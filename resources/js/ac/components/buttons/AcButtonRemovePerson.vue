<template>
    <button
        type="button"
        class="btn btn-danger"
        @click="removePerson"
    >
        {{ $t('Удалить') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonRemove",

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            }
        },

        methods: {
            removePerson() {
                if (this.$store.state.modal.shown) {
                    this.$store.dispatch('persons/removeSelected')
                    this.$store.dispatch('modal/close')
                    this.$store.commit('cp/showLeftMenu')
                } else {
                    this.$store.commit('modal/setTitle', this.$t('Удаление'))

                    this.$store.commit('modal/setMessage', this.$t('Вы действительно хотите удалить ' + this.selectedPerson.f + ' ' + this.selectedPerson.i + '?'))

                    this.$store.commit('modal/setAcceptButton', 'removePerson')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
