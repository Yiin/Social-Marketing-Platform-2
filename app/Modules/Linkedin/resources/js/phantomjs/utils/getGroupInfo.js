const sleep = require('../utils/sleep');

async function getGroupInfo(page, gid, retries = 0) {

    // open groups info page
    await page.open('https://www.linkedin.com/grps?displaySettings=&gid=' + gid);

    await sleep(5000);

    let group = await page.evaluate(function (gid) {
	if(document.querySelector('.group-name > a')) {
            var name = document.querySelector('.group-name > a').text;
            var members = document.querySelector('.member-count').text.replace(/,| members/g, '');

            return {
                id: gid,
                name: name,
                members: members
            };
        }
        return { error: true, id: gid, name: 'undefined', members: 0 };
    }, gid);

    if (group.error && retries < 5) {
	return await getGroupInfo(page, gid, retries + 1);
    }
    return group;

};

module.exports = getGroupInfo;