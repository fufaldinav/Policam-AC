<template>
    <button type="button" class="btn btn-danger" @click="removePerson">
        {{ $t('ac.delete') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonRemove",
        methods: {
            removePerson() {
                this.$store.dispatch('persons/removeSelected');
            }
        }
    }
</script>

<style scoped>

</style>
