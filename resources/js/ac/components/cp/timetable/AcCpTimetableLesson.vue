<template>
    <div class="list-group-item" :class="classObject">{{ content }}</div>
</template>

<script>
    export default {
        name: "AcCpTimetableLesson",

        data() {
           return {
               assignedLesson: null
           }
        },

        props: {
            lesson: {
                type: [Object, Number],
                required: true
            }
        },

        computed: {
            content() {
                if (typeof this.lesson === 'number') {
                    return this.lessonStart + ' - ' + this.lessonEnd
                }

                return this.$store.state.timetable.subjects.find(subject => subject.id === this.lesson.subject).name
            },

            classObject() {
                if (typeof this.lesson !== 'number') {
                    return {'ac-lesson': true}
                }
            },

            lessonStart() {
                return this.$store.state.timetable.hoursOfLessons[this.lesson].start
            },

            lessonEnd() {
                return this.$store.state.timetable.hoursOfLessons[this.lesson].end
            }
        }
    }
</script>

<style scoped>
    .ac-lesson:hover {
        cursor: grab;
    }
</style>
