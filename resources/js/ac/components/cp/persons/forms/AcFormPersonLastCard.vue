<template>
    <div>
        <div class="form-row">
            <label for="freeCards">
                {{ $t('Последняя карта') }}
            </label>
        </div>
        <div class="form-row">
            <div class="col-9 col-sm-10">
                <input
                    id="freeCards"
                    v-model="cardCode"
                    type="text"
                    class="form-control form-control-plaintext ac-input-card-code"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-trigger="manual"
                    :readonly="manualInput === false"
                    @click="formClicked"
                    @blur="leaveForm"
                >
            </div>
            <div class="col-3 col-sm-2">
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
                return this.$store.state.cards.last
            },

            buttonIsDisabled() {
                return this.lastFreeCard === null
            },

            manualInput: {
                get() {
                    return this.$store.state.cards.manualInput
                },

                set(status) {
                    if (status) {
                        this.$store.commit('cards/setManualInput')
                    } else {
                        this.$store.commit('cards/setManualInput', false)
                        this.countClicked = 0
                    }
                }
            },

            cardCode: {
                get() {
                    let wiegand = '000000000000'
                    if (this.lastFreeCard !== null) {
                        wiegand = this.lastFreeCard.wiegand
                    }
                    return ('0000000000' + parseInt(wiegand, 16)).slice(-10)
                },

                set(code) {
                    code = parseInt(code)
                    let wiegand = ('000000000000' + code.toString(16).toUpperCase()).slice(-12)
                    this.$store.commit('cards/setLast', {wiegand: wiegand})
                }
            },
        },

        mounted() {
            $('.ac-input-card-code').tooltip({title: this.$t('Нажмите еще раз для редактирования')})
        },

        methods: {
            addCardToPerson() {
                this.$store.commit('persons/addCard', this.lastFreeCard)
                this.$store.commit('cards/clearLast')
                this.manualInput = false
            },

            formClicked() {
                this.countClicked++
                if (this.countClicked === 1) {
                    $('.ac-input-card-code').tooltip('show')
                } else if (this.countClicked === 2) {
                    this.manualInput = true
                } else {
                    $('.ac-input-card-code').tooltip('hide')
                }
            },

            leaveForm() {
                this.manualInput = false
                if (this.cardCode === '0000000000') {
                    this.$store.commit('cards/clearLast')
                }
                $('.ac-input-card-code').tooltip('hide')
            }
        }
    }
</script>

<style scoped>

</style>
