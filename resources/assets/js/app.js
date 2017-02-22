require('./bootstrap');

Vue.component('clients-table', require('./components/ClientsTable.vue'));
Vue.component('client-row', require('./components/ClientRow.vue'));

Vue.component('google-accounts-table', require('./components/GoogleAccountsTable.vue'));
Vue.component('google-account-row', require('./components/GoogleAccountRow.vue'));

Vue.component('facebook-accounts-table', require('./components/FacebookAccountsTable.vue'));
Vue.component('facebook-account-row', require('./components/FacebookAccountRow.vue'));

Vue.component('google-plus-accounts-table', require('./components/GooglePlusAccountsTable.vue'));
Vue.component('google-plus-posting-panels', require('./components/GooglePlusPostingPanels.vue'));

Vue.component('facebook-posting-panels', require('./components/FacebookPostingPanels.vue'));

const app = new Vue({
    el: '#app'
});