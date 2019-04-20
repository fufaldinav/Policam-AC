<template>
    <button
        type="button"
        class="btn btn-primary"
        :disabled="disabled"
        @click="savePerson"
    >
        {{ $t('ac.save') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonSave",

        props: ['disabled'],

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            }
        },

        methods: {
            savePerson() {
                if (this.$store.state.modal.shown) {
                    this.$store.dispatch('persons/saveSelected')
                    this.$store.dispatch('modal/close')
                    this.$store.commit('cp/showLeftMenu')
                } else {
                    this.$store.commit('modal/setTitle', this.$t('ac.saving'))

                    this.$store.commit('modal/setMessage', this.$t('ac.do_you_really_want_to', {
                        action: this.$t('ac.save').toLowerCase() + ' ' + this.selectedPerson.f + ' ' + this.selectedPerson.i
                    }))

                    this.$store.commit('modal/setAcceptButton', 'savePerson')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
