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
                    <ac-observer-buttons-card-forgot v-if="this.$store.state.modal.acceptButton === 'cardForgot'">Подтвердить</ac-observer-buttons-card-forgot>
                    <ac-observer-buttons-card-lost v-if="this.$store.state.modal.acceptButton === 'cardLost'">Подтвердить</ac-observer-buttons-card-lost>
                    <ac-observer-buttons-card-broke v-if="this.$store.state.modal.acceptButton === 'cardBroke'">Подтвердить</ac-observer-buttons-card-broke>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AcObserverButtonsCardBroke from './buttons/AcObserverButtonsCardBroke'
    import AcObserverButtonsCardForgot from './buttons/AcObserverButtonsCardForgot'
    import AcObserverButtonsCardLost from './buttons/AcObserverButtonsCardLost'

    export default {
        name: "AcObserverModal",

        components: {
            AcObserverButtonsCardBroke,
            AcObserverButtonsCardForgot,
            AcObserverButtonsCardLost
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
