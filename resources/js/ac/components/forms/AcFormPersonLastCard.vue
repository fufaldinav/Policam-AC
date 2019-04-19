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
                    class="form-control form-control-plaintext ac-input-card-code"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-trigger="manual"
                    :placeholder="$t('ac.last_card')"
                    :readonly="formIfDisabled"
                    @click="formClicked"
                    @blur="leaveForm"
                >
            </div>
            <div class="col-2">
                <button
                    type="button"
                    class="btn"
                    :class="[buttonIsDisabled ? 'btn-secondary' : 'btn-primary']"
                    :disabled="buttonIsDisabled"
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

        data: function () {
            return {
                countClicked: 0
            }
        },

        computed: {
            lastFreeCard() {
                return this.$store.state.cards.last;
            },

            buttonIsDisabled() {
                return this.lastFreeCard === null;
            },

            formIfDisabled() {
                return this.countClicked < 2;
            },

            cardCode: {
                get() {
                    let wiegand = '000000000000';
                    if (this.lastFreeCard !== null) {
                        wiegand = this.lastFreeCard.wiegand;
                    }
                    return ('0000000000' + parseInt(wiegand, 16)).slice(-10);
                },

                set(code) {
                    code = parseInt(code);
                    let wiegand = ('000000000000' + code.toString(16).toUpperCase()).slice(-12);
                    this.$store.commit('cards/setLast', {wiegand: wiegand});
                }
            },
        },

        mounted() {
            $('.ac-input-card-code').tooltip({title: this.$t('ac.click_again_to_edit')});
        },

        methods: {
            addCardToPerson() {
                this.$store.commit('persons/addCard', this.lastFreeCard);
                this.$store.commit('cards/clearLast');
                this.countClicked = 0;
            },

            formClicked() {
                this.countClicked++;
                if (this.countClicked === 1) {
                    $('.ac-input-card-code').tooltip('show');
                } else {
                    $('.ac-input-card-code').tooltip('hide');
                }
            },

            leaveForm() {
                this.countClicked = 0;
                if (this.cardCode === '0000000000') {
                    this.$store.commit('cards/clearLast');
                }
                $('.ac-input-card-code').tooltip('hide');
            }
        }
    }
</script>

<style scoped>

</style>
