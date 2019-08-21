<template>
    <button
        class="btn btn-primary"
        :class="buttonClass"
        type="button"
        :disabled="buttonDisabled"
        @click="updateDivision"
    >
        Сохранить
    </button>
</template>

<script>
    export default {
        name: "AcCpClassesButtonsSave",

        props: {
            buttonClass: {
                type: String
            },

            buttonDisabled: {
                type: Boolean
            }
        },

        methods: {
            updateDivision() {
                if (this.$store.state.modal.shown) {
                    this.$store.dispatch('divisions/updateSelected')

                    this.$store.dispatch('modal/close')
                } else {
                    this.$store.commit('modal/setTitle', 'Сохранение')

                    this.$store.commit('modal/setMessage', 'Вы действительно хотите сохранить подразделение?')

                    this.$store.commit('modal/setAcceptButton', 'updateDivision')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>

<style scoped>

</style>
