<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <template v-if="done">
                    <div class="header">
                        <h4 class="title">Posting was successfully queued!</h4>
                        <p class="category">
                            <a :href="`https://smp.roislope.com/facebook/stats/${queue_id}`" target="_blank">
                                https://smp.roislope.com/facebook/stats/{{ queue_id }}
                            </a>
                        </p>
                    </div>

                    <div class="content">
                        <p>
                            To see the progress, visit this url:
                            <a :href="`https://smp.roislope.com/facebook/stats/${queue_id}`" target="_blank">
                                https://smp.roislope.com/facebook/stats/{{ queue_id }}
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
                        <h4 class="title">Mass Post Settings</h4>
                    </div>
                    <div class="content">
                        <form @submit.prevent="post">
                            <div class="form-group" :class="{ 'has-error': errors.client_id }">
                                <label>Client</label>
                                <select data-title="Select Client We Post For" data-style="btn-default btn-block"
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
                                <label>Post Template</label>
                                <select data-title="Select Post Template" data-style="btn-default btn-block"
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
                                <label>Delay between posts (in seconds)</label>
                                <input v-model="delay" placeholder="Delay between posts (in seconds)" type="number"
                                       min="0" class="form-control">
                                <template v-if="errors.delay">
                                    <label v-for="errorMessage in errors.delay" class="error">{{ errorMessage }}</label>
                                </template>
                            </div>

                            <button type="submit" class="btn btn-fill btn-primary" :disabled="!selectedGroupsTotal()">{{
                                selectedGroupsTotal() ? 'Post to selected groups' : 'Please select at least one group'
                                }}
                            </button>
                        </form>
                    </div>
                </template>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Select groups</h4>
                </div>
                <div class="content">
                    <template v-for="(account, accountIndex) in accounts">
                        <div class="heading">
                            <h5 class="title">
                                {{ groupsHeadingText(account) }}
                                <button class="btn btn-xs" :data-target="`#account-groups-${accountIndex}`"
                                        data-toggle="collapse">
                                    Show / Hide
                                </button>
                                <button class="btn btn-xs" @click="selectAllGroups(account)">
                                    Select All
                                </button>
                                <button class="btn btn-xs" @click="resetSelection(account)">
                                    Reset Selection
                                </button>
                            </h5>
                        </div>
                        <div :id="`account-groups-${accountIndex}`" class="table-responsive collapse">

                            <table class="table groups">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Group Name</th>
                                    <th>Members</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(group, index) in account.groups"
                                    :class="{ selected: group.selected }"
                                    @click="selectGroup(group)"
                                >
                                    <td class="text-center">{{ index + 1 }}</td>
                                    <td class="highlight">{{ group.name }}</td>
                                    <td>{{ group.members }}</td>
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
    </div>
</template>

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
                accounts: [{
                    groups: [{}]
                }],
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
                    queue: []
                };

                console.log(this.accounts);

                this.accounts.forEach(account => {
                    account.groups.filter(group => group.selected
                    ).forEach(group => {
                        data.queue.push({
                            account_id: account.id,
                            groupId: group.groupId
                        });
                    })
                    ;
                });

                this.$http.post(`/api/facebook/post`, data).then(response => {
                    this.done = true;
                    this.queue_id = response.body;
                    this.resetSelection();
                }).catch(response => {
                    this.errors = response.body;
                });
            },
            selectGroup(group) {
                this.$set(group, 'selected', !group.selected);
            },
            selectAllGroups(account) {
                if (!account.groups) {
                    return;
                }

                account.groups.forEach(group => {
                    this.$set(group, 'selected', true);
                });
            },
            resetSelection(account) {
                let reset = account => {
                    if (!account.groups) {
                        return;
                    }

                    account.groups.forEach(group => {
                        this.$set(group, 'selected', false);
                    });
                };

                if (account) {
                    reset(account);
                }
                else {
                    this.accounts.forEach(account => {
                        reset(account);
                    });
                }
            },
            selectedGroups(account) {
                return account.groups.filter(group => group.selected).length;
            },
            selectedGroupsTotal() {
                let sum = 0;

                this.accounts.forEach(account => {
                    if (account.groups) {
                        sum += account.groups.filter(group => group.selected).length;
                    }
                });

                return sum;
            },
            groupsHeadingText(account) {
                let text = `${account.name}`;

                let selectedGroups = this.selectedGroups(account);

                if (selectedGroups) {
                    text += ` (selected ${selectedGroups} out of ${account.groups.length})`;
                }
                else {
                    text += ` (${account.groups.length})`;
                }

                return text;
            }
        },
        mounted() {
            this.accounts = JSON.parse(this.accountsjson);
            this.clients = JSON.parse(this.clientsjson);
            this.templates = JSON.parse(this.templatesjson);

            setTimeout($('[data-toggle="collapse"]').collapse);
        }
    }
</script>