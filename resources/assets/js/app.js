require('./bootstrap');

Vue.component('accounts-table', require('./components/AccountsTable.vue'));
Vue.component('account-row', require('./components/AccountRow.vue'));

Vue.component('clients-table', require('./components/ClientsTable.vue'));
Vue.component('client-row', require('./components/ClientRow.vue'));

Vue.component('google-plus-accounts-table', require('./components/GooglePlusAccountsTable.vue'));
Vue.component('google-plus-posting-panels', require('./components/GooglePlusPostingPanels.vue'));

const app = new Vue({
    el: '#app'
});