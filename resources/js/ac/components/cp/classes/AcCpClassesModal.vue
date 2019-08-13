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
                        Отмена
                    </button>
                    <ac-cp-classes-buttons-add v-if="this.$store.state.modal.acceptButton === 'saveDivision'">'Сохранить</ac-cp-classes-buttons-add>
                    <ac-cp-classes-buttons-remove v-if="this.$store.state.modal.acceptButton === 'removeDivision'">Удалить</ac-cp-classes-buttons-remove>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AcCpClassesButtonsAdd from './buttons/AcCpClassesButtonsAdd'
    import AcCpClassesButtonsRemove from './buttons/AcCpClassesButtonsRemove'

    export default {
        name: "AcCpClassesModal",

        components: {
            AcCpClassesButtonsAdd,
            AcCpClassesButtonsRemove
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
