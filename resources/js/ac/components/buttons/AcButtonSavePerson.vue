<template>
    <button
        type="button"
        class="btn btn-primary"
        :disabled="disabled"
        @click="savePerson"
    >
        {{ $t('Сохранить') }}
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
                    this.$store.commit('modal/setTitle', this.$t('Сохранение'))

                    this.$store.commit('modal/setMessage', this.$t('Вы действительно хотите :action?', {
                        action: this.$t('Сохранить').toLowerCase() + ' ' + this.selectedPerson.f + ' ' + this.selectedPerson.i
                    }))

                    this.$store.commit('modal/setAcceptButton', 'savePerson')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
