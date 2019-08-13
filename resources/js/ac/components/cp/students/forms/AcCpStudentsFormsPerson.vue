<template>
    <form
        class="needs-validation"
        novalidate
    >
        <div class="form-row">
            <div class="form-group col-6 d-flex justify-content-center align-items-center">
                <ac-cp-students-forms-person-photo></ac-cp-students-forms-person-photo>
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
                        :disabled="selectedPerson.id > 0"
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
                        :disabled="selectedPerson.id > 0"
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
                        :disabled="selectedPerson.id > 0"
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
                        :disabled="selectedPerson.id > 0"
                        required
                    >
                    <div class="invalid-feedback">
                        Поле "Дата рождения" является обязательным!
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="address">
                Адрес
            </label>
            <input
                id="address"
                v-model="selectedPerson.address"
                type="text"
                class="form-control"
                placeholder="Адрес"
            >
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
                <ac-form-cards></ac-form-cards>
            </div>
            <div class="form-group col-6">
            </div>
        </div>
        <div class="form-row d-md-none">
            <slot name="basicEducation"></slot>
        </div>
        <div class="form-row d-md-none">
            <slot name="additionalEducation"></slot>
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
                <ac-buttons-cancel></ac-buttons-cancel>
            </div>
        </div>
        <ac-cp-students-forms-person-modal></ac-cp-students-forms-person-modal>
    </form>
</template>

<script>
    import AcFormCards from './AcCpStudentsFormsPersonCards'
    import AcCpStudentsFormsPersonPhoto from './AcCpStudentsFormsPersonPhoto'
    import AcButtonsCancel from '../../../buttons/AcButtonsCancel'
    import AcButtonsSavePerson from '../../../buttons/AcButtonsSavePerson'
    import AcButtonsUpdatePerson from '../../../buttons/AcButtonsUpdatePerson'
    import AcCpStudentsFormsPersonModal from './AcCpStudentsFormsPersonModal'

    export default {
        name: "AcCpStudentsFormsPerson",

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
            AcFormCards, AcCpStudentsFormsPersonPhoto,
            AcButtonsCancel, AcButtonsSavePerson, AcButtonsUpdatePerson,
            AcCpStudentsFormsPersonModal
        },

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            hasErrors() {
                return this.errors.f || this.errors.i || this.errors.birthday || this.selectedPerson.organizations.basic === null
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
