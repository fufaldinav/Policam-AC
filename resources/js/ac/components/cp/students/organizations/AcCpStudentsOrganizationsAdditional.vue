<template>
    <div class="container-fluid d-flex justify-content-center">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
            class="card w-100"
        >
            <div class="card-header">
                {{ $t('Основное образование') }}
            </div>
            <ul class="list-group list-group-flush">
                <li
                    v-for="org in organizations"
                    class="list-group-item"
                    :key="org.id"
                >
                    <ac-cp-students-organizations-additional-checkbox
                        :org="org"></ac-cp-students-organizations-additional-checkbox>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import AcLoading from '../../../AcLoading'
    import AcCpStudentsOrganizationsAdditionalCheckbox from './AcCpStudentsOrganizationsAdditionalCheckbox'

    export default {
        name: "AcCpStudentsOrganizationsAdditional",

        components: {
            AcLoading, AcCpStudentsOrganizationsAdditionalCheckbox
        },

        data() {
            return {
                loading: true,
                organizations: []
            }
        },

        mounted() {
            this.loadOrganizations()
        },

        methods: {
            loadOrganizations() {
                window.axios.get('/users/organizations/2')
                    .then(response => {
                        this.organizations = []
                        for (let org of response.data) {
                            this.organizations.push(org)
                        }
                        this.loading = false
                    })
                    .catch(error => {
                        if (this.$store.state.debug) console.log(error)
                        setTimeout(this.loadOrganizations, 2000) //TODO перезапуск при ошибке
                    })
            }
        }
    }
</script>

<style scoped>

</style>
