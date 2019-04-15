<template>
    <button type="button" class="list-group-item list-group-item-info"  @click="$emit('ac-division-changed', null)">
        {{ $t('ac.back') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonBack"
    }
</script>

<style scoped>

</style>
