<template>
    <button
        class="btn btn-warning"
        :class="buttonClass"
        type="button"
        @click="setSelected"
    >
        Редактировать
    </button>
</template>

<script>
    import {Division} from '../../../../classes'

    export default {
        name: "AcCpClassesButtonsEdit",

        props: {
            buttonClass: {
                type: String
            },

            division: {
                type: Object,
                required: true
            }
        },

        methods: {
            setSelected() {
                this.$store.commit('divisions/setSelected', new Division(this.division))
            }
        }
    }
</script>

<style scoped>

</style>
