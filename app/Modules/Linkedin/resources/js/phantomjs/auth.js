/**
 * auth.js
 *
 * Linkedin authentication file for PhantomJS scripts
 *
 */

const phantom = require('phantom');
const fs = require('fs');
const moment = require('moment');

module.exports = {

    // log in to linkedin
    async login(account, settings) {

        let {email, password} = account;
        let {cookiesFile, loadCookies} = settings;

        const nolog = () => {
            //
        };

        const instance = await phantom.create(['--ignore-ssl-errors=true', '--disk-cache=true', '--load-images=no'], {
            logger: {
                warn: nolog,
                debug: nolog,
                error: console.log
            }
        });
        const page = await instance.createPage();

        await page.property('viewportSize', {width: 1024, height: 600});
        await page.setting('userAgent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');


        await page.on("onResourceReceived", async () => {

            let cookies = JSON.stringify(await page.property('cookies'));

            // save cookies
            fs.writeFileSync(cookiesFile, cookies);

        });

        if (loadCookies) {

            const data = fs.readFileSync(cookiesFile, 'utf8');

            // load cookies from cookies file
            Array.prototype.forEach.call(JSON.parse(data), async x => {
                await page.addCookie(x);
            });

        }

        await page.open('https://www.linkedin.com/groups/my-groups');

        // we're already logged in
        if (['LinkedIn Groups | LinkedIn', 'My Groups'].indexOf(await page.property('title')) !== -1) {
            await page.off('onResourceReceived');

            return {instance, page, is_authenticated: true};
        }


        // open login page
        await page.open('https://www.linkedin.com/uas/login');

        // before submiting form, register callback to know when page
        // is done loading after we click submit
        let waitForResponse = () => {
            return new Promise(async resolve => {
                await page.on("onLoadFinished", async () => {
                    await page.off('onLoadFinished');
                    resolve();
                });
            });
        };

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

        await waitForResponse();

        // login was successful
        if (await page.property('title') === 'LinkedIn') {

            await page.off('onResourceReceived');

            // save a screenshot
            // const time = moment().format('YYYY-MM-DD HH:mm:ss').replace(/:/g, ' ');
            // await page.render(__dirname + `/success/${email} at ${time}.png`);

            // success
            return {instance, page, is_authenticated: true};

        }
        // linkedin didn't let us in
        else {

            await page.off('onResourceReceived');

            // format current time
            const time = moment().format('YYYY-MM-DD HH:mm:ss').replace(/:/g, ' ');

            // save screenshot of current page state
            await page.render(__dirname + `/errors/${email} at ${time}.png`);

            // return false
            return {instance, page, is_authenticated: false};

        }

    }

};