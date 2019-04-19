<template>
    <div
        id="ac-form-modal"
        class="modal fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="ac-modal-label"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-dialog-centered"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5
                        id="ac-modal-label"
                        class="modal-title"
                    >
                        {{ title }}
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ message }}
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        {{ $t('ac.cancel') }}
                    </button>
                    <ac-button-save
                        v-if="buttonType === 'save'"
                        @ac-person-save="savePerson"
                    >
                    </ac-button-save>
                    <ac-button-update
                        v-if="buttonType === 'update'"
                        @ac-person-update="updatePerson"
                    >
                    </ac-button-update>
                    <ac-button-remove
                        v-if="buttonType === 'remove'"
                        @ac-person-remove="removePerson"
                    >
                    </ac-button-remove>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AcButtonSave from '../buttons/AcButtonSave';
    import AcButtonUpdate from '../buttons/AcButtonUpdate';
    import AcButtonRemove from '../buttons/AcButtonRemove';

    export default {
        name: "AcFormModal",

        components: {AcButtonSave, AcButtonRemove, AcButtonUpdate},

        props: {
            title: String,
            message: String,
            buttonType: String
        },

        data() {
            return {}
        },

        methods: {
            savePerson() {
                this.$store.dispatch('persons/saveSelected');
                $('#ac-form-modal').modal('hide');
            },

            updatePerson() {
                this.$store.dispatch('persons/updateSelected');
                $('#ac-form-modal').modal('hide');
            },

            removePerson() {
                this.$store.dispatch('persons/removeSelected');
                $('#ac-form-modal').modal('hide');
            },
        }
    }
</script>

<style scoped>

</style>
