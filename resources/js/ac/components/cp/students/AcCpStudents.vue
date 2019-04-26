<template>
    <div class="container-fluid mt-3">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
        >
            <div
                v-if="selectedPerson.id === null"
                class="row"
            >
                <div class="col"></div>
                <div class="col12 col-lg-10 col-xl-8">
                    <div class="row">
                        <ac-cp-students-card
                            v-for="person in persons"
                            :key="person.id"
                            :person="person"
                        >
                        </ac-cp-students-card>
                        <ac-cp-students-card-empty></ac-cp-students-card-empty>
                    </div>
                </div>
                <div class="col"></div>
            </div>
            <div
                v-else
                class="row justify-content-around"
            >
                <div class="d-none d-lg-block col-lg-1"></div>
                <div
                    class="mb-2 col-12 col-sm-10 mb-md-0 col-md-6 col-lg-5"
                    :class="formContainerClass"
                >
                    <ac-form-person></ac-form-person>
                </div>
                <div
                    class="col-12 col-sm-10 col-md-5 col-lg-4"
                >
                    <div class="row h-100">
                        <div
                            class="col-12 mb-2"
                            :class="formContainerClass"
                        >
                            <ac-cp-students-organizations-basic></ac-cp-students-organizations-basic>
                        </div>
                        <div
                            class="col-12 mb-2"
                            :class="formContainerClass"
                        >
                            <ac-cp-students-organizations-additional></ac-cp-students-organizations-additional>
                        </div>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-1"></div>
            </div>

        </div>
    </div>
</template>

<script>
    import AcLoading from '../../AcLoading'
    import AcCpStudentsCard from './AcCpStudentsCard'
    import AcCpStudentsCardEmpty from './AcCpStudentsCardEmpty'
    import AcFormPerson from '../persons/forms/AcFormPerson'
    import AcCpStudentsOrganizationsBasic from './organizations/AcCpStudentsOrganizationsBasic'
    import AcCpStudentsOrganizationsAdditional from './organizations/AcCpStudentsOrganizationsAdditional'

    export default {
        name: "AcCpStudents",

        components: {
            AcLoading, AcCpStudentsCard, AcCpStudentsCardEmpty, AcFormPerson,
            AcCpStudentsOrganizationsBasic, AcCpStudentsOrganizationsAdditional
        },

        computed: {
            loading() {
                return this.$store.state.loader.loading
            },

            breakpoint() {
                return this.$store.state.bp.current
            },

            persons() {
                return this.$store.state.persons.collection
            },

            selectedPerson() {
                return this.$store.state.persons.selected
            },

            formContainerClass() {
                return {
                    'p-4 rounded shadow-sm bg-white': this.breakpoint !== 'xs'
                }
            }
        },

        created() {
            this.$store.dispatch('loader/loadPersons')

            window.axios.get('/users/referral_codes')
                .then(response => {
                    let rc = response.data
                    if (rc.length === 0) return
                    let code = parseInt(rc[0].card)
                    let wiegand = ('000000000000' + code.toString(16).toUpperCase()).slice(-12)
                    this.$store.commit('cards/setLast', {wiegand: wiegand})
                })
                .catch(error => {
                    if (this.$store.state.debug) console.log(error)
                })
        },
    }
</script>

<style scoped>

</style>
