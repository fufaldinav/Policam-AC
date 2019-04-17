<template>
    <button type="button" class="btn btn-primary" @click="updatePerson">
        {{ $t('ac.update') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonUpdate",
        methods: {
            updatePerson() {
                this.$store.dispatch('persons/updateSelected');
            }
        }
    }
</script>

<style scoped>

</style>
