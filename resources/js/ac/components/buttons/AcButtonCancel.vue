<template>
    <button type="button" class="btn btn-secondary" @click="$emit('ac-form-cancel')">
        {{ $t('ac.cancel') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonCancel"
    }
</script>

<style scoped>

</style>
