<template>
    <tr>
        <td class="text-center">{{ index + 1 }}</td>
        <td>
            {{ account.name }} ({{ account.groups.length }} groups)
        </td>
        <td>
            {{ account.access_token.substring(0, 20) }}...
        </td>
        <td>
            <input type="file" name="groups" placeholder="Select Groups File" @change="onFileChange">
        </td>
        <td class="text-right">
            <button v-if="groupsFile" @click="upload" class="btn btn-primary btn-fill btn-xs">
                Upload groups file
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
                this.$http.delete(Laravel.routes['facebook-account.destroy'].replace('{facebook_account}', this.account.id)).then(this.onDelete.bind(null, this.account.id));
            },
            cancelRemove() {
                this.confirm = false;
            },
            upload() {
                this.$http.post(Laravel.routes['facebook-groups-file'], {
                    facebook_account_id: this.account.id,
                    html: this.groupsFile
                }).then(() => {
                    location.reload();
                }).catch(() => {
                    this.groupsFile = null;
                });
            },
            onFileChange(e) {
                let reader = new FileReader();
                let files = e.target.files;
                if (!files.length)
                    return;

                reader.readAsText(files[0]);

                this.groupsFile = null;

                reader.onload = (e) => {
                    this.$set(this, 'groupsFile', e.target.result);
                };
            }
        }
    }
</script>