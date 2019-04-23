<template>
    <button
        type="button"
        class="btn btn-primary mb-1"
        @click="sendToServer"
    >
        <slot>{{ $t('ac.card_forgot') }}</slot>
    </button>
</template>

<script>
    export default {
        name: "AcButtonCardForgot",

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            }
        },

        methods: {
            sendToServer() {
                if (this.$store.state.modal.shown) {
                    let self = this
                    window.axios.post('/util/card_problem', {
                        type: 1,
                        person_id: self.selectedPerson.id
                    }).then(response => {
                        self.$store.dispatch('modal/close')
                        self.$root.alert(response.data)
                    }).catch(error => {
                        if (self.$store.debug === true) console.log(error)
                        self.$store.dispatch('modal/close')
                        self.$root.alert(error, 'danger')
                    })
                } else {
                    this.$store.commit('modal/setTitle', this.$t('ac.card_forgot'))

                    this.$store.commit('modal/setMessage', this.$t('ac.must_confirm_action'))

                    this.$store.commit('modal/setAcceptButton', 'cardForgot')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
