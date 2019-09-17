<template>
    <div class="form-row">
        <div class="form-group col-12">
            <label for="rc">
                Карта/браслет
            </label>
            <div class="row">
                <div class="col-12 col-md-5 pr-md-1 mb-2">
                    <input
                        v-if="! codeManualInput"
                        id="rc"
                        :value="RCCode(selectedPersonRC)"
                        type="text"
                        class="form-control"
                        placeholder="Номер карты"
                        disabled
                    >
                    <input
                        v-else
                        id="rcToCheck"
                        v-model="codeToCheck"
                        type="text"
                        class="form-control"
                        :class="{'is-invalid': codeIsInvalid}"
                        placeholder="Номер с карты/браслета"
                    >
                    <div class="invalid-feedback text-center">
                        Код занят или не найден
                    </div>
                </div>
                <div class="col-12 col-md-7 pl-md-0">
                    <button
                        v-if="RCActivated(selectedPersonRC) === 0 && ! codeManualInput"
                        type="button"
                        class="btn btn-success"
                    >
                        Подтвердить
                    </button>
                    <button
                        v-if="! codeManualInput"
                        type="button"
                        class="btn btn-primary"
                        @click="activateManualInput"
                    >
                        Редактировать
                    </button>
                    <button
                        v-if="codeManualInput && codeReceived === null"
                        type="button"
                        class="btn btn-primary"
                        :disabled="checkButtonDisabled"
                        @click="checkRCCode"
                    >
                        <template v-if="codeChecking">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Проверка...
                        </template>
                        <template v-else>
                            Проверить код
                        </template>
                    </button>
                    <button
                        v-if="codeManualInput && codeReceived !== null"
                        type="button"
                        class="btn btn-success"
                        @click="saveCode"
                    >
                        Сохранить
                    </button>
                    <button
                        v-if="codeManualInput"
                        type="button"
                        class="btn btn-secondary mr-2"
                        @click="deactivateManualInput"
                    >
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {ReferralCode} from "../../../../classes";

    export default {
        name: "AcCpPersonsFormsPersonRC",

        data() {
            return {
                codeManualInput: false,
                codeManuallyEntered: '',
                codeChecking: false,
                codeReceived: null,
                codeIsInvalid: false,
                oldCode: 0
            }
        },

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            selectedPersonRC() {
                let rcId = this.selectedPerson.referral_code_id
                if (rcId === null) return
                return this.$store.getters['rc/getById'](rcId)
            },

            codeToCheck: {
                get() {
                    return this.codeManuallyEntered
                },

                set(code) {
                    this.codeReceived = null
                    this.codeIsInvalid = false
                    this.codeManualInput = true
                    this.codeManuallyEntered = code
                }
            },

            checkButtonDisabled() {
                return this.codeManuallyEntered === '' || this.codeChecking
            }
        },

        methods: {
            RCCode(rc) {
                if (rc !== undefined) return rc.code
            },

            RCActivated(rc) {
                if (rc !== undefined) return rc.activated
            },

            checkRCCode() {
                this.codeChecking = true
                let rc = this.$store.getters['rc/getByCode'](this.codeManuallyEntered)
                if (rc === undefined) {
                    this.$store.dispatch('rc/getReferral', this.codeManuallyEntered)
                        .then(response => {
                            if (response === 0) {
                                this.codeIsInvalid = true
                            } else {
                                this.codeReceived = new ReferralCode(response)
                            }
                        })
                        .catch(error => {
                            if (this.$store.state.debug) console.log(error)
                        })
                        .finally(() => {
                            this.codeChecking = false
                        })
                } else if (rc.activated === 1) {
                    this.codeIsInvalid = true
                    this.codeChecking = false
                } else {
                    this.codeReceived = rc
                }
            },

            saveCode() {
                this.$store.commit('rc/add', this.codeReceived)
                this.selectedPerson.referral_code_id = this.codeReceived.id
                this.codeManuallyEntered = ''
                this.codeReceived = null
                this.codeManualInput = false
            },

            activateManualInput() {
                this.oldCode = this.selectedPerson.referral_code_id
                this.selectedPerson.referral_code_id = 0
                this.codeManualInput = true
            },

            deactivateManualInput() {
                this.selectedPerson.referral_code_id = this.oldCode
                this.oldCode = 0
                this.codeManuallyEntered = ''
                this.codeIsInvalid = false
                this.codeManualInput = false
            },
        }
    }
</script>

<style scoped>

</style>
