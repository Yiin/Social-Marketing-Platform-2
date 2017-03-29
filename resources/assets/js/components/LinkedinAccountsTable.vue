<template>
    <table class="table">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Email</th>
            <th>Password</th>
            <th>Groups</th>
            <th class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr is="linkedin-account-row" v-for="(account, index) in accounts" :account="account" :onDelete="onDelete" :index="index"></tr>
        <tr>
            <td></td>
            <td>
                <input @keydown.enter="create" type="text" class="form-control" :class="{ error: errors.email }" placeholder="Email" name="email" v-model="account.email">
            </td>
            <td>
                <input @keydown.enter="create" type="password" class="form-control" :class="{ error: errors.password }" placeholder="Password" name="password" v-model="account.password">
            </td>
            <td></td>
            <td class="text-right">
                <template v-if="checking">
                    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                    Authorizing...
                </template>
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
                checking: false
            }
        },


        methods: {
            create() {
                this.errors = {};
                this.checking = true;

                this.$http.post(Laravel.routes['linkedin_account.store'], this.account).then(response => {
                    this.checking = false;
                    this.account = {};
                    this.$set(this, 'accounts', response.body);
                }).catch(response => {
                    this.checking = false;
                    this.$set(this, 'errors', response.body);
                });
            },


            onDelete(id) {
                this.$set(this, 'accounts', this.accounts.filter(account => account.id !== id));
            }
        },


        /**
         * Prepare the component
         */
        mounted() {
            this.accounts = JSON.parse(this.data);
        }
    }
</script>