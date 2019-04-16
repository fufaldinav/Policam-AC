<template>
    <button type="button" class="list-group-item list-group-item-action" @click="selectPerson">
        {{ person.f }} {{ person.i }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonPerson",
        props: {
            person: Object
        },
        methods: {
            selectPerson: function () {
                this.$store.commit('persons/setSelected', this.person);
            }
        }
    }
</script>

<style scoped>

</style>
