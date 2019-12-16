<template>
    <button
        type="button"
        class="list-group-item list-group-item-info"
        @click="cancel"
    >
        Назад
    </button>
</template>

<script>
    export default {
        name: "AcCpPersonsButtonsCancelSearchByCode",

        methods: {
            cancel() {
                this.$store.commit('persons/setSearchByCode', false);
            }
        }
    }
</script>

<style scoped>

</style>
