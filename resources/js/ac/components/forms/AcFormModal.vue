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
                    <ac-button-save-person v-if="this.$store.state.modal.acceptButton === 'savePerson'">
                    </ac-button-save-person>
                    <ac-button-update-person v-if="this.$store.state.modal.acceptButton === 'updatePerson'">
                    </ac-button-update-person>
                    <ac-button-remove-person v-if="this.$store.state.modal.acceptButton === 'removePerson'">
                    </ac-button-remove-person>
                    <ac-button-remove-card v-if="this.$store.state.modal.acceptButton === 'removeCard'">
                    </ac-button-remove-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AcButtonRemoveCard from '../buttons/AcButtonRemoveCard'
    import AcButtonRemovePerson from '../buttons/AcButtonRemovePerson'
    import AcButtonSavePerson from '../buttons/AcButtonSavePerson'
    import AcButtonUpdatePerson from '../buttons/AcButtonUpdatePerson'

    export default {
        name: "AcFormModal",

        components: {
            AcButtonRemoveCard,
            AcButtonRemovePerson,
            AcButtonSavePerson,
            AcButtonUpdatePerson
        },

        computed: {
            title() {
                return this.$store.state.modal.title
            },

            message() {
                return this.$store.state.modal.message
            }
        },

        mounted() {
            $(this.$store.state.modal.id).on('show.bs.modal', () => {
                this.$store.commit('modal/setShown')
            })
            $(this.$store.state.modal.id).on('hide.bs.modal', () => {
                this.$store.commit('modal/setHidden')
            })
        }
    }
</script>
