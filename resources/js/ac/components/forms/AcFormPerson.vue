<template>
    <form
        class="needs-validation"
        novalidate
    >
        <div class="form-row">
            <div class="form-group col-6 d-flex justify-content-center align-items-center">
                <ac-form-person-photo></ac-form-person-photo>
            </div>
            <div class="form-group col-6">
                <div class="form-group">
                    <label for="f">
                        {{ $t('ac.f') }}
                    </label>
                    <input
                        id="f"
                        v-model="selectedPerson.f"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('f') === false }"
                        :placeholder="$t('ac.f')"
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.f')}) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="i">
                        {{ $t('ac.i') }}
                    </label>
                    <input
                        id="i"
                        v-model="selectedPerson.i"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('i') === false }"
                        :placeholder="$t('ac.i')"
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.i')}) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="o">
                        {{ $t('ac.o') }}
                    </label>
                    <input
                        id="o"
                        v-model="selectedPerson.o"
                        type="text"
                        class="form-control"
                        :placeholder="$t('ac.o')"
                        :disabled="selectedPerson.id === null"
                    >
                </div>
                <div class="form-group">
                    <label for="birthday">
                        {{ $t('ac.birthday') }}
                    </label>
                    <input
                        id="birthday"
                        v-model="selectedPerson.birthday"
                        type="date"
                        class="form-control"
                        :class="{ 'is-invalid': checkField('birthday') === false }"
                        :placeholder="$t('ac.birthday')"
                        :disabled="selectedPerson.id === null"
                        required
                    >
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.birthday')}) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="address">
                {{ $t('ac.address') }}
            </label>
            <input
                id="address"
                v-model="selectedPerson.address"
                type="text"
                class="form-control"
                :placeholder="$t('ac.address')"
                :disabled="selectedPerson.id === null"
            >
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="phone">
                    {{ $t('ac.phone') }}
                </label>
                <input
                    id="phone"
                    v-model="selectedPerson.phone"
                    type="text"
                    class="form-control"
                    :placeholder="$t('ac.phone')"
                    :disabled="selectedPerson.id === null"
                >
            </div>
            <div class="form-group col-6">
                <label for="uid">
                    {{ $t('ac.uid') }}
                </label>
                <input
                    id="uid"
                    v-model="selectedPerson.id"
                    type="text"
                    class="form-control"
                    :placeholder="$t('ac.uid')"
                    disabled
                >
            </div>
        </div>
        <div v-if="selectedPerson.id !== null" class="form-row">
            <div class="form-group col-6">
                <ac-form-cards></ac-form-cards>
            </div>
            <div class="form-group col-6">
                <ac-form-last-card></ac-form-last-card>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <ac-button-save-person
                    v-if="selectedPerson.id === 0"
                    :disabled="hasErrors"
                >
                </ac-button-save-person>
                <ac-button-update-person
                    v-if="selectedPerson.id > 0"
                    :disabled="hasErrors"
                >
                </ac-button-update-person>
                <ac-button-remove-person
                    v-if="selectedPerson.id > 0"
                >
                </ac-button-remove-person>
                <ac-button-cancel v-if="selectedPerson.id !== null"></ac-button-cancel>
            </div>
        </div>
        <ac-form-modal></ac-form-modal>
    </form>
</template>

<script>
    import AcFormCards from './AcFormPersonCards'
    import AcFormLastCard from './AcFormPersonLastCard'
    import AcFormPersonPhoto from './AcFormPersonPhoto'
    import AcButtonCancel from '../buttons/AcButtonCancel'
    import AcButtonRemovePerson from '../buttons/AcButtonRemovePerson'
    import AcButtonSavePerson from '../buttons/AcButtonSavePerson'
    import AcButtonUpdatePerson from '../buttons/AcButtonUpdatePerson'
    import AcFormModal from "./AcFormModal"

    export default {
        name: "AcFormPerson",

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
            AcFormCards, AcFormLastCard, AcFormPersonPhoto,
            AcButtonCancel, AcButtonRemovePerson, AcButtonSavePerson, AcButtonUpdatePerson,
            AcFormModal
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
