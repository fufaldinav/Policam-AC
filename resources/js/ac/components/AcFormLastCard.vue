<template>
    <div>
        <div class="form-row">
            <label for="freeCards">
                {{ $t('ac.last_card') }}
            </label>
        </div>
        <div class="form-row">
            <div class="col-10">
                <input
                    id="freeCards"
                    v-model="cardCode"
                    type="text"
                    class="form-control form-control-plaintext"
                    :placeholder="$t('ac.last_card')"
                    disabled
                >
            </div>
            <div class="col-2">
                <button
                    type="button"
                    class="btn"
                    :class="[noLastFreeCard ? 'btn-secondary' : 'btn-primary']"
                    :disabled="noLastFreeCard"
                    @click="addCardToPerson"
                >
                    +
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcFormLastCard",
        computed: {
            lastFreeCard() {
                return this.$store.state.cards.last;
            },
            noLastFreeCard() {
                return this.lastFreeCard.wiegand === '000000000000';
            },
            cardCode() {
                let wiegand = this.lastFreeCard.wiegand;
                return ('0000000000' + parseInt(wiegand, 16)).slice(-10);
            }
        },
        methods: {
            addCardToPerson() {
                this.$store.commit('persons/addCard', this.lastFreeCard);
                this.$store.commit('cards/setLast', {id: 0, wiegand: '000000000000'});
            }
        }
    }
</script>

<style scoped>

</style>
