<template>
    <form
        class="needs-validation"
        novalidate
    >
        <div class="form-row">
            <div class="form-group col-6 d-flex justify-content-center align-items-center">
                <ac-cp-persons-forms-person-photo></ac-cp-persons-forms-person-photo>
            </div>
            <div class="form-group col-6">
                <div class="form-group">
                    <label for="f">
                        Фамилия
                    </label>
                    <input
                        id="f"
                        v-model="selectedPerson.f"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('f') === false }"
                        placeholder="Фамилия"
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        Поле "Фамилия" является обязательным!
                    </div>
                </div>
                <div class="form-group">
                    <label for="i">
                        Имя
                    </label>
                    <input
                        id="i"
                        v-model="selectedPerson.i"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('i') === false }"
                        placeholder="Имя"
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        Поле "Имя" является обязательным!
                    </div>
                </div>
                <div class="form-group">
                    <label for="o">
                        Отчество
                    </label>
                    <input
                        id="o"
                        v-model="selectedPerson.o"
                        type="text"
                        class="form-control"
                        placeholder="Отчество"
                        :disabled="selectedPerson.id === null"
                    >
                </div>
                <div class="form-group">
                    <label for="birthday">
                        Дата рождения
                    </label>
                    <input
                        id="birthday"
                        v-model="selectedPerson.birthday"
                        type="date"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('birthday') === false }"
                        placeholder="Дата рождения"
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        Поле "Дата рождения" является обязательным!
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4 col-sm-3">
                <ac-cp-persons-forms-select-division v-if="selectedOrganization.type === 1"></ac-cp-persons-forms-select-division>
            </div>
            <div class="form-group col-8 col-sm-9">
                <label for="address">
                    Адрес
                </label>
                <input
                    id="address"
                    v-model="selectedPerson.address"
                    type="text"
                    class="form-control"
                    placeholder="Адрес"
                    :disabled="selectedPerson.id === null"
                >
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="phone">
                    Номер телефона
                </label>
                <input
                    id="phone"
                    v-model="selectedPerson.phone"
                    type="text"
                    class="form-control"
                    placeholder="Номер телефона"
                    :disabled="selectedPerson.id === null"
                >
            </div>
            <div class="form-group col-6">
                <label for="uid">
                    Уникальный номер
                </label>
                <input
                    id="uid"
                    v-model="selectedPerson.id"
                    type="text"
                    class="form-control"
                    placeholder="Уникальный номер"
                    readonly
                >
            </div>
        </div>
        <div v-if="selectedPerson.id !== null" class="form-row">
            <div class="form-group col-6">
            </div>
            <div class="form-group col-6">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <ac-buttons-save-person
                    v-if="selectedPerson.id === 0"
                    :disabled="hasErrors"
                >
                </ac-buttons-save-person>
                <ac-buttons-update-person
                    v-if="selectedPerson.id > 0"
                    :disabled="hasErrors"
                >
                </ac-buttons-update-person>
                <ac-buttons-remove-person
                    v-if="selectedPerson.id > 0"
                >
                </ac-buttons-remove-person>
                <ac-buttons-cancel v-if="selectedPerson.id !== null"></ac-buttons-cancel>
            </div>
        </div>
        <ac-cp-persons-forms-modal></ac-cp-persons-forms-modal>
    </form>
</template>

<script>
    import AcCpPersonsFormsPersonPhoto from './AcCpPersonsFormsPersonPhoto'
    import AcCpPersonsFormsSelectDivision from './AcCpPersonsFormsSelectDivision'
    import AcButtonsCancel from '../../../buttons/AcButtonsCancel'
    import AcButtonsRemovePerson from '../../../buttons/AcButtonsRemovePerson'
    import AcButtonsSavePerson from '../../../buttons/AcButtonsSavePerson'
    import AcButtonsUpdatePerson from '../../../buttons/AcButtonsUpdatePerson'
    import AcCpPersonsFormsModal from './AcCpPersonsFormsModal'

    export default {
        name: "AcCpPersonsFormsPerson",

        data: function () {
            return {
                errors: {
                    f: false,
                    i: false,
                    birthday: false
                }
            }
        },

        components: {
            AcCpPersonsFormsPersonPhoto, AcCpPersonsFormsSelectDivision, AcButtonsCancel, AcButtonsRemovePerson,
            AcButtonsSavePerson, AcButtonsUpdatePerson, AcCpPersonsFormsModal
        },

        computed: {
            selectedOrganization() {
                return this.$store.state.organizations.selected
            },

            selectedPerson() {
                return this.$store.state.persons.selected
            },

            hasErrors() {
                return this.errors.f || this.errors.i || this.errors.birthday
            }
        },

        methods: {
            checkField(fieldName) {
                if (this.selectedPerson.id !== null && (this.selectedPerson[fieldName] === null || this.selectedPerson[fieldName] === '')) {
                    this.errors[fieldName] = true
                    return false
                }

                this.errors[fieldName] = false
                return true
            }
        }
    }
</script>
