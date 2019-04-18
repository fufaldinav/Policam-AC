<template>
    <div v-if="selectedPersonCards.length > 0" class="dropdown">
        <label for="cardsMenu">
            {{ $tc('ac.cards', selectedPersonCards.length) }}
        </label>
        <div v-for="card in selectedPersonCards" id="cardsMenu" class="form-row mb-2">
            <div class="col-10">
                <input
                    v-model="card.wiegand"
                    type="text"
                    class="form-control form-control-plaintext"
                    :placeholder="$t('ac.card')"
                    disabled
                >
            </div>
            <div class="col-2">
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="detachCard(card)"
                >
                    X
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcFormCards",
        computed: {
            selectedPersonCards() {
                return this.$store.state.persons.selected.cards;
            }
        },
        methods: {
            detachCard(card) {
                this.$store.commit('persons/removeCard', card);
            }
        }
    }
</script>

<style scoped>

</style>
