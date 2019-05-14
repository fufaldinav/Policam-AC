<template>
    <button
        type="button"
        class="btn btn-danger"
        @click="removeCard"
    >
        <slot>{{ $t('Удалить') }}</slot>
    </button>
</template>

<script>
    export default {
        name: "AcButtonsRemoveCard",

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

                    this.$store.commit('modal/setTitle', this.$t('Удаление'))

                    this.$store.commit('modal/setMessage', this.$t('Вы действительно хотите удалить карту?'))

                    this.$store.commit('modal/setAcceptButton', 'removeCard')

                    this.$store.dispatch('modal/show')
                }
            },
        }
    }
</script>
