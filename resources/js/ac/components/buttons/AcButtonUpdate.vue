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
                this.$store.dispatch('persons/update');
            }
        }
    }
</script>

<style scoped>

</style>
