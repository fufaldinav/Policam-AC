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
                    <ac-cp-students-organizations-basic-radio :org="org"></ac-cp-students-organizations-basic-radio>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import AcLoading from '../../../AcLoading'
    import AcCpStudentsOrganizationsBasicRadio from './AcCpStudentsOrganizationsBasicRadio'

    export default {
        name: "AcCpStudentsOrganizationsBasic",

        components: {
            AcLoading, AcCpStudentsOrganizationsBasicRadio
        },

        data() {
            return {
                loading: true,
                organizations: []
            }
        },

        mounted() {
            window.axios.get('/users/organizations/1')
                .then(response => {
                    for (let org of response.data) {
                        this.organizations.push(org)
                    }
                    this.loading = false
                })
                .catch(error => {
                    if (this.$store.state.debug) console.log(error)
                })
        }
    }
</script>

<style scoped>

</style>
