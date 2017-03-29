const auth = require('./auth.js');

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

    console.log(JSON.stringify({
        is_authenticated: response.is_authenticated
    }));

    await response.instance.exit();

});