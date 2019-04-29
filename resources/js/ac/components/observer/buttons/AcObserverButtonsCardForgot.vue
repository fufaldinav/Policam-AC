<template>
    <button
        type="button"
        class="btn btn-primary mb-1"
        @click="sendToServer"
    >
        <slot>{{ $t('Забыл карту') }}</slot>
    </button>
</template>

<script>
    export default {
        name: "AcObserverButtonsCardForgot",

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            }
        },

        methods: {
            sendToServer() {
                if (this.$store.state.modal.shown) {
                    window.axios.post('/util/card_problem', {
                        type: 1,
                        person_id: this.selectedPerson.id
                    }).then(response => {
                        this.$store.dispatch('modal/close')
                        this.$root.alert(response.data)
                    }).catch(error => {
                        if (this.$store.debug === true) console.log(error)
                        this.$store.dispatch('modal/close')
                        this.$root.alert(error, 'danger')
                    })
                } else {
                    this.$store.commit('modal/setTitle', this.$t('Забыл карту'))

                    this.$store.commit('modal/setMessage', this.$t('Необходимо подтвердить действие'))

                    this.$store.commit('modal/setAcceptButton', 'cardForgot')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
