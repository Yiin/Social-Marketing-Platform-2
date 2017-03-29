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

auth.login(account, settings).then(async response => {

    let {instance, page, is_authenticated} = response;

    if (is_authenticated) {

        const groups = await getGroups(instance, page);

        console.log(JSON.stringify(groups));

        await instance.exit();

    }
    else {

        console.log(JSON.stringify([]));

        await instance.exit();

    }

});

async function getGroups(instance, page) {
    page.open('https://www.linkedin.com/groups/my-groups');

    await sleep(3000);

    let groups = await page.evaluate(function () {
        var arr = [];
        var entities = document.getElementsByClassName('entity-name');

        Array.prototype.filter.call(entities, function (entity) {
            arr.push(entity.attributes.href.nodeValue.replace("/groups/", ""));
        });

        return arr;
    });

    let promises = [];

    groups.forEach(group => {
        promises.push(getGroupInfo(instance, group));
    });

    groups = await Promise.all(promises);

    return groups;
}

async function getGroupInfo(instance, gid) {
    let tab = await instance.createPage();

    // page properties and settings
    await tab.property('viewportSize', {width: 1024, height: 600});
    await tab.setting('userAgent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');

    // load cookies
    const data = fs.readFileSync(settings.cookiesFile, 'utf8');
    Array.prototype.forEach.call(JSON.parse(data), async x => {
        await tab.addCookie(x);
    });

    // open groups info page
    await tab.open('https://www.linkedin.com/grps?displaySettings=&gid=' + gid);

    // save screenshot of current page state
    // console.log('saving screenshot');
    // await tab.render(__dirname + `/debug/${account.email} - Group ${gid}.png`);

    let group = await tab.evaluate(function () {
        var name = document.querySelector('.group-name > a').text;
        var members = document.querySelector('.member-count').text.replace(/,| members/g, '');

        return {
            name: name,
            members: members
        };
    });
    group.id = gid;

    return group;
}