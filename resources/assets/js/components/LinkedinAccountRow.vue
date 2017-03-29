<template>
    <tr>
        <td class="text-center">{{ index + 1 }}</td>
        <td>
            <input v-if="editing" v-model="account.email" :class="{ error: errors.email }" type="text"
                   @keydown.enter="save"
                   class="form-control">
            <template v-else>{{ account.email }}</template>
        </td>
        <td>
            <input v-if="editing" v-model="account.password" :class="{ error: errors.password }" type="password"
                   @keydown.enter="save"
                   class="form-control">
            <template v-else>{{ "•".repeat(account.password.length) }}</template>
        </td>
        <td>
            {{ account.groups.length }}
        </td>
        <td class="text-right">
            <template v-if="authorizing">

                <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                Authorizing...

            </template>
            <template v-else>

                <template v-if="fetchingGroups">

                    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                    Fetching groups...

                </template>
                <template v-else>

                    <button @click="groups" class="btn btn-primary btn-simple btn-xs">
                        Fetch groups

                    </button>

                    <template v-if="editing">

                        <button @click="save" class="btn btn-success btn-xs">
                            Save <i class="fa fa-save"></i>
                        </button>

                    </template>
                    <template v-else>

                        <button @click="edit" type="button" class="btn btn-primary btn-xs">
                            Edit <i class="fa fa-edit"></i>
                        </button>

                    </template>


                    <template v-if="confirm">

                        <button @click="confirmRemove" class="btn btn-danger btn-fill btn-xs">
                            Confirm deletion <i class="fa fa-remove"></i>
                        </button>
                        <button @click="cancelRemove" class="btn btn-default btn-xs">
                            Cancel <i class="fa fa-ban"></i>
                        </button>

                    </template>
                    <template v-else>

                        <button @click="remove" class="btn btn-danger btn-xs">
                            Delete <i class="fa fa-remove"></i>
                        </button>

                    </template>

                </template>

            </template>
        </td>
    </tr>
</template>

<style>
    td[contenteditable="true"] {
        border-left: 1px solid;
    }
</style>

<script>
    export default {
        props: ['account', 'onDelete', 'index'],
        data() {
            return {
                errors: {},
                editing: false,
                confirm: false,
                fetchingGroups: false,
                authorizing: false
            }
        },
        methods: {
            groups() {
                this.fetchingGroups = true;

                this.$http.post(Laravel.routes['linkedin-fetch-groups'].replace('{linkedin_account}', this.account.id), this.account).then(response => {
                    this.fetchingGroups = false;
                    this.account.groups = response.body;
                }).catch(response => {
                    this.fetchingGroups = false;
                    this.errors = response.body;
                });
            },
            edit() {
                this.editing = true;

                setTimeout(() => {
                    window.jQuery('.selectpicker').selectpicker();
                });
            },
            save() {
                this.errors = {};
                this.authorizing = true;

                this.$http.put(Laravel.routes['linkedin-account.update'].replace('{linkedin_account}', this.account.id), this.account).then(() => {
                    this.editing = false;
                    this.authorizing = false;
                }).catch(response => {
                    this.errors = response.body;
                    this.authorizing = false;
                });
            },
            remove() {
                this.confirm = true;
            },
            confirmRemove() {
                this.editing = false;
                this.confirm = false;
                this.$http.delete(Laravel.routes['linkedin-account.destroy'].replace('{linkedin_account}', this.account.id)).then(this.onDelete.bind(null, this.account.id));
            },
            cancelRemove() {
                this.confirm = false;
            }
        }
    }
</script>