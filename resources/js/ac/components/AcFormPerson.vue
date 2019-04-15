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
                <input type="file" class="form-control-file" @change="handleFiles(this.files)" disabled>
            </div>
            <div class="form-group col-6">
                <div class="form-group">
                    <label for="f">{{ $t('ac.f') }}</label>
                    <input type="text" class="form-control" id="f" :placeholder="$t('ac.f')" disabled required>
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.f')}) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="i">{{ $t('ac.i') }}</label>
                    <input type="text" class="form-control" id="i" :placeholder="$t('ac.i')" disabled required>
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.i')}) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="o">{{ $t('ac.o') }}</label>
                    <input type="text" class="form-control" id="o" :placeholder="$t('ac.o')" disabled>
                </div>
                <div class="form-group">
                    <label for="birthday">{{ $t('ac.birthday') }}</label>
                    <input type="date" class="form-control" id="birthday" :placeholder="$t('ac.birthday')" required
                           disabled>
                    <div class="invalid-feedback">
                        {{ $t('ac.required', {field: $t('ac.birthday')}) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="address">{{ $t('ac.address') }}</label>
            <input type="text" class="form-control" id="address" :placeholder="$t('ac.address')" disabled>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="phone">{{ $t('ac.phone') }}</label>
                <input type="text" class="form-control" id="phone" :placeholder="$t('ac.phone')" disabled>
            </div>
            <div id="ac-menu-card" class="form-group col-6">
                <label for="card">{{ $t('ac.card') }}</label>
                <input type="text" class="form-control" id="card" :placeholder="$t('ac.card')" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <ac-button-save @ac-form-save="save"></ac-button-save>
                <ac-button-update @ac-form-update="update"></ac-button-update>
                <ac-button-cancel @ac-form-cancel="cancel"></ac-button-cancel>
                <ac-button-remove @ac-form-remove="remove"></ac-button-remove>
            </div>
        </div>
    </form>
</template>

<script>
    import AcButtonSave from "./buttons/AcButtonSave";
    import AcButtonCancel from "./buttons/AcButtonCancel";
    import AcButtonUpdate from "./buttons/AcButtonUpdate";
    import AcButtonRemove from "./buttons/AcButtonRemove";

    export default {
        name: "AcFormPerson",
        components: {AcButtonRemove, AcButtonUpdate, AcButtonCancel, AcButtonSave},
        props: {
            person: Object
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
