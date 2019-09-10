<template>
    <div class="ac-subject">{{ subject.name }}</div>
</template>

<script>
    export default {
        name: "AcCpTimetableSubject",

        props: {
            subject: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>
    .ac-subject:hover {
        cursor: grab;
    }
</style>
