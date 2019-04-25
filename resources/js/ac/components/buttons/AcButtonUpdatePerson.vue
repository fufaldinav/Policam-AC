<template>
    <button
        type="button"
        class="btn btn-primary"
        :disabled="disabled"
        @click="updatePerson"
    >
        {{ $t('Сохранить') }}
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
                    this.$store.commit('cp/showLeftMenu')
                } else {
                    this.$store.commit('modal/setTitle', this.$t('Сохранение'))

                    this.$store.commit('modal/setMessage', this.$t('Вы действительно хотите сохранить ' + this.selectedPerson.f + ' ' + this.selectedPerson.i + '?'))

                    this.$store.commit('modal/setAcceptButton', 'updatePerson')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
