<template>
    <button
        type="button"
        class="btn btn-primary"
        :disabled="disabled"
        @click="updatePerson"
    >
        {{ $t('ac.update') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonUpdate",

        props: ['disabled'],

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            }
        },

        methods: {
            updatePerson() {
                if (this.$store.state.modal.shown) {
                    this.$store.dispatch('persons/updateSelected')
                    this.$store.dispatch('modal/close')
                } else {
                    this.$store.commit('modal/setTitle', this.$t('ac.updating'))

                    this.$store.commit('modal/setMessage', this.$t('ac.do_you_really_want_to', {
                        action: this.$t('ac.update').toLowerCase() + ' ' + this.selectedPerson.f + ' ' + this.selectedPerson.i
                    }))

                    this.$store.commit('modal/setAcceptButton', 'updatePerson')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
