<template>
    <div class="container-fluid my-2">
        <div class="row justify-content-around">
            <div class="col col-lg-2">
                <div class="row">
                    <div class="col-12 bg-white rounded shadow mb-2 p-0 ac-classes">
                        <ac-cp-timetable-classes></ac-cp-timetable-classes>
                    </div>
                    <div class="col-12 bg-white rounded shadow ac-calendar">
                        Календарь недель
                    </div>
                </div>
            </div>
            <div class="col col-lg-7 bg-white rounded shadow">
                <div class="row container-fluid">
                    <ac-cp-timetable-day-of-week
                        v-for="n in 6"
                        class="col-12 col-sm-6 col-lg-4"
                        :day="n"
                        :key="n"
                    >
                    </ac-cp-timetable-day-of-week>
                </div>
            </div>
            <div class="col col-lg-2 bg-white rounded shadow">
                <ac-cp-timetable-list-of-subjects></ac-cp-timetable-list-of-subjects>
            </div>
        </div>
    </div>
</template>

<script>
    import AcCpTimetableClasses from './AcCpTimetableClasses'
    import AcCpTimetableDayOfWeek from './AcCpTimetableDayOfWeek'
    import AcCpTimetableListOfSubjects from './AcCpTimetableListOfSubjects'

    export default {
        name: "AcCpTimetable",

        components: {AcCpTimetableClasses, AcCpTimetableDayOfWeek, AcCpTimetableListOfSubjects},

        computed: {
            currentDivision() {
                if (this.$store.state.divisions.selected !== null) return this.$store.state.divisions.selected

                if (this.$store.state.divisions.collection.length > 0)
                {
                    let division = this.$store.state.divisions.collection.find(div => div.type === 1)
                    this.$store.commit('divisions/setSelected', division)
                    return division
                }

                return null
            }
        },

        created() {
            let currentWeek = this.$store.getters['timetable/getCurrentWeek']
            this.$store.commit('timetable/setCurrentWeek', currentWeek)

            this.$store.commit('setPersonsShouldNotBeLoaded')
            this.$store.dispatch('loader/loadDivisions', {organizationId: 0, withPersons: 0})
        }
    }
</script>

<style scoped>
    .ac-classes, .ac-calendar {
        overflow-y: auto;
        height: calc(100vh / 4);
        max-height: calc(100vh / 4);
    }
</style>
