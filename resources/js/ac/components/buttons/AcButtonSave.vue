<template>
    <button type="button" class="btn btn-primary" @click="$emit('ac-form-save')">
        {{ $t('ac.save') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonSave"
    }
</script>

<style scoped>

</style>
