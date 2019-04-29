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
                        {{ $t('Фамилия') }}
                    </label>
                    <input
                        id="f"
                        v-model="selectedPerson.f"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('f') === false }"
                        :placeholder="$t('Фамилия')"
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('Поле "Фамилия" является обязательным!') }}
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
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('Поле "Имя" является обязательным!') }}
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
                        :disabled="selectedPerson.id === null"
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
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('Поле "Дата рождения" является обязательным!') }}
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
                :disabled="selectedPerson.id === null"
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
                    :disabled="selectedPerson.id === null"
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
                <ac-cp-persons-forms-cards></ac-cp-persons-forms-cards>
            </div>
            <div class="form-group col-6">
                <ac-cp-persons-forms-last-card></ac-cp-persons-forms-last-card>
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
    import AcCpPersonsFormsCards from './AcCpPersonsFormsCards'
    import AcCpPersonsFormsLastCard from './AcCpPersonsFormsLastCard'
    import AcCpPersonsFormsPersonPhoto from './AcCpPersonsFormsPersonPhoto'
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
            AcCpPersonsFormsCards, AcCpPersonsFormsLastCard, AcCpPersonsFormsPersonPhoto,
            AcButtonsCancel, AcButtonsRemovePerson, AcButtonsSavePerson, AcButtonsUpdatePerson,
            AcCpPersonsFormsModal
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
