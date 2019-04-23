<template>
    <button
        type="button"
        class="btn btn-danger"
        @click="removeCard"
    >
        <slot>{{ $t('ac.delete') }}</slot>
    </button>
</template>

<script>
    export default {
        name: "AcButtonRemoveCard",

        props: ['removableCard'],

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            }
        },

        methods: {
            removeCard() {
                if (this.$store.state.modal.shown) {
                    this.$store.commit('persons/removeCard', this.$store.state.cards.removable)
                    this.$store.dispatch('modal/close')
                } else {
                    this.$store.commit('cards/setRemovable', this.removableCard)

                    this.$store.commit('modal/setTitle', this.$t('ac.deleting'))

                    this.$store.commit('modal/setMessage', this.$t('ac.do_you_really_want_to', {
                        action: this.$t('ac.delete').toLowerCase()
                    }))

                    this.$store.commit('modal/setAcceptButton', 'removeCard')

                    this.$store.dispatch('modal/show')
                }
            },
        }
    }
</script>
