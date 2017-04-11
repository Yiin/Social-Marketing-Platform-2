(async function () {
    // utils
    const path = require('path');
    const HttpStatus = require('http-status-codes');
    const moment = require('moment');
    const winston = require('winston');

    const logger = new (winston.Logger)({
        transports: [
            new (winston.transports.File)({
                name: 'info-file',
                filename: path.join(__dirname, './logs/info.log'),
                level: 'info'
            }),
            new (winston.transports.File)({
                name: 'error-file',
                filename: path.join(__dirname, './logs/error.log'),
                level: 'error'
            })
        ]
    });

    // webserver
    const express = require('express');
    const app = express();
    const bodyParser = require('body-parser');

    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({extended: true}));

    // controllers
    const InstancesController = require('./controllers/instances');
    const instancesController = new InstancesController();

    // scripts
    const sleep = require('./utils/sleep');
    const waitForResponse = require('./utils/waitForResponse');
    const getCookiesFile = require('./utils/getCookiesFile');
    const getGroupInfo = require('./utils/getGroupInfo');
    const postToGroup = require('./utils/postToGroup');

    /**
     * Check if we can login
     *
     * @requires email
     * @requires password
     *
     * @returns HttpStatus.OK | HttpStatus.LOCKED | HttpStatus.UNAUTHORIZED
     */

    app.post('/authenticate', async (req, res) => {
        const {email, password} = req.body;

        logger.log("info", `${email} is trying to authenticate.`);

        const cookiesFile = getCookiesFile(email);

        // create fresh new page
        let instance = await InstancesController.create(cookiesFile);
        let page = await instance.createPage();

        // open login page
        await page.open('https://www.linkedin.com/uas/login');

        // fill login form
        await page.evaluate(function (account) {

            // enter authentication details
            document.getElementById("session_key-login").value = account.email;
            document.getElementById("session_password-login").value = account.password;

            // click login button
            document.getElementById("login").submit();

        }, {
            email, password
        });

        await waitForResponse(page);

        // login was successful
        if (await page.property('title') === 'LinkedIn') {

            await instance.closePages();
            await instancesController.save(instance);

            logger.log("info", `${email} authenticated successfully.`);
            return res.status(HttpStatus.OK).send();

        }
        // linkedin didn't let us in
        else {

            // check if we need to enter confirmation code
            if (await page.property('url') === 'https://www.linkedin.com/uas/consumer-email-challenge') {

                await instancesController.save(instance);

                logger.log('info', `${email} requires sign-in verification.`);
                return res.status(HttpStatus.LOCKED).send();

            }

            await instancesController.remove(instance);

            // return false
            logger.log("info", `${email} authentication failed.`);
            return res.status(HttpStatus.UNAUTHORIZED).send();

        }

    });

    /**
     * Verify sign-in with a code
     *
     * @requires email
     * @requires password
     * @requires code
     *
     * @returns HttpStatus.OK | HttpStatus.EXPECTATION_FAILED
     */
    app.post('/unlock', async (req, res) => {

        const {email, code} = req.body;

        const cookiesFile = getCookiesFile(email);

        // get existing page
        let instance = await instancesController.get(cookiesFile);
        let page = await instance.getPage();

        // check if we can enter confirmation code
        if (await page.property('url') === 'https://www.linkedin.com/uas/consumer-email-challenge') {

            logger.log("info", `${email} verifying sign-in with a code ${code}.`);

            await page.evaluate(function (code) {
                document.getElementById("verification-code").value = code;
                document.getElementsByClassName("flow-login-form")[0].submit();
            }, code);

            await sleep(5000);

            // login was successful
            if (await page.property('title') === 'LinkedIn') {

                await instance.closePages();
                await instancesController.save(instance);

                logger.log("info", `${email} sign-in verification was successful.`);
                return res.status(HttpStatus.OK).send();

            }

            logger.log("info", `${email} sign-in verification wasn't successful.`);

            return res.status(HttpStatus.EXPECTATION_FAILED).send();

        }

        logger.log("info", `${email} sign-in verification wasn't successful because email challenge page wasn't open.`);

        return res.status(HttpStatus.EXPECTATION_FAILED).send();

    });

    /**
     * Get account groups
     *
     * @requires email
     * @requires password
     *
     * @returns HttpStatus.OK: [{id, name, members}, ...] | HttpStatus.UNAUTHORIZED
     */
    app.post('/groups', async (req, res) => {

        const {email, password} = req.body;

        logger.log("info", `${email} is fetching groups.`);

        const cookiesFile = getCookiesFile(email);

        // get existing page
        let instance = await instancesController.get(cookiesFile);
        let page = await instance.getPage();

        await page.open('https://www.linkedin.com/groups/my-groups');

        // if we're not signed-in yet, do it now
        if (['LinkedIn Groups | LinkedIn', 'My Groups'].indexOf(await page.property('title')) === -1) {

            // open login page
            await page.open('https://www.linkedin.com/uas/login');

            // fill login form
            await page.evaluate(function (account) {

                // enter authentication details
                document.getElementById("session_key-login").value = account.email;
                document.getElementById("session_password-login").value = account.password;

                // click login button
                document.getElementById("login").submit();

            }, {
                email, password
            });

            await waitForResponse(page);

            // login wasn't successful
            if (await page.property('title') !== 'LinkedIn') {

                await instancesController.remove(instance);

                logger.log("info", `${email} authentication failed.`);
                return res.status(HttpStatus.UNAUTHORIZED).send();

            }

            // everything's ok
            await instancesController.save(instance);

            // open my-groups page again
            await page.open('https://www.linkedin.com/groups/my-groups');

        }

        logger.log("info", `${email} authenticated successfully.`);

        await sleep(3000);

        let groups = await page.evaluate(function () {
            var arr = [];
            var entities = document.getElementsByClassName('entity-name');

            Array.prototype.filter.call(entities, function (entity) {
                arr.push(entity.attributes.href.nodeValue.replace("/groups/", ""));
            });

            return arr;
        });

	logger.log("info", `${email} groups: ` + JSON.stringify(groups));

        let promises = [];

        groups.forEach(group => {
            promises.push((async () => {

                let groupPage = await instance.createPage();

                const groupinfo = await getGroupInfo(groupPage, group);

                await instance.closePage(groupPage);

                return groupinfo;

            })());
        });

        groups = await Promise.all(promises);
	groups = groups.filter(group => !group.error);

        await instancesController.save(instance);

        return res.status(HttpStatus.OK).send(groups);
    });

    /**
     * Post to selected account groups
     *
     * @requires email
     * @requires password
     * @requires groupid
     * @requires caption
     * @requires message
     *
     * @returns HttpStatus.OK: {link} | HttpStatus.UNAUTHORIZED
     */
    app.post('/post', async (req, res) => {

        const {email, password, groupid, caption, message} = req.body;

        const cookiesFile = getCookiesFile(email);

        // open new page
        let instance = await instancesController.get(cookiesFile);
        let page = await instance.createPage({
            loadCookies: true
        });

        await page.open('https://www.linkedin.com/groups/' + groupid);

        // if we're not signed-in yet, do it now
        if (['LinkedIn Groups | LinkedIn'].indexOf(await page.property('title')) === -1) {

            // open login page
            await page.open('https://www.linkedin.com/uas/login');

            // fill login form
            await page.evaluate(function (account) {

                // enter authentication details
                document.getElementById("session_key-login").value = account.email;
                document.getElementById("session_password-login").value = account.password;

                // click login button
                document.getElementById("login").submit();

            }, {
                email, password
            });

            await waitForResponse(page);

            // login wasn't successful
            if (await page.property('title') !== 'LinkedIn') {

                await instancesController.remove(instance);

                logger.log("error", `${email} authentication failed.`);
                return res.status(HttpStatus.UNAUTHORIZED).send();

            }

            // everything's ok
            await instancesController.save(instance);

        }

        const result = await postToGroup(page, {groupid, caption, message});

        await instance.closePage(page);
        await instancesController.save(instance);

        return res.status(HttpStatus.OK).send(result);

    });

    /**
     * Run the server
     */
    const port = 3000;
    app.listen(port, () => {
        console.log("info", `port :${port}`);
    });
}());