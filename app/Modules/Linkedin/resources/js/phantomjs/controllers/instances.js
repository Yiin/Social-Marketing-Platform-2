const Instance = require('../models/instance');

class InstancesController {

    constructor() {

        this.instances = {};

    }

    static async create(cookiesFile) {

        return await (new Instance(cookiesFile)).init();

    }


    async remove(instance) {

        const cookiesFile = instance.getCookiesFile();

        await instance.destroy();

        if (typeof this.instances[cookiesFile] !== 'undefined') {
            delete this.instances[cookiesFile];
        }

    }


    async save(instance) {

        const cookiesFile = instance.getCookiesFile();

        // if we already have a page for this account open, close it
        if (typeof this.instances[cookiesFile] !== 'undefined') {
            if (this.instances[cookiesFile] !== instance) {
                console.log('destroying old instance');
                await this.instances[cookiesFile].destroy();
            }
        }

        this.instances[cookiesFile] = instance;

    }


    async get(cookiesFile) {

        if (typeof this.instances[cookiesFile] !== 'undefined') {
            console.log('using saved instance');
            return this.instances[cookiesFile];
        }

        console.log('using new instance');
        return await this.constructor.create(cookiesFile);
    }
}

module.exports = InstancesController;