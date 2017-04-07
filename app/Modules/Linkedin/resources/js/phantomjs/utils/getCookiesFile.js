/**
 * Returns path of cookie file for specified account
 *
 * @param email
 */

const path = require('path');

module.exports = email => path.join(__dirname, '../cookies', `${email}.json`);