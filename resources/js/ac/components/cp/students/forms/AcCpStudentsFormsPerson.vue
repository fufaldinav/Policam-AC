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
                        {{ $t('Фамилия') }}
                    </label>
                    <input
                        id="f"
                        v-model="selectedPerson.f"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('f') === false }"
                        :placeholder="$t('Фамилия')"
                        :disabled="selectedPerson.id > 0"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('Поле "фамилия" является обязательным!') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="i">
                        {{ $t('Имя') }}
                    </label>
                    <input
                        id="i"
                        v-model="selectedPerson.i"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('i') === false }"
                        :placeholder="$t('Имя')"
                        :disabled="selectedPerson.id > 0"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('Поле "имя" является обязательным!') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="o">
                        {{ $t('Отчество') }}
                    </label>
                    <input
                        id="o"
                        v-model="selectedPerson.o"
                        type="text"
                        class="form-control"
                        :placeholder="$t('Отчество')"
                        :disabled="selectedPerson.id > 0"
                    >
                </div>
                <div class="form-group">
                    <label for="birthday">
                        {{ $t('Дата рождения') }}
                    </label>
                    <input
                        id="birthday"
                        v-model="selectedPerson.birthday"
                        type="date"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('birthday') === false }"
                        :placeholder="$t('Дата рождения')"
                        :disabled="selectedPerson.id > 0"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('Поле "дата рождения" является обязательным!') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="address">
                {{ $t('Адрес') }}
            </label>
            <input
                id="address"
                v-model="selectedPerson.address"
                type="text"
                class="form-control"
                :placeholder="$t('Адрес')"
            >
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="phone">
                    {{ $t('Номер телефона') }}
                </label>
                <input
                    id="phone"
                    v-model="selectedPerson.phone"
                    type="text"
                    class="form-control"
                    :placeholder="$t('Номер телефона')"
                >
            </div>
            <div class="form-group col-6">
                <label for="uid">
                    {{ $t('Уникальный номер') }}
                </label>
                <input
                    id="uid"
                    v-model="selectedPerson.id"
                    type="text"
                    class="form-control"
                    :placeholder="$t('Уникальный номер')"
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
                <ac-buttons-cancel v-if="selectedPerson.id !== null"></ac-buttons-cancel>
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
