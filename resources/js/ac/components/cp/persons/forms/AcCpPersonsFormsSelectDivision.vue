<template>
    <div>
        <label for="division">
            Подразделение
        </label>
        <select
            id="division"
            class="custom-select"
            v-model="selectedPerson.division"
            :disabled="selectedPerson.id === null || selectedPerson.id === 0"
        >
            <option value="null" disabled>Выбирете подразделение</option>
            <option
                v-for="division of divisions"
                :value="division.id"
                :key="division.id"
            >
                {{ division.name }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        name: "AcCpPersonsFormsSelectDivision",

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            divisions() {
                return this.$store.state.divisions.collection
            }
        }
    }
</script>

<style scoped>

</style>
