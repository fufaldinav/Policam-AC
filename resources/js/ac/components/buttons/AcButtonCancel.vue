<template>
    <button
        type="button"
        class="btn btn-secondary"
        @click="unsetSelectedPerson"
    >
        {{ $t('ac.cancel') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonCancel",
        methods: {
            unsetSelectedPerson() {
                this.$store.commit('persons/setSelected');
            }
        }
    }
</script>
