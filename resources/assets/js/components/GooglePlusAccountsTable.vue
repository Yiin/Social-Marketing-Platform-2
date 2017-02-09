<template>
    <div>
        <div class="table-responsive">

            <table class="table">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(account, index) in accounts">
                    <td class="text-center">{{ index + 1 }}</td>
                    <td>{{ account.username }}</td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    export default {
        props: ['accounts']
    }
</script>