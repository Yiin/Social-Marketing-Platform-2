<template>
    <table class="table">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Username</th>
            <th>Password</th>
            <th class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr is="google-account-row" v-for="(account, index) in accounts" :account="account" :onDelete="onDelete" :index="index"></tr>
        <tr>
            <td></td>
            <td>
                <input @keydown.enter="create" type="text" class="form-control" :class="{ error: errors.username }" placeholder="Username or Email" name="username" v-model="account.username">
            </td>
            <td>
                <input @keydown.enter="create" type="password" class="form-control" :class="{ error: errors.password }" placeholder="Password" name="password" v-model="account.password">
            </td>
            <td class="text-right">
                <a v-if="loginUrl" :href="loginUrl" class="btn btn-success btn-simple-btn-xs">
                    Confirm login
                </a>
                <button v-else @click="create" class="btn btn-primary btn-simple-btn-xs">
                    Add account
                </button>
            </td>
        </tr>
        </tbody>
    </table>
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

                this.$http.post('http://roislope.com/smp/public/google-account', this.account).then(response => {
                    if (response.body.loginUrl) {
                        this.loginUrl = response.body.loginUrl;
                    }
                    else {
                        this.account = {};
                        this.$set(this, 'accounts', response.body);
                    }
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