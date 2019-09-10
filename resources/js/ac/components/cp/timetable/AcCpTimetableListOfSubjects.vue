<template>
    <draggable
        v-model="subjects"
        :group="{ name: 'lessons', pull: 'clone', put: false }"
        :sortable="false"
    >
        <transition-group>
            <ac-cp-timetable-subject
                v-for="subject in subjects"
                :key="subject.id"
                :subject="subject"
            >
            </ac-cp-timetable-subject>
        </transition-group>
    </draggable>
</template>

<script>
    import AcCpTimetableSubject from './AcCpTimetableSubject'
    import draggable from 'vuedraggable'

    export default {
        name: "AcCpTimetableListOfSubjects",

        components: {AcCpTimetableSubject, draggable},

        computed: {
            subjects: {
                get() {
                    return this.$store.state.timetable.subjects
                },

                set() {
                    //do nothing
                }
            }
        }
    }
</script>

<style scoped>

</style>
