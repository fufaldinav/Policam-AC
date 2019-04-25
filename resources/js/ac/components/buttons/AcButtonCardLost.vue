<template>
    <button
        type="button"
        class="btn btn-warning mb-1"
        @click="sendToServer"
    >
        <slot>{{ $t('Потерял карту') }}</slot>
    </button>
</template>

<script>
    export default {
        name: "AcButtonCardLost",

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
                    this.$store.commit('modal/setTitle', this.$t('Потерял карту'))

                    this.$store.commit('modal/setMessage', this.$t('Необходимо подтвердить действие'))

                    this.$store.commit('modal/setAcceptButton', 'cardLost')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
