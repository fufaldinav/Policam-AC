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
                    <ac-button-card-forgot v-if="this.$store.state.modal.acceptButton === 'cardForgot'">{{ $t('ac.accept') }}</ac-button-card-forgot>
                    <ac-button-card-lost v-if="this.$store.state.modal.acceptButton === 'cardLost'">{{ $t('ac.accept') }}</ac-button-card-lost>
                    <ac-button-card-broke v-if="this.$store.state.modal.acceptButton === 'cardBroke'">{{ $t('ac.accept') }}</ac-button-card-broke>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AcButtonCardBroke from '../buttons/AcButtonCardBroke'
    import AcButtonCardForgot from '../buttons/AcButtonCardForgot'
    import AcButtonCardLost from '../buttons/AcButtonCardLost'

    export default {
        name: "AcFormModal",

        components: {
            AcButtonCardBroke,
            AcButtonCardForgot,
            AcButtonCardLost
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
