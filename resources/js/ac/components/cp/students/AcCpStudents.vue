<template>
    <div class="container-fluid mt-3">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div v-else>
            <div
                v-show="selectedPerson.id === null"
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
                v-show="selectedPerson.id !== null"
                class="row justify-content-around"
            >
                <div class="d-none d-lg-block col-lg-1"></div>
                <div
                    class="mb-2 col-12 col-sm-10 mb-md-0 col-md-6 col-lg-5"
                    :class="formContainerClass"
                >
                    <ac-cp-students-forms-person>
                        <template slot="basicEducation">
                            <div class="container-fluid position-relative mb-3 p-0">
                                <ac-cp-students-organizations-basic></ac-cp-students-organizations-basic>
                            </div>
                        </template>
                        <template slot="additionalEducation">
                            <div class="container-fluid position-relative mb-3 p-0">
                                <ac-cp-students-organizations-additional></ac-cp-students-organizations-additional>
                            </div>
                        </template>
                    </ac-cp-students-forms-person>
                </div>
                <div
                    class="d-none d-md-block col-sm-10 col-md-5 col-lg-4 col-xl-3"
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
    import AcCpStudentsFormsPerson from './forms/AcCpStudentsFormsPerson'
    import AcCpStudentsOrganizationsBasic from './organizations/AcCpStudentsOrganizationsBasic'
    import AcCpStudentsOrganizationsAdditional from './organizations/AcCpStudentsOrganizationsAdditional'

    export default {
        name: "AcCpStudents",

        components: {
            AcLoading, AcCpStudentsCard, AcCpStudentsCardEmpty, AcCpStudentsFormsPerson,
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
            this.$store.commit('setUserRole', 4)
            this.$store.dispatch('loader/loadPersons')
            this.$store.dispatch('loader/loadReferralCodes')
        },
    }
</script>

<style scoped>

</style>
