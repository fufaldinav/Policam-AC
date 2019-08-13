<template>
    <div class="container-fluid d-flex justify-content-center">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
            class="card w-100"
            :class="isInvalid"
        >
            <div
                class="card-header"
                :class="isInvalid"
            >
                Основное образование
            </div>
            <ul class="list-group list-group-flush">
                <li
                    v-for="org in organizations"
                    class="list-group-item"
                    :key="org.id"
                >
                    <ac-cp-students-organizations-basic-radio :org="org"></ac-cp-students-organizations-basic-radio>
                </li>
                <li
                    v-if="isInvalid"
                    class="list-group-item text-danger small"
                >
                    Необходимо выбрать как минимум одно образовательное учреждение
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

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            isInvalid() {
                if (this.selectedPerson.organizations.basic === null) {
                    return 'border-danger'
                }
            }
        },

        mounted() {
            this.loadOrganizations()
        },

        methods: {
            loadOrganizations() {
                window.axios.get('/users/organizations/1')
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
