<template>
    <div class="container-fluid mt-3">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
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
    </div>
</template>

<script>
    import {Person} from '../../../classes'
    import AcLoading from '../../AcLoading'
    import AcCpStudentsCard from './AcCpStudentsCard'
    import AcCpStudentsCardEmpty from './AcCpStudentsCardEmpty'

    export default {
        name: "AcCpStudents",

        components: {AcLoading, AcCpStudentsCard, AcCpStudentsCardEmpty},

        computed: {
            loading() {
                return this.$store.state.loader.loading
            },

            persons() {
                return this.$store.state.persons.collection
            },

            emptyPerson() {
                return new Person({})
            }
        },

        created() {
            this.$store.dispatch('loader/loadPersons')
        },
    }
</script>

<style scoped>

</style>
