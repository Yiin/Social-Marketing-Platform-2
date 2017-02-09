<template>
    <table class="table">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Type</th>
            <th>Account Username</th>
            <th>Password</th>
            <th class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr is="account-row" v-for="(account, index) in accounts" :services="services" :account="account" :onDelete="onDelete" :index="index"></tr>
        <tr>
            <td></td>
            <td>
                <select data-title="Select Service" data-style="btn-default btn-block" data-menu-style="dropdown-blue" class="form-control selectpicker" :class="{ error: errors.social_media_service_id }" v-model="account.social_media_service_id">
                    <option v-for="service in services" :value="service.id">{{ service.name }}</option>
                </select>
            </td>
            <td><input type="text" class="form-control" :class="{ error: errors.username }" placeholder="Username" name="username" v-model="account.username"></td>
            <td><input type="password" class="form-control" :class="{ error: errors.password }" placeholder="Password" name="password" v-model="account.password"></td>
            <td class="text-right">
                <button @click="create" class="btn btn-primary btn-simple-btn-xs">
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
            'data', 'sms'
        ],
        data() {
            return {
                accounts: [],
                account: {},
                errors: {}
            }
        },
        computed: {
            services() {
                return JSON.parse(this.sms);
            }
        },
        methods: {
            create() {
                this.errors = {};

                this.$http.post('/account', this.account).then(response => {
                    this.account = {};
                    this.$set(this, 'accounts', response.body);
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