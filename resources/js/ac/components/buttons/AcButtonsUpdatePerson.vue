<template>
    <button
        type="button"
        class="btn btn-primary"
        :disabled="disabled"
        @click="updatePerson"
    >
        Сохранить
    </button>
</template>

<script>
    export default {
        name: "AcButtonsUpdatePerson",

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
                    this.$store.commit('modal/setTitle', 'Сохранение')

                    this.$store.commit('modal/setMessage', `Вы действительно хотите сохранить ${this.selectedPerson.f} ${this.selectedPerson.i}?`)

                    this.$store.commit('modal/setAcceptButton', 'updatePerson')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>
