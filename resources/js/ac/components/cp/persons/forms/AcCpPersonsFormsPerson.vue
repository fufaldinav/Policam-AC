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
                        :disabled="formDisabled"
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
                        :disabled="formDisabled"
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
                        :disabled="formDisabled"
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
                        :disabled="formDisabled"
                        required
                    >
                    <div class="invalid-feedback">
                        Поле "Дата рождения" является обязательным!
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row d-flex justify-content-center">
            <div class="form-check form-check-inline">
                Пол:
            </div>
            <div class="form-check form-check-inline">
                <input
                    v-model="selectedPerson.gender"
                    class="form-check-input"
                    type="radio"
                    name="gender"
                    id="gender-m"
                    value="1"
                    :disabled="formDisabled"
                >
                <label class="form-check-label" for="gender-m">Мужской</label>
            </div>
            <div class="form-check form-check-inline">
                <input
                    v-model="selectedPerson.gender"
                    class="form-check-input"
                    type="radio"
                    name="gender"
                    id="gender-w"
                    value="2"
                    :disabled="formDisabled"
                >
                <label class="form-check-label" for="gender-w">Женский</label>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4 col-sm-3">
                <ac-cp-persons-forms-select-division
                    v-if="selectedOrganization !== null"></ac-cp-persons-forms-select-division>
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
                    :disabled="formDisabled"
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
                    :disabled="formDisabled"
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
        <ac-cp-persons-forms-person-r-c v-if="selectedPerson.id !== null"></ac-cp-persons-forms-person-r-c>
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
                <ac-buttons-remove-person v-if="selectedPerson.id !== null && ! formDisabled"></ac-buttons-remove-person>
                <ac-buttons-cancel v-if="selectedPerson.id !== null"></ac-buttons-cancel>
            </div>
        </div>
        <ac-cp-persons-forms-modal></ac-cp-persons-forms-modal>
    </form>
</template>

<script>
    import AcCpPersonsFormsPersonRC from './AcCpPersonsFormsPersonRC'
    import AcCpPersonsFormsPersonPhoto from './AcCpPersonsFormsPersonPhoto'
    import AcCpPersonsFormsSelectDivision from './AcCpPersonsFormsSelectDivision'
    import AcButtonsCancel from '../../../buttons/AcButtonsCancel'
    import AcButtonsRemovePerson from '../../../buttons/AcButtonsRemovePerson'
    import AcButtonsSavePerson from '../../../buttons/AcButtonsSavePerson'
    import AcButtonsUpdatePerson from '../../../buttons/AcButtonsUpdatePerson'
    import AcCpPersonsFormsModal from './AcCpPersonsFormsModal'

    export default {
        name: "AcCpPersonsFormsPerson",

        data() {
            return {
                errors: {
                    f: false,
                    i: false,
                    o: false,
                    birthday: false
                }
            }
        },

        components: {
            AcCpPersonsFormsPersonRC,
            AcCpPersonsFormsPersonPhoto,
            AcCpPersonsFormsSelectDivision,
            AcButtonsCancel,
            AcButtonsRemovePerson,
            AcButtonsSavePerson,
            AcButtonsUpdatePerson,
            AcCpPersonsFormsModal
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
            },

            formDisabled() {
                return this.selectedPerson.organization_id !== this.selectedOrganization.id && this.selectedPerson.id !== 0
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
