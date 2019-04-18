<template>
    <form
        class="needs-validation"
        novalidate
    >
        <div class="form-row">
            <div class="form-group col-6">
                <ac-form-person-photo></ac-form-person-photo>
                <input
                    v-if="selectedPerson.id !== null"
                    type="file"
                    class="form-control-file"
                    @change="uploadPhoto($event.target.files)"
                >
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
                <ac-button-save v-if="selectedPerson.id === 0"></ac-button-save>
                <ac-button-cancel v-if="selectedPerson.id === 0"></ac-button-cancel>
                <ac-button-update v-if="selectedPerson.id > 0"></ac-button-update>
                <ac-button-remove v-if="selectedPerson.id > 0"></ac-button-remove>
            </div>
        </div>
    </form>
</template>

<script>
    import AcFormCards from './AcFormPersonCards';
    import AcFormLastCard from './AcFormPersonLastCard';
    import AcFormPersonPhoto from './AcFormPersonPhoto';
    import AcButtonSave from '../buttons/AcButtonSave';
    import AcButtonCancel from '../buttons/AcButtonCancel';
    import AcButtonUpdate from '../buttons/AcButtonUpdate';
    import AcButtonRemove from '../buttons/AcButtonRemove';

    export default {
        name: "AcFormPerson",
        components: {
            AcFormPersonPhoto,
            AcFormCards, AcFormLastCard, AcButtonRemove, AcButtonUpdate, AcButtonCancel, AcButtonSave},
        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected;
            }
        },
        methods: {
            uploadPhoto(files) {
                let formData = new FormData();
                formData.append('file', files[0]);

                let self = this;

                window.axios({
                    method: 'post',
                    url: '/photos/save',
                    data: formData,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                }).then(function (response) {
                    self.$store.commit('persons/addPhoto', response.data);
                }).catch(function (error) {
                    console.log(error);
                })
            }
        }
    }
</script>
