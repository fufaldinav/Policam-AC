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
                    <div
                        v-if="activeCodesCount > 0 || user.code > 0"
                        class="input-group mb-3"
                    >
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="user-code">Карта/браслет</label>
                        </div>
                        <select
                            v-model="user.code"
                            class="custom-select"
                            id="user-code"
                            required
                        >
                            <option disabled value="0">Выберите карту...</option>
                            <option
                                v-for="code of codes"
                                v-if="code.activated === 0 || user.code === code.id"
                                :value="code.id"
                            >
                                {{ code.code }}
                            </option>
                        </select>
                    </div>
                    <div
                        v-else
                        class="input-group mb-3"
                    >
                        NO CARDS
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="user-organization">Организация</span>
                        </div>
                        <input
                            type="text"
                            class="form-control bg-white"
                            placeholder="Организация..."
                            aria-label="Организация"
                            aria-describedby="user-organization"
                            :value="organization"
                            readonly
                        >
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

        computed: {
            user() {
                return this.$store.state.postreg.user
            },

            organization() {
                let org = this.$store.getters['postreg/getOrganizationByCode'](this.user.code)
                if (org === undefined) return 'Неизвестная организация'
                return org.name
            },

            codes() {
                return this.$store.state.postreg.codes
            },

            activeCodesCount() {
                return this.$store.getters['postreg/getActiveCodesCount']
            },

            buttonDisabled() {
                return this.user.f === null || this.user.i === null || this.user.o === null || this.user.gender === null || this.user.birthday === null || this.user.code === null ||
                    this.user.f === '' || this.user.i === '' || this.user.o === '' || this.user.gender === '' || this.user.birthday === '' || this.user.code === ''
                    || ! this.checkInputForF(this.user.f) || ! this.checkInput(this.user.i) || ! this.checkInput(this.user.o)
            },

            buttonDisabledTooltip() {
                if (this.buttonDisabled) {
                    return 'Введены не все поля'
                }
            },

            windowType() {
                return this.$store.state.postreg.studentFormType
            }
        },

        methods: {
            saveUserProfile() {
                this.$store.commit('postreg/setUserCheckedStatus', true)
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
            }
        }
    }
</script>

<style scoped>

</style>
