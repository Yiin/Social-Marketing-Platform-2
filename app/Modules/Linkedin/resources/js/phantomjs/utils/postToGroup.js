const sleep = require('../utils/sleep');

module.exports = async function (page, postData) {

    if (await page.property('url') !== 'https://www.linkedin.com/groups/' + postData.groupid) {

        await page.open('https://www.linkedin.com/groups/' + postData.groupid);

        await sleep(5000);

    }

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

};