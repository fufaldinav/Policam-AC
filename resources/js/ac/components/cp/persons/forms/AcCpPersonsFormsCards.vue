<template>
    <div
        v-if="selectedPersonCards.length > 0"
        class="dropdown"
    >
        <label for="cardsMenu">
            Карты
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
                <ac-buttons-remove-card :removable-card="card">
                    &times;
                </ac-buttons-remove-card>
            </div>
        </div>
    </div>
</template>

<script>
    import AcButtonsRemoveCard from '../../../buttons/AcButtonsRemoveCard'

    export default {
        name: "AcCpPersonsFormsCards",

        components: {AcButtonsRemoveCard},

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
