<template>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="header">
                    <h4 class="title">Select accounts you want to tweet from</h4>
                </div>
                <div class="content">
                    <template>
                        <div class="heading">
                            <h5 class="title">
                                {{ groupsHeadingText() }}
                                <button class="btn btn-xs" @click="selectAllAccounts()">
                                    Select All
                                </button>
                                <button class="btn btn-xs" @click="resetSelection()">
                                    Reset Selection
                                </button>
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table groups">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Account Name</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(account, index) in accounts"
                                    :class="{ selected: account.selected }"
                                    @click="selectAccount(account)"
                                >
                                    <td class="text-center">{{ index + 1 }}</td>
                                    <td class="highlight">{{ account.name }}</td>
                                    <td class="text-right">
                                        <label class="explanation">CLICK TO SELECT</label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <template v-if="done">
                    <div class="header">
                        <h4 class="title">Posting was successfully queued!</h4>
                        <p class="category">
                            <a :href="`https://smp.roislope.com/twitter/stats/${queue_id}`" target="_blank">
                                https://smp.roislope.com/twitter/stats/{{ queue_id }}
                            </a>
                        </p>
                    </div>

                    <div class="content">
                        <p>
                            To see the progress, visit this url:
                            <a :href="`https://smp.roislope.com/twitter/stats/${queue_id}`" target="_blank">
                                https://smp.roislope.com/twitter/stats/{{ queue_id }}
                            </a>
                        </p>
                        <p>
                            A link above will be sent to client email
                            ({{ clients.filter(client => client.id === client_id)[0].email }})
                            once posting is done.
                        </p>
                        <p>
                            Usually posting is completed in up to 15 minutes, but that
                            can vary depending on number of posts.
                        </p>
                        <button @click="done = false" class="btn">Alright</button>
                    </div>
                </template>
                <template v-else>
                    <div class="header">
                        <h4 class="title">Mass Tweet Settings</h4>
                    </div>
                    <div class="content">
                        <form @submit.prevent="post">
                            <div class="form-group" :class="{ 'has-error': errors.client_id }">
                                <label>Client</label>
                                <select data-title="Select Client We Tweet For" data-style="btn-default btn-block"
                                        data-menu-style="dropdown-blue" class="form-control selectpicker"
                                        v-model="client_id">
                                    <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
                                </select>
                                <template v-if="errors.client_id">
                                    <label v-for="errorMessage in errors.client_id" class="error">{{ errorMessage
                                        }}</label>
                                </template>
                            </div>
                            <div class="form-group" :class="{ 'has-error': errors.template_id }">
                                <label>Tweet Template</label>
                                <select data-title="Select Tweet Template" data-style="btn-default btn-block"
                                        data-menu-style="dropdown-blue" class="form-control selectpicker"
                                        v-model="template_id">
                                    <option v-for="template in templates" :value="template.id">{{ template.name }}
                                    </option>
                                </select>
                                <template v-if="errors.template_id">
                                    <label v-for="errorMessage in errors.template_id" class="error">{{ errorMessage
                                        }}</label>
                                </template>
                            </div>
                            <div class="form-group" :class="{ 'has-error': errors.delay }">
                                <label>Delay between tweets (in seconds)</label>
                                <input v-model="delay" placeholder="Delay between posts (in seconds)" type="number"
                                       min="0" class="form-control">
                                <template v-if="errors.delay">
                                    <label v-for="errorMessage in errors.delay" class="error">{{ errorMessage }}</label>
                                </template>
                            </div>

                            <button type="submit" class="btn btn-fill btn-primary" :disabled="!selectedAccountsTotal()">
                                {{ selectedAccountsTotal() ? 'Post from selected accounts' : 'Please select at least one account' }}
                            </button>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'clientsjson', 'templatesjson', 'accountsjson'
        ],
        data() {
            return {
                done: false,
                queue_id: null,
                errors: [],
                accounts: [{}],
                clients: [],
                templates: [],
                delay: 1,
                client_id: undefined,
                template_id: undefined
            }
        },
        methods: {
            post() {
                let data = {
                    template_id: this.template_id,
                    client_id: this.client_id,
                    delay: this.delay,
                    queue: this.accounts.filter(account => account.selected)
                };

                this.$http.post(`/api/twitter/post`, data).then(response => {
                    this.done = true;
                    this.queue_id = response.body;
                    this.resetSelection();
                }).catch(response => {
                    this.errors = response.body;
                });
            },
            selectAccount(account) {
                this.$set(account, 'selected', !account.selected);
            },
            selectAllAccounts() {
                this.accounts.forEach(account => this.$set(account, 'selected', true));
            },
            resetSelection() {
                this.accounts.forEach(account => this.$set(account, 'selected', false));
            },
            selectedaccounts() {
                return this.accounts.filter(account => account.selected).length;
            },
            selectedAccountsTotal() {
                return this.accounts.filter(account => account.selected).length;
            },
            groupsHeadingText() {
                let selectedaccounts = this.selectedaccounts();

                if (selectedaccounts) {
                    return `Selected ${selectedaccounts} accounts out of ${this.accounts.length})`;
                }
                else {
                    return `Please select at least one account.`;
                }
            }
        },
        mounted() {
            this.accounts = JSON.parse(this.accountsjson);
            this.clients = JSON.parse(this.clientsjson);
            this.templates = JSON.parse(this.templatesjson);
        }
    }

</script>

<style>
    .heading {
        margin: 20px;
    }

    .heading > .title > button {
        margin-left: 15px;
    }

    table.groups tbody tr {
        cursor: pointer;
    }

    table.groups tbody tr:hover {
        background: rgba(74, 119, 234, 0.37);
    }

    table.groups tbody tr.selected {
        background: #4a77ea;
        color: white;
    }

    table.groups tbody tr.selected > td.highlight {
        font-weight: bold;
    }

    .explanation {
        font-weight: normal;
        font-size: 12px;
    }
</style>