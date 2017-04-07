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
        <tr v-if="status === LOCKED">
            <td colspan="3">
                <i class="fa fa-lock"></i>
                <template v-if="errors.code">
                    Invalid code.
                </template>
                Please enter sign-in confirmation code which was sent to {{ account.email }}.
            </td>
            <td>
                <input v-model="account.code" @keydown.enter="unlock" type="text" class="form-control" :class="{ error: errors.code }" placeholder="Confirmation Code (check email)">
            </td>
            <td>
                <template v-if="status === VALIDATING_CODE">
                    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                    Validating confirmation code...
                </template>
                <template v-else>
                    <button @click="unlock" class="btn btn-primary btn-simple-btn-xs">
                        Confirm
                    </button>
                </template>
            </td>
        </tr>
        <tr v-else>
            <td></td>
            <td>
                <input @keydown.enter="create" type="text" class="form-control" :class="{ error: errors.email }" placeholder="Email" name="email" v-model="account.email">
            </td>
            <td>
                <input @keydown.enter="create" type="password" class="form-control" :class="{ error: errors.password }" placeholder="Password" name="password" v-model="account.password">
            </td>
            <td></td>
            <td class="text-right">
                <template v-if="status === CHECKING">
                    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                    Authorizing...
                </template>
                <template v-else>
                    <button @click="create" class="btn btn-primary btn-simple-btn-xs">
                        Add account
                    </button>
                </template>
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
                status: null,

                CHECKING: 'checking',
                LOCKED: 'locked',
                VALIDATING_CODE: 'validating'
            }
        },


        methods: {
            create() {
                this.errors = {};
                this.status = this.CHECKING;

                this.$http.post(Laravel.routes['linkedin-account.store'], this.account).then(response => {
                    switch (response.body.status) {
                        case 'authenticated':
                            this.status = null;
                            this.account = {};
                            this.$set(this, 'accounts', response.body.accounts);
                            break;
                        case 'locked':
                            this.$set(this, 'status', this.LOCKED);
                            break;
                    }
                }).catch(response => {
                    this.status = null;
                    this.$set(this, 'errors', response.body);
                });
            },


            unlock() {
                this.errors = {};
                this.status = this.VALIDATING_CODE;

                this.$http.post(Laravel.routes['linkedin-account.unlock'], this.account).then(response => {
                    this.status = null;
                    this.account = {};
                    this.$set(this, 'accounts', response.body);
                }).catch(response => {
                    this.status = this.LOCKED;
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