<template>
    <tr>
        <td class="text-center">{{ index + 1 }}</td>
        <td>
            {{ account.name }}
        </td>
        <td>
            {{ account.oauth_token.substring(0, 10) }}...
        </td>
        <td class="text-right">
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
        props: ['account', 'onDelete', 'index'],
        data() {
            return {
                errors: {},
                editing: false,
                confirm: false,
                groupsFile: null
            }
        },
        methods: {
            remove() {
                this.confirm = true;
            },
            confirmRemove() {
                this.editing = false;
                this.confirm = false;
                this.$http.delete(`/twitter-account/${this.account.id}`).then(this.onDelete.bind(null, this.account.id));
            },
            cancelRemove() {
                this.confirm = false;
            }
        }
    }
</script>