<template>
    <div>
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
            class="list-group list-group-flush"
        >
            <ac-cp-timetable-class
                v-for="division in divisions"
                v-if="division.type === 1"
                :key="division.id"
                :division="division"
            >
            </ac-cp-timetable-class >
        </div>

    </div>
</template>

<script>
    import AcLoading from '../../AcLoading'
    import AcCpTimetableClass from './AcCpTimetableClass'

    export default {
        name: "AcCpTimetableClasses",

        components: {AcLoading, AcCpTimetableClass},

        computed: {
            loading() {
                return this.$store.state.loader.loading
            },

            divisions() {
                return this.$store.state.divisions.collection
            }
        }
    }
</script>

<style scoped>

</style>
