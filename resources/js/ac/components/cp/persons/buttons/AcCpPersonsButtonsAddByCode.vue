<template>
    <button
        type="button"
        class="list-group-item list-group-item-warning"
        @click="addByCode"
    >
        Добавить по штрих-коду
    </button>
</template>

<script>
    export default {
        name: "AcCpPersonsButtonsAddByCode",

        methods: {
            addByCode() {
                this.$store.commit('persons/clearSelected');
                this.$store.commit('persons/setSearchByCode');
                this.$store.commit('cp/showLeftMenu');
            }
        }
    }
</script>

<style scoped>

</style>
