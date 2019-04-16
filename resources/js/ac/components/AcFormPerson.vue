<template>
    <form class="needs-validation" novalidate>
        <div class="form-row">
            <div class="form-group col-6">
                <div class="photo-bg">
                    <div class="photo-del" :data-title="$t('ac.delete')" hidden>
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <input type="file" class="form-control-file" @change="handleFiles(this.files)" :disabled="selectedPerson.id === null">
            </div>
            <div class="form-group col-6">
                <div class="form-group">
                    <label for="f">{{ $t('ac.f') }}</label>
                    <input type="text" class="form-control" id="f" v-model="selectedPerson.f" :placeholder="$t('ac.f')" :disabled="selectedPerson.id === null" required>
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.f')}) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="i">{{ $t('ac.i') }}</label>
                    <input type="text" class="form-control" id="i" v-model="selectedPerson.i" :placeholder="$t('ac.i')" :disabled="selectedPerson.id === null" required>
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.i')}) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="o">{{ $t('ac.o') }}</label>
                    <input type="text" class="form-control" id="o" v-model="selectedPerson.o" :placeholder="$t('ac.o')" :disabled="selectedPerson.id === null">
                </div>
                <div class="form-group">
                    <label for="birthday">{{ $t('ac.birthday') }}</label>
                    <input type="date" class="form-control" id="birthday" v-model="selectedPerson.birthday" :placeholder="$t('ac.birthday')" required
                           :disabled="selectedPerson.id === null">
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.birthday')}) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="address">{{ $t('ac.address') }}</label>
            <input type="text" class="form-control" id="address" v-model="selectedPerson.address" :placeholder="$t('ac.address')" :disabled="selectedPerson.id === null">
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="phone">{{ $t('ac.phone') }}</label>
                <input type="text" class="form-control" id="phone" v-model="selectedPerson.phone" :placeholder="$t('ac.phone')" :disabled="selectedPerson.id === null">
            </div>
            <div id="ac-menu-card" class="form-group col-6">
                <label for="card">{{ $t('ac.card') }}</label>
                <input type="text" class="form-control" id="card" :placeholder="$t('ac.card')" :disabled="selectedPerson.id === null">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <ac-button-save v-if="selectedPerson.id === 0" @click="save"></ac-button-save>
                <ac-button-cancel v-if="selectedPerson.id === 0" @click="cancel"></ac-button-cancel>
                <ac-button-update v-if="selectedPerson.id > 0" @click="update"></ac-button-update>
                <ac-button-remove v-if="selectedPerson.id > 0" @click="remove"></ac-button-remove>
            </div>
        </div>
    </form>
</template>

<script>
    import {mapState} from 'vuex';
    import AcButtonSave from "./buttons/AcButtonSave";
    import AcButtonCancel from "./buttons/AcButtonCancel";
    import AcButtonUpdate from "./buttons/AcButtonUpdate";
    import AcButtonRemove from "./buttons/AcButtonRemove";

    export default {
        name: "AcFormPerson",
        components: {AcButtonRemove, AcButtonUpdate, AcButtonCancel, AcButtonSave},
        computed: {
            ...mapState({
                selectedPerson: state => state.persons.selected
            })
        },
        methods: {
            save: function () {

            },
            update: function () {

            },
            cancel: function () {

            },
            remove: function () {

            }
        }
    }
</script>

<style scoped>

</style>
