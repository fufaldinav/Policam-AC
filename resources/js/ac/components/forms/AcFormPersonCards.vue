<template>
    <div
        v-if="selectedPersonCards.length > 0"
        class="dropdown"
    >
        <label for="cardsMenu">
            {{ $tc('ac.cards', selectedPersonCards.length) }}
        </label>
        <div
            v-for="card in selectedPersonCards"
            id="cardsMenu"
            class="form-row mb-2"
        >
            <div class="col-9 col-sm-10">
                <input
                    type="text"
                    class="form-control form-control-plaintext"
                    :value="cardCode(card.wiegand)"
                    disabled
                >
            </div>
            <div class="col-3 col-sm-2">
                <ac-button-remove-card :removable-card="card">
                    &times;
                </ac-button-remove-card>
            </div>
        </div>
    </div>
</template>

<script>
    import AcButtonRemoveCard from '../buttons/AcButtonRemoveCard'

    export default {
        name: "AcFormCards",

        components: {AcButtonRemoveCard},

        computed: {
            selectedPersonCards() {
                return this.$store.state.persons.selected.cards
            }
        },

        methods: {
            cardCode(wiegand) {
                return ('0000000000' + parseInt(wiegand, 16)).slice(-10)
            }
        }
    }
</script>

<style scoped>

</style>
