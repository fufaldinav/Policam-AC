<template>
    <div v-if="selectedPersonCards.length > 0">
        <label for="cards">
            {{ $tc('ac.cards', selectedPersonCards.length) }}
        </label>
        <input
            id="cards"
            v-model="selectedPersonCards"
            type="text"
            class="form-control"
            :placeholder="$tc('ac.cards', selectedPersonCards.length)"
        >
    </div>
</template>

<script>
    export default {
        name: "AcFormCards",
        computed: {
            selectedPersonCards() {
                return this.$store.state.persons.selected.cards;
            }
        }
    }
</script>

<style scoped>

</style>
