require('./bootstrap');

Vue.component('google-accounts-table', require('./components/GoogleAccountsTable.vue'));
Vue.component('google-account-row', require('./components/GoogleAccountRow.vue'));
Vue.component('google-plus-posting-panels', require('./components/GooglePlusPostingPanels.vue'));

Vue.component('facebook-accounts-table', require('./components/FacebookAccountsTable.vue'));
Vue.component('facebook-account-row', require('./components/FacebookAccountRow.vue'));
Vue.component('facebook-posting-panels', require('./components/FacebookPostingPanels.vue'));

Vue.component('twitter-accounts-table', require('./components/TwitterAccountsTable.vue'));
Vue.component('twitter-account-row', require('./components/TwitterAccountRow.vue'));
Vue.component('twitter-posting-panels', require('./components/TwitterPostingPanels.vue'));

Vue.component('linkedin-accounts-table', require('./components/LinkedinAccountsTable.vue'));
Vue.component('linkedin-account-row', require('./components/LinkedinAccountRow.vue'));
Vue.component('linkedin-posting-panels', require('./components/LinkedinPostingPanels.vue'));

const app = new Vue({
    el: '#app'
});