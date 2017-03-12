<template>
    <div>
        <template v-if="!accounts.length">
            <a v-if="loginUrl" :href="loginUrl" class="btn btn-success btn-simple-btn-xs">
                Login with twitter
            </a>
            <button v-else @click="create" class="btn btn-primary btn-simple-btn-xs">
                Add first account
            </button>
        </template>
        <table v-else class="table">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Name</th>
                <th>Access Token</th>
                <th class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr is="twitter-account-row" v-for="(account, index) in accounts" :account="account" :onDelete="onDelete" :index="index"></tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">
                    <a v-if="loginUrl" :href="loginUrl" class="btn btn-success btn-simple-btn-xs">
                        Login with twitter
                    </a>
                    <button v-else @click="create" class="btn btn-primary btn-simple-btn-xs">
                        Add account
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: [
            'data'
        ],
        data() {
            return {
                accounts: [],
                account: {},
                errors: {},
                loginUrl: null
            }
        },
        methods: {
            create() {
                this.errors = {};
                this.loginUrl = null;

                this.$http.post(Laravel.routes['twitter-account.store'], this.account).then(response => {
                    this.loginUrl = response.body;
                }).catch(response => {
                    this.$set(this, 'errors', response.body);
                });
            },
            onDelete(id) {
                this.$set(this, 'accounts', this.accounts.filter(account => account.id !== id));
            }
        },
        mounted() {
            this.accounts = JSON.parse(this.data);
            console.log(this.accounts);
        }
    }
</script>