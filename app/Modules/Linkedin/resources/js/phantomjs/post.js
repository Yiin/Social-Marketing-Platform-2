/**
 * check-auth.js
 *
 * Node.js script for checking user auth-details.
 *
 * This script tries to login with given auth details.
 *
 */

const fs = require('fs');
const auth = require('./auth.js');
const sleep = require('./sleep.js');

let args = require('minimist')(process.argv.slice(2));

const account = {
    email: args.email,
    password: args.password
};

const settings = {
    cookiesFile: args.cookiesFile,
    loadCookies: args.loadCookies
};

const postData = {
    groupid: args.groupid,
    caption: args.caption,
    message: args.message
};

auth.login(account, settings).then(async response => {

    let {instance, page, is_authenticated} = response;

    if (is_authenticated) {

        const data = await post(page);

        console.log(JSON.stringify(data));

        await instance.exit();

    }
    else {

        console.log(JSON.stringify({
            link: ''
        }));

        await instance.exit();

    }

});


async function post(page) {
    await page.open('https://www.linkedin.com/groups/' + postData.groupid);

    await sleep(5000);

    await page.evaluate(function (postData) {
        document.getElementsByClassName('input-title')[0].focus();
        document.getElementsByClassName('input-title')[0].value = postData.caption;
        document.getElementsByClassName('input-body')[0].value = postData.message;
        document.getElementsByClassName('action-submit')[0].disabled = false;
        document.getElementsByClassName('action-submit')[0].click();
    }, postData);

    await sleep(3000);

    return await page.evaluate(function () {
        var link = document.querySelector('.post-view .post-title > a').href;

        return {
            link: link
        };
    });
}