<template>
    <button
        class="btn btn-danger"
        :class="buttonClass"
        type="button"
        @click="deleteDivision"
    >
        Удалить
    </button>
</template>

<script>
    export default {
        name: "AcCpClassesButtonsRemove",

        props: {
            buttonClass: {
                type: String
            },

            division: {
                type: Object
            }
        },

        methods: {
            deleteDivision() {
                if (this.$store.state.modal.shown) {
                    this.$store.dispatch('divisions/removeSelected')

                    this.$store.dispatch('modal/close')
                } else {
                    this.$store.commit('divisions/setSelected', this.division)

                    this.$store.commit('modal/setTitle', 'Удаление')

                    this.$store.commit('modal/setMessage', 'Вы действительно хотите удалить подразделение? Все члены подразделения будут перемещены в "пустое" подразделение.')

                    this.$store.commit('modal/setAcceptButton', 'removeDivision')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>

<style scoped>

</style>
