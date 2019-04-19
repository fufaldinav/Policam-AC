<template>
    <button
        type="button"
        class="btn btn-primary"
        @click="$emit('ac-save-person')"
    >
        {{ $t('ac.save') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonSave"
    }
</script>
