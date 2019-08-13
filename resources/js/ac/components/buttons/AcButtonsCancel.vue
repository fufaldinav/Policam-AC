<template>
    <button
        type="button"
        class="btn btn-secondary"
        @click="clearSelectedPerson"
    >
        Отмена
    </button>
</template>

<script>
    export default {
        name: "AcButtonsCancel",

        methods: {
            clearSelectedPerson() {
                this.$store.commit('persons/clearSelected')
                this.$store.commit('cp/showLeftMenu')
            }
        }
    }
</script>
