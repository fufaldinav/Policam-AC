<template>
    <div class="form-row">
        <div class="form-group col-12">
            <label for="rc">
                Карта/браслет
            </label>
            <div class="row">
                <div class="col-12 col-md-5 pr-md-1 mb-2">
                    <input
                        v-if="selectedPerson.referral_code.id !== null && ! codeManualInput"
                        id="rc"
                        :value="selectedPerson.referral_code.code"
                        type="text"
                        class="form-control"
                        :class="rcActivatedClass"
                        placeholder="Номер карты"
                        disabled
                    >
                    <div class="valid-feedback text-center">
                        Код активирован!
                    </div>
                    <input
                        v-if="selectedPerson.referral_code.id === null || codeManualInput"
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
                        v-if="selectedPerson.referral_code.id !== null && selectedPerson.referral_code.activated === 0 && ! codeManualInput"
                        type="button"
                        class="btn btn-success"
                        @click="activateRC"
                        :disabled="codeActivation"
                    >
                        <template v-if="codeActivation">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Проверка...
                        </template>
                        <template v-else>
                            Подтвердить
                        </template>
                    </button>
                    <button
                        v-if="selectedPerson.referral_code.id !== null && ! codeManualInput"
                        type="button"
                        class="btn btn-primary"
                        :disabled="formDisabled"
                        @click="activateManualInput"
                    >
                        Редактировать
                    </button>
                    <button
                        v-if="(selectedPerson.referral_code.id === null && codeReceived === null ) || (codeManualInput && codeReceived === null)"
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
                codeActivation: false,
                codeActivationFailed: false,
                codeManualInput: false,
                codeManuallyEntered: '',
                codeChecking: false,
                codeReceived: null,
                codeIsInvalid: false,
                oldCode: new ReferralCode()
            }
        },

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            rcActivatedClass() {
              return {
                  'is-valid': this.selectedPerson.referral_code.activated === 1,
                  'is-invalid': this.codeActivationFailed
              }
            },

            codeToCheck: {
                get() {
                    return this.codeManuallyEntered
                },

                set(code) {
                    this.codeActivationFailed = false
                    this.codeReceived = null
                    this.codeIsInvalid = false
                    this.codeManualInput = true
                    this.codeManuallyEntered = code
                }
            },

            checkButtonDisabled() {
                return this.codeManuallyEntered === '' || this.codeChecking
            },

            selectedOrganization() {
                return this.$store.state.organizations.selected
            },

            formDisabled() {
                return this.selectedPerson.organization_id !== this.selectedOrganization.id && this.selectedPerson.id !== 0 && ! this.codeActivationFailed
            }
        },

        methods: {
            activateRC() {
                this.codeActivation = true
                this.$store.dispatch('rc/activateReferral', this.selectedPerson.referral_code.code)
                    .then(response => {
                        if (response === 0 || response.activated === 1 || (response.organization_id !== this.selectedOrganization.id && response.organization_id !== 0)) {
                            this.codeActivationFailed = true
                        } else {
                            this.selectedPerson.referral_code.activated = 1
                        }
                    })
                    .catch(error => {
                        if (this.$store.state.debug) console.log(error)
                    })
                    .finally(() => {
                        this.codeActivation = false
                    })
            },

            checkRCCode() {
                this.codeChecking = true
                let rc = this.$store.getters['rc/getByCode'](this.codeManuallyEntered)
                if (rc === undefined) {
                    this.$store.dispatch('rc/checkReferral', this.codeManuallyEntered)
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
                    this.codeChecking = false
                }
            },

            saveCode() {
                this.$store.commit('rc/add', this.codeReceived)
                this.selectedPerson.referral_code = new ReferralCode(this.codeReceived)
                this.oldCode = new ReferralCode()
                this.codeManuallyEntered = ''
                this.codeReceived = null
                this.codeManualInput = false

                this.activateRC()
            },

            activateManualInput() {
                this.oldCode = this.selectedPerson.referral_code
                this.selectedPerson.referral_code = new ReferralCode()
                this.codeManualInput = true
            },

            deactivateManualInput() {
                this.selectedPerson.referral_code = this.oldCode
                this.oldCode = new ReferralCode()
                this.codeManuallyEntered = ''
                this.codeIsInvalid = false
                this.codeManualInput = false
            },
        }
    }
</script>

<style scoped>

</style>
