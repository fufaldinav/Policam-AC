<template>
    <button type="button" class="btn btn-primary" @click="$emit('ac-form-update')">
        {{ $t('ac.update') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonUpdate"
    }
</script>

<style scoped>

</style>
