const sleep = require('../utils/sleep');

module.exports = async (page, gid) => {

    // open groups info page
    await page.open('https://www.linkedin.com/grps?displaySettings=&gid=' + gid);

    await sleep(1000);

    await page.render('pages/' + gid + '.png');

    return await page.evaluate(function (gid) {
        var name = document.querySelector('.group-name > a').text;
        var members = document.querySelector('.member-count').text.replace(/,| members/g, '');

        return {
            id: gid,
            name: name,
            members: members
        };
    }, gid);

};