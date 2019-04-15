<template>
    <button type="button" class="btn btn-danger" @click="$emit('ac-form-remove')">
        {{ $t('ac.delete') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonRemove"
    }
</script>

<style scoped>

</style>
