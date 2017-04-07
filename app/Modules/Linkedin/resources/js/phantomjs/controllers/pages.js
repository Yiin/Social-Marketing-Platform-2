/**
 * Pages controller
 */

const fs = require('fs');
const phantom = require('phantom');

async function cookies(page, cookiesFile, loadCookies = false) {

    if (loadCookies) {

        try {

            await page.clearCookies();

            fs.accessSync(cookiesFile);


            // load cookies from cookies file
            Array.prototype.forEach.call(JSON.parse(fs.readFileSync(cookiesFile, 'utf8')), async x => {
                await page.addCookie(x);
            });
        }
        catch (e) {

            // cookies file doesn't exist

        }

    }

    // save cookies
    await page.on("onResourceReceived", async () => {

        fs.writeFileSync(cookiesFile, JSON.stringify(await page.property('cookies')));

    });

}


// set page properties and settings
async function init(page) {

    await page.property('viewportSize', {width: 1024, height: 600});
    await page.setting('userAgent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');

}


class PagesController {

    constructor() {

        this.pages = {};
        this.instances = {};

    }


    static async newInstance() {

        return await phantom.create(['--ignore-ssl-errors=true', '--load-images=no']);

    }


    async saveInstance(params) {

        let {instance, cookiesFile} = params;

        // if we already have a page for this account open, close it
        if (typeof this.instances[cookiesFile] !== 'undefined') {
            if (this.instances[cookiesFile] !== instance) {
                console.log('exiting instance');
                await this.instances[cookiesFile].exit();
            }
            else {
                console.log('dont exit instance');
            }
        }

        this.instances[cookiesFile] = instance;

    }


    async getInstance(params) {

        const {cookiesFile} = params;

        if (typeof this.instances[cookiesFile] !== 'undefined') {
            console.log('using saved instance');
            return this.instances[cookiesFile];
        }

        console.log('using new instance');
        return await this.newInstance({
            cookiesFile,
            loadCookies: true
        });
    }


    async newPage(params) {

        const {cookiesFile, loadCookies = false} = params;

        let page = await this.instance.createPage();

        await init(page);
        await cookies(page, cookiesFile, loadCookies);

        return page;

    }


    async savePage(params) {

        let {page, cookiesFile} = params;

        // if we already have a page for this account open, close it
        if (typeof this.pages[cookiesFile] !== 'undefined') {
            if (this.pages[cookiesFile] !== page) {
                console.log('closing page');
                await this.pages[cookiesFile].close();
            }
            else {
                console.log('dont close page');
            }
        }

        this.pages[cookiesFile] = page;

    }


    async getPage(params) {

        const {cookiesFile} = params;

        if (typeof this.pages[cookiesFile] !== 'undefined') {
            console.log('using saved page');
            return this.pages[cookiesFile];
        }

        console.log('using new page');
        return await this.newPage({
            cookiesFile,
            loadCookies: true
        });
    }
}

module.exports = PagesController;