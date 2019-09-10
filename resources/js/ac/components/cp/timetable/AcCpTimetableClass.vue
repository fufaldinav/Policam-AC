<template>
    <button
        type="button"
        class="list-group-item list-group-item-action"
        @click="changeCurrentDivision()"
    >
        {{ division.name }}
    </button >
</template>

<script>
    export default {
        name: "AcCpTimetableClass",

        props: {
            division: {
                type: Object,
                required: true
            }
        },

        methods: {
            changeCurrentDivision() {
                this.$store.commit('divisions/setSelected', this.division)
            }
        }
    }
</script>

<style scoped>

</style>
