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
                        {{ $t('Отмена') }}
                    </button>
                    <ac-buttons-save-person v-if="this.$store.state.modal.acceptButton === 'savePerson'">
                    </ac-buttons-save-person>
                    <ac-buttons-update-person v-if="this.$store.state.modal.acceptButton === 'updatePerson'">
                    </ac-buttons-update-person>
                    <ac-buttons-remove-person v-if="this.$store.state.modal.acceptButton === 'removePerson'">
                    </ac-buttons-remove-person>
                    <ac-buttons-remove-card v-if="this.$store.state.modal.acceptButton === 'removeCard'">
                    </ac-buttons-remove-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AcButtonsRemoveCard from '../../../buttons/AcButtonsRemoveCard'
    import AcButtonsRemovePerson from '../../../buttons/AcButtonsRemovePerson'
    import AcButtonsSavePerson from '../../../buttons/AcButtonsSavePerson'
    import AcButtonsUpdatePerson from '../../../buttons/AcButtonsUpdatePerson'

    export default {
        name: "AcCpPersonsFormsModal",

        components: {
            AcButtonsRemoveCard,
            AcButtonsRemovePerson,
            AcButtonsSavePerson,
            AcButtonsUpdatePerson
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
