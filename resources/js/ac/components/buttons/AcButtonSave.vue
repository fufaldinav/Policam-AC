<template>
    <button type="button" class="btn btn-primary" @click="savePerson">
        {{ $t('ac.save') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonSave",
        methods: {
            savePerson() {
                this.$store.dispatch('persons/add');
            }
        }
    }
</script>

<style scoped>

</style>
