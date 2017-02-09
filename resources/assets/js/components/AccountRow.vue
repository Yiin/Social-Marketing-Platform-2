<template>
    <tr>
        <td class="text-center">{{ index + 1 }}</td>
        <td>
            <template v-if="editing">
                <div>
                    <select data-title="Select Service" data-style="btn-default btn-block" data-menu-style="dropdown-blue" :class="{ error: errors.social_media_service_id }" class="form-control selectpicker" v-model="account.social_media_service_id">
                        <option v-for="service in services" :value="service.id" :selected="service.id == account.social_media_service_id">{{ service.name }}</option>
                    </select>
                </div>
            </template>
            <template v-else>{{ services.find(service => service.id == account.social_media_service_id).name }}</template>
        </td>
        <td>
            <input v-if="editing" v-model="account.username" :class="{ error: errors.username }" type="text" class="form-control">
            <template v-else>{{ account.username }}</template>
        </td>
        <td>
            <input v-if="editing" v-model="account.password" :class="{ error: errors.password }" type="password" class="form-control">
            <template v-else>{{ "•".repeat(account.password.length) }}</template>
        </td>
        <td class="text-right">
            <button v-if="editing" @click="save" class="btn btn-success btn-xs">
                Save <i class="fa fa-save"></i>
            </button>
            <button v-else @click="edit" type="button" class="btn btn-primary btn-xs">
                Edit <i class="fa fa-edit"></i>
            </button>
            <template v-if="confirm">
                <button @click="confirmRemove" class="btn btn-danger btn-fill btn-xs">
                    Confirm deletion <i class="fa fa-remove"></i>
                </button>
                <button @click="cancelRemove" class="btn btn-default btn-xs">
                    Cancel <i class="fa fa-ban"></i>
                </button>
            </template>
            <button v-else @click="remove" class="btn btn-danger btn-xs">
                Delete <i class="fa fa-remove"></i>
            </button>
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
        props: ['account', 'onDelete', 'index', 'services'],
        data() {
            return {
                errors: {},
                editing: false,
                confirm: false
            }
        },
        methods: {
            edit() {
                this.editing = true;

                setTimeout(() => {
                    window.jQuery('.selectpicker').selectpicker();
                });
            },
            save() {
                this.errors = {};

                this.$http.put(`/account/${this.account.id}`, this.account).then(() => {
                    this.editing = false;
                }).catch(response => {
                    this.errors = response.body;
                });
            },
            remove() {
                this.confirm = true;
            },
            confirmRemove() {
                this.editing = false;
                this.confirm = false;
                this.$http.delete(`/account/${this.account.id}`).then(this.onDelete.bind(null, this.account.id));
            },
            cancelRemove() {
                this.confirm = false;
            }
        }
    }
</script>