<template>
    <div class="modal fade" id="addStudentForm" tabindex="-1" role="dialog" aria-labelledby="addStudentFormTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentFormTitle">Добавление ребёнка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-f">Фамилия</span>
                        </div>
                        <input
                            v-model="student.f"
                            type="text"
                            class="form-control"
                            :class="checkInputForFClass(student.f)"
                            placeholder="Например, Иванов"
                            aria-label="Фамилия"
                            aria-describedby="student-f"
                            required
                        >
                        <div class="invalid-feedback">
                            Фамилия должна начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-i">Имя</span>
                        </div>
                        <input
                            v-model="student.i"
                            type="text"
                            class="form-control"
                            :class="checkInputClass(student.i)"
                            placeholder="Например, Иван"
                            aria-label="Имя"
                            aria-describedby="student-i"
                            required
                        >
                        <div class="invalid-feedback">
                            Имя должно начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-o">Отчество</span>
                        </div>
                        <input
                            v-model="student.o"
                            type="text"
                            class="form-control"
                            :class="checkInputClass(student.o)"
                            placeholder="Например, Иванович"
                            aria-label="Отчество"
                            aria-describedby="student-o"
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
                                v-model="student.gender"
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="student-boy"
                                value="1"
                                required
                            >
                            <label class="form-check-label" for="student-boy">Мужской</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                v-model="student.gender"
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="student-girl"
                                value="2"
                                required
                            >
                            <label class="form-check-label" for="student-girl">Женский</label>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-birthday">Дата рождения</label>
                        </div>
                        <input
                            v-model="student.birthday"
                            id="student-birthday"
                            type="date"
                            class="form-control"
                            placeholder="Например, 24.10.2010"
                            required
                        >
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-code">Карта/браслет</label>
                        </div>
                        <select
                            v-if="(activeCodesCount > 0 || student.code > 0) && ! codeManualInput"
                            v-model="student.code"
                            class="custom-select"
                            id="student-code"
                            @change="student.division = 0"
                            required
                        >
                            <option disabled value="0">Выберите карту...</option>
                            <option
                                v-for="code of codes"
                                v-if="code.activated === 0 || student.code === code.id"
                                :key="code.id"
                                :value="code.id"
                            >
                                {{ code.code }}
                            </option>
                        </select>
                        <input
                            v-else
                            v-model="codeToCheck"
                            id="student-code"
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
                            v-if="! codeManualInput && (activeCodesCount > 0 || student.code > 0)"
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
                            class="btn btn-primary"
                            @click="saveCode"
                        >
                            Сохранить
                        </button>
                    </div>
                    <div
                        v-if="organization !== undefined"
                        class="container-fluid mb-3"
                    >
                        <p class="text-center">Школа: {{ organization.name }}</p>
                    </div>
                    <div
                        v-if="student.code > 0 && organization === undefined && ! loading"
                        class="input-group mb-3"
                    >
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-organization">Школа</label>
                        </div>
                        <select
                            v-model="student.organization"
                            class="custom-select"
                            id="student-organization"
                            @change="loadDivisions($event)"
                            required
                        >
                            <option disabled value="0">Выберите школу...</option>
                            <option
                                v-for="organization of organizations"
                                :key="organization.id"
                                :value="organization.id"
                            >
                                {{ organization.name }}
                            </option>
                        </select>
                    </div>
                    <div
                        v-if="divisions.length > 0"
                        class="input-group"
                    >
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-division">Класс</label>
                        </div>
                        <select
                            v-model="student.division"
                            class="custom-select"
                            id="student-division"
                            required
                        >
                            <option disabled value="0">Выберите класс...</option>
                            <option
                                v-for="division of divisions"
                                :value="division.id"
                            >
                                {{ division.name }}
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
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Отменить
                    </button>
                    <button
                        v-if="windowType === 'add'"
                        type="button"
                        class="btn btn-primary"
                        :disabled="buttonDisabled"
                        @click="addStudent()"
                    >
                        Добавить
                    </button>
                    <button
                        v-if="windowType === 'edit'"
                        type="button"
                        class="btn btn-primary"
                        :disabled="buttonDisabled"
                        @click="saveStudent()"
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
        name: "PostregAddStudentForm",

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
            student() {
                return this.$store.state.postreg.currentStudent
            },

            codes() {
                return this.$store.state.postreg.codes
            },

            activeCodesCount() {
                return this.$store.getters['postreg/getActiveCodesCount']
            },

            organization() {
                return this.$store.getters['postreg/getOrganizationByCode'](this.student.code)
            },

            organizations() {
                return this.$store.state.postreg.organizations
            },

            divisions() {
                let organizationId = 0

                if (this.student.organization > 0) {
                    organizationId = this.student.organization
                } else {
                    let organization = this.$store.getters['postreg/getOrganizationByCode'](this.student.code)

                    if (organization === undefined) {
                        return []
                    } else {
                        organizationId = organization.id
                    }
                }

                return this.$store.getters['postreg/getDivisionsByOrg'](organizationId)
            },

            buttonDisabled() {
                return this.student.f === null || this.student.i === null || this.student.o === null || this.student.gender === null || this.student.birthday === null || this.student.code === 0 ||
                    this.student.f === '' || this.student.i === '' || this.student.o === '' || this.student.gender === '' || this.student.birthday === ''
                    || ! this.checkInputForF(this.student.f) || ! this.checkInput(this.student.i) || ! this.checkInput(this.student.o) || this.student.division === 0
            },

            buttonDisabledTooltip() {
                if (this.buttonDisabled) {
                    return 'Введены не все поля'
                }
            },

            windowType() {
                return this.$store.state.postreg.studentFormType
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
            addStudent() {
                this.student.gender = parseInt(this.student.gender, 10)
                this.$store.commit('postreg/addStudent', this.student)
                this.$store.commit('postreg/setCodeActivatedStatus', {codeId: this.student.code, activated: 1})
                $('#addStudentForm').modal('hide')
            },

            saveStudent() {
                if (typeof this.student.gender === 'string') {
                    this.student.gender = parseInt(this.student.gender, 10)
                }
                this.$store.commit('postreg/saveStudent', this.student)
                this.$store.commit('postreg/setCodeActivatedStatus', {codeId: this.student.code, activated: 1})
                $('#addStudentForm').modal('hide')
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

            checkCodeCode() {
                this.codeChecking = true
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
            },

            saveCode() {
                this.$store.commit('postreg/addCode', this.codeReceived)
                this.student.code = this.codeReceived.id
                this.loading = true
                this.$store.dispatch('postreg/loadOrganization', this.codeReceived.organization_id)
                    .then(response => {
                        if (response.length > 0) {
                            for (let organization of response) {
                                this.$store.commit('postreg/addOrganization', organization)
                                this.$store.dispatch('postreg/loadDivisions', organization.id)
                                    .then(response => {
                                        for (let division of response) {
                                            this.$store.commit('postreg/addDivision', division)
                                        }
                                    })
                                    .catch(error => {
                                        if (this.$store.state.debug) console.log(error)
                                    })
                            }
                        } else {
                            this.$store.dispatch('postreg/loadOrganizations')
                                .then(response => {
                                    for (let organization of response) {
                                        this.$store.commit('postreg/addOrganization', organization)
                                    }
                                })
                                .catch(error => {
                                    if (this.$store.state.debug) console.log(error)
                                })
                        }
                        this.loading = false
                    })
                    .catch(error => {
                        if (this.$store.state.debug) console.log(error)
                    })
                this.codeManuallyEntered = ''
                this.codeReceived = null
                this.codeManualInput = false
            },

            activateManualInput() {
                this.oldCode = this.student.code
                this.student.code = 0
                this.oldOrganization = this.student.organization
                this.student.organization = 0
                this.codeManualInput = true
            },

            deactivateManualInput() {
                this.student.code = this.oldCode
                this.oldCode = 0
                this.codeManuallyEntered = ''
                this.codeIsInvalid = false
                this.student.organization = this.oldOrganization
                this.oldOrganization = 0
                this.codeManualInput = false
            },

            loadDivisions(event) {
                this.student.division = 0
                let organizationId = parseInt(event.target.value, 10)

                if (this.$store.getters['postreg/getDivisionsByOrg'](organizationId).length > 0) return

                this.$store.dispatch('postreg/loadDivisions', organizationId)
                    .then(response => {
                        for (let division of response) {
                            this.$store.commit('postreg/addDivision', division)
                        }
                    })
                    .catch(error => {
                        if (this.$store.state.debug) console.log(error)
                    })
            }
        }
    }
</script>

<style scoped>

</style>
