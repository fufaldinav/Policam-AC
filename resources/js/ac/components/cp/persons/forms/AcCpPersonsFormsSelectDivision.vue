<template>
    <div>
        <label for="division">
            {{ $t('Подразделение') }}
        </label>
        <select
            id="division"
            class="custom-select"
            v-model="selectedPersonDivision"
            :disabled="selectedPerson.id === null || selectedPerson.id === 0"
        >
            <option value="null" disabled>{{ $t('Выбирете подразделение') }}</option>
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
            },

            selectedPersonDivision: {
                get() {
                    if (this.selectedPerson.divisions.length === 0) return null
                    return this.selectedPerson.divisions[0]
                },

                set(newDivision) {
                    const oldDivision = this.selectedPerson.divisions[0]
                    if (this.selectedPerson.divisionsToDelete.length === 0) {
                        this.selectedPerson.divisionsToDelete.push(oldDivision)
                    }
                    let index = this.selectedPerson.divisionsToDelete.indexOf(newDivision)
                    if (index > -1) {
                        this.selectedPerson.divisionsToDelete.splice(index, 1)
                    }
                    this.selectedPerson.divisions.shift()
                    this.selectedPerson.divisions.unshift(newDivision)
                }
            },
        }
    }
</script>

<style scoped>

</style>
