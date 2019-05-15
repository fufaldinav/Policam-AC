<template>
    <div class="col-12 col-sm-6 col-lg-4 my-2">
        <div class="card">
            <div class="card-header">
                {{ nameOfDay }}
            </div>
            <div class="list-group list-group-flush">
                <draggable
                    v-model="lessons"
                    :group="{ name: 'lessons', pull: 'clone', put: false }"
                >
                    <ac-cp-timetable-lesson
                        v-for="(lesson, key) in lessons"
                        :lesson="lesson"
                        :key="key"
                    >
                    </ac-cp-timetable-lesson>
                </draggable>
            </div>
        </div>
    </div>
</template>

<script>
    import AcCpTimetableLesson from './AcCpTimetableLesson'
    import draggable from 'vuedraggable'

    export default {
        name: "AcCpTimetableDayOfWeek",

        components: {AcCpTimetableLesson, draggable},

        props: {
            day: {
                type: Number,
                required: true
            }
        },

        computed: {
            nameOfDay() {
                return this.$store.state.timetable.days[this.day]
            },

            currentWeek() {
                return this.$store.state.timetable.currentWeek
            },

            lessons: {
                get() {
                    let currentDayTimetable = this.$store.state.timetable.currentWeekTimetable.filter(entry => (entry.week === this.currentWeek && entry.day === this.day))
                    let lessons = []

                    for (let hour = 1; hour <= 7; hour++) {
                        let currentHourLesson = currentDayTimetable.find(entry => entry.hour === hour)
                        if (currentHourLesson === undefined) {
                            lessons.push(hour)
                        } else {
                            lessons.push(currentHourLesson)
                        }
                    }

                    console.log(lessons)

                    return lessons
                },

                set() {

                }
            }
        }
    }
</script>

<style scoped>

</style>
