<template>
    <button
        class="btn btn-secondary"
        :class="buttonClass"
        type="button"
        @click="clearSelected"
    >
        Отмена
    </button>
</template>

<script>
    export default {
        name: "AcCpClassesButtonsCancel",

        props: {
            buttonClass: {
                type: String
            }
        },

        methods: {
            clearSelected() {
                if (this.$store.state.modal.shown) {
                    this.$store.dispatch('modal/close')
                }
                this.$store.commit('divisions/clearSelected')
            }
        }
    }
</script>

<style scoped>

</style>
