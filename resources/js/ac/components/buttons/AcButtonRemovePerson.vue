<template>
    <button
        type="button"
        class="btn btn-danger"
        @click="removePerson"
    >
        {{ $t('ac.delete') }}
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
                } else {
                    this.$store.commit('modal/setTitle', this.$t('ac.deleting'))

                    this.$store.commit('modal/setMessage', this.$t('ac.do_you_really_want_to', {
                        action: this.$t('ac.delete').toLowerCase() + ' ' + this.selectedPerson.f + ' ' + this.selectedPerson.i
                    }))

                    this.$store.commit('modal/setAcceptButton', 'removePerson')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
