<template>
    <button
        type="button"
        class="btn btn-danger mb-1"
        @click="sendToServer"
    >
        <slot>{{ $t('ac.card_broke') }}</slot>
    </button>
</template>

<script>
    export default {
        name: "AcButtonCardBroke",

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            }
        },

        methods: {
            sendToServer() {
                if (this.$store.state.modal.shown) {
                    window.axios.post('/util/card_problem', {
                        type: 3,
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
                    this.$store.commit('modal/setTitle', this.$t('ac.card_broke'))

                    this.$store.commit('modal/setMessage', this.$t('ac.must_confirm_action'))

                    this.$store.commit('modal/setAcceptButton', 'cardBroke')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
