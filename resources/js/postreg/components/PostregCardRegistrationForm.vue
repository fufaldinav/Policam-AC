<template>
    <div class="modal fade" id="cardRegistrationForm" tabindex="-1" role="dialog"
         aria-labelledby="cardRegistrationFormTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cardRegistrationFormTitle">Регистрация карты</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="user-f">Фамилия</span>
                        </div>
                        <input
                            v-model="user.f"
                            type="text"
                            class="form-control"
                            :class="checkInputForFClass(user.f)"
                            placeholder="Например, Иванов"
                            aria-label="Фамилия"
                            aria-describedby="user-f"
                            required
                        >
                        <div class="invalid-feedback">
                            Фамилия должна начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="user-i">Имя</span>
                        </div>
                        <input
                            v-model="user.i"
                            type="text"
                            class="form-control"
                            :class="checkInputClass(user.i)"
                            placeholder="Например, Иван"
                            aria-label="Имя"
                            aria-describedby="user-i"
                            required
                        >
                        <div class="invalid-feedback">
                            Имя должно начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="user-o">Отчество</span>
                        </div>
                        <input
                            v-model="user.o"
                            type="text"
                            class="form-control"
                            :class="checkInputClass(user.o)"
                            placeholder="Например, Иванович"
                            aria-label="Отчество"
                            aria-describedby="user-o"
                            required
                        >
                        <div class="invalid-feedback">
                            Отчество должно начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="container-fluid mb-3">
                        <p class="text-center">Пол:</p>
                    </div>
                    <div class="container-fluid justify-content-center d-flex mb-3">
                        <div class="form-check form-check-inline">
                            <input
                                v-model="user.gender"
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="user-boy"
                                value="1"
                                required
                            >
                            <label class="form-check-label" for="user-boy">Мужской</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                v-model="user.gender"
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="user-girl"
                                value="2"
                                required
                            >
                            <label class="form-check-label" for="user-girl">Женский</label>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="user-birthday">Дата рождения</label>
                        </div>
                        <input
                            v-model="user.birthday"
                            id="user-birthday"
                            type="date"
                            class="form-control"
                            placeholder="Например, 24.10.2010"
                            required
                        >
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="user-code">Карта/браслет</label>
                        </div>
                        <select
                            v-if="(activeCodesCount > 0 || user.code > 0)  && ! codeManualInput"
                            class="custom-select"
                            id="user-code"
                            @change.prevent="codeChanged($event)"
                            required
                        >
                            <option
                                disabled
                                :selected="user.code === 0"
                                value="0">
                                Выберите карту...
                            </option>
                            <option
                                v-for="code of codes"
                                :key="code.id"
                                :value="code.id"
                                :disabled="code.activated === 1 && user.code !== code.id"
                            >
                                {{ code.code }}
                            </option>
                        </select>
                        <input
                            v-else
                            v-model="codeToCheck"
                            id="user-code"
                            type="text"
                            class="form-control"
                            :class="{'is-invalid': codeIsInvalid}"
                            placeholder="Номер с карты/браслета"
                            @focus="codeManualInput = true"
                            required
                        >
                        <div class="invalid-feedback text-center">
                            Код занят или не найден
                        </div>
                    </div>
                    <div class="container-fluid mb-3 d-flex justify-content-end">
                        <button
                            v-if="! codeManualInput && (activeCodesCount > 0 || user.code > 0)"
                            type="button"
                            class="btn btn-warning"
                            @click="activateManualInput"
                        >
                            Ручной ввод карты
                        </button>
                        <button
                            v-if="codeManualInput"
                            type="button"
                            class="btn btn-secondary mr-2"
                            @click="deactivateManualInput"
                        >
                            Отмена
                        </button>
                        <button
                            v-if="codeManualInput && codeReceived === null"
                            type="button"
                            class="btn btn-primary"
                            :disabled="checkButtonDisabled"
                            @click="checkCodeCode"
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
                            v-if="(codeManualInput || activeCodesCount === 0) && codeReceived !== null"
                            type="button"
                            class="btn btn-success"
                            @click="saveCode"
                        >
                            Сохранить
                        </button>
                    </div>
                    <div
                        v-if="organization !== undefined"
                        class="container-fluid mb-2"
                    >
                        <p class="text-center">Организация: {{ organization.name }}</p>
                    </div>
                    <div
                        v-if="user.code > 0 && organization === undefined && ! loading"
                        class="input-group mb-2"
                    >
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="user-organization">Организация</span>
                        </div>
                        <select
                            v-model="user.organization"
                            class="custom-select"
                            id="student-organization"
                            required
                        >
                            <option disabled value="0">Выберите организацию...</option>
                            <option
                                v-for="organization of organizations"
                                :key="organization.id"
                                :value="organization.id"
                            >
                                {{ organizationFullName(organization) }}
                            </option>
                        </select>
                    </div>
                </div>
                <p
                    v-if="buttonDisabled"
                    class="text-center text-danger"
                >
                    Заполнены не все поля!
                </p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        :disabled="buttonDisabled"
                        @click="saveUserProfile()"
                    >
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PostregCardRegistrationForm",

        data() {
            return {
                codeManualInput: false,
                codeManuallyEntered: '',
                codeChecking: false,
                codeReceived: null,
                codeIsInvalid: false,
                oldCode: 0,
                oldOrganization: 0,
                loading: false
            }
        },

        computed: {
            user() {
                return this.$store.state.postreg.user
            },


            codes() {
                return this.$store.state.postreg.codes
            },

            activeCodesCount() {
                return this.$store.getters['postreg/getActiveCodesCount']
            },

            organization() {
                let org = this.$store.getters['postreg/getOrganizationByCode'](this.user.code)
                if (org === undefined) return
                this.user.organization = org.id
                return org
            },

            organizations() {
                return this.$store.getters['postreg/getSortedOrganizations']
            },

            activeCodesCount() {
                return this.$store.getters['postreg/getActiveCodesCount']
            },

            buttonDisabled() {
                return this.user.f === null || this.user.i === null || this.user.o === null || this.user.gender === null || this.user.birthday === null || this.user.code === 0 ||
                    this.user.f === '' || this.user.i === '' || this.user.o === '' || this.user.gender === '' || this.user.birthday === ''
                    || ! this.checkInputForF(this.user.f) || ! this.checkInput(this.user.i) || ! this.checkInput(this.user.o) || this.user.organization === 0
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
            saveUserProfile() {
                if (typeof this.user.gender === 'string') {
                    this.user.gender = parseInt(this.user.gender, 10)
                }
                this.$store.commit('postreg/setUserCheckedStatus', true)
                this.$store.commit('postreg/setCodeActivatedStatus', {codeId: this.user.code, activated: 1})
                this.$store.commit('postreg/setCodeActivatedStatus', {codeId: this.oldCode, activated: 0})
                $('#cardRegistrationForm').modal('hide')
            },

            checkInputForF(input) {
                let reg = /^[А-ЯЁ][а-яё]+[-]*[А-ЯЁа-яё]*$/
                return reg.test(input)
            },

            checkInput(input) {
                let reg = /^[А-ЯЁ][а-яё]+$/
                return reg.test(input)
            },

            checkInputClass(input) {
                return {
                    'is-invalid': input !== null && input !== '' && ! this.checkInput(input) && input.length >= 2
                }
            },

            checkInputForFClass(input) {
                return {
                    'is-invalid': input !== null && input !== '' && ! this.checkInputForF(input) && input.length >= 2
                }
            },

            codeChanged(event) {
                this.oldCode = this.user.code
                this.user.code = parseInt(event.target.value, 10)
                this.loadOrganizations(this.user.code)
            },

            checkCodeCode() {
                this.codeChecking = true
                if (this.$store.getters['postreg/checkCodeActivity'](this.codeManuallyEntered)) {
                    this.codeIsInvalid = true
                    this.codeChecking = false
                } else {
                    this.$store.dispatch('postreg/getReferral', this.codeManuallyEntered)
                        .then(response => {
                            if (response === 0) {
                                this.codeIsInvalid = true
                            } else {
                                this.codeReceived = response
                            }
                            this.codeChecking = false
                        })
                        .catch(error => {
                            if (this.$store.state.debug) console.log(error)
                            this.codeChecking = false
                        })
                }
            },

            saveCode() {
                this.$store.commit('postreg/addCode', this.codeReceived)
                this.user.code = this.codeReceived.id
                this.$store.commit('postreg/setCodeActivatedStatus', {codeId: this.oldCode, activated: 0})
                this.loadOrganizations(this.user.code)
                this.codeManuallyEntered = ''
                this.codeReceived = null
                this.codeManualInput = false
            },

            activateManualInput() {
                this.oldCode = this.user.code
                this.user.code = 0
                this.oldOrganization = this.user.organization
                this.user.organization = 0
                this.codeManualInput = true
            },

            deactivateManualInput() {
                this.user.code = this.oldCode
                this.oldCode = 0
                this.codeManuallyEntered = ''
                this.codeIsInvalid = false
                this.user.organization = this.oldOrganization
                this.oldOrganization = 0
                this.codeManualInput = false
            },

            loadOrganizations(codeId) {
                this.loading = true

                let code = this.$store.getters['postreg/getCodeById'](codeId)
                if (code === undefined) {
                    this.loading = false
                    return
                }

                if (code.organization_id === 0) {
                    this.$store.dispatch('postreg/loadOrganizations')
                        .then(response => {
                            for (let organization of response) {
                                this.$store.commit('postreg/addOrganization', organization)
                            }
                        })
                        .catch(error => {
                            if (this.$store.state.debug) console.log(error)
                        })
                        .finally(() => {
                            this.loading = false
                        })
                } else if (this.$store.getters['postreg/getOrganizationById'](code.organization_id) === undefined) {
                    this.$store.dispatch('postreg/loadOrganization', {organizationId: code.organization_id})
                        .then(response => {
                            for (let organization of response) {
                                this.$store.commit('postreg/addOrganization', organization)
                            }
                            let org = this.organization
                            if (org !== undefined) {
                                this.user.organization = org.id
                            }
                        })
                        .catch(error => {
                            if (this.$store.state.debug) console.log(error)
                        })
                        .finally(() => {
                            this.loading = false
                        })
                } else {
                    this.loading = false
                }
            },

            organizationFullName(organization) {
                let fullName = organization.name
                if (organization.address !== null) {
                    fullName += ', ' + organization.address
                }
                return fullName
            }
        }
    }
</script>

<style scoped>

</style>
