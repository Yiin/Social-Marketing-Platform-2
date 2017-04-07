/**
 * Pages controller
 */

const fs = require('fs');
const phantom = require('phantom');

class Instance {

    constructor(cookiesFile) {

        this._cookiesFile = cookiesFile;
        this._pages = [];

    }


    async init() {

        this._instance = await phantom.create(['--ignore-ssl-errors=true', '--load-images=no']);

        return this;

    }


    getCookiesFile() {

        return this._cookiesFile;

    }


    async createPage(params = {}) {

        const {loadCookies = false} = params;

        let page = await this._instance.createPage();

        await page.property('viewportSize', {width: 1024, height: 600});
        await page.setting('userAgent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');

        if (loadCookies) {
            try {
                fs.accessSync(this._cookiesFile);

                // load cookies from cookies file
                await Promise.all(Array.prototype.map.call(JSON.parse(fs.readFileSync(this._cookiesFile, 'utf8')), async x => {
                    await page.addCookie(x);
                }));
            }
            catch (e) {
                // cookies file doesn't exist
            }
        }

        // save cookies
        await page.on("onResourceReceived", async () => {
            await this.saveCookies(page);
        });

        this._pages.push(page);

        return page;

    }

    async saveCookies(page) {

        fs.writeFileSync(this._cookiesFile, JSON.stringify(await page.property('cookies')));

    }


    getPage() {

        if (this._pages.length) {
            return this._pages[0];
        }
        return this.createPage({loadCookies: true});

    }


    async closePage(page) {

        let index = this._pages.indexOf(page);

        if (index !== -1) {
            let page = this._pages[index];

            this._pages.splice(index, 1);

            await page.close();
        }

    }


    async closePages() {

        await Promise.all(this._pages.map(async page => {
            await this.closePage(page);
        }));

    }


    async destroy() {

        await this.closePages();
        await this._instance.exit();

    }

}

module.exports = Instance;