<template>
    <button
        type="button"
        class="btn btn-secondary"
        @click="clearSelectedPerson"
    >
        {{ $t('ac.cancel') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonCancel",

        methods: {
            clearSelectedPerson() {
                this.$store.commit('persons/clearSelected')
                this.$store.commit('cp/showLeftMenu')
            }
        }
    }
</script>
