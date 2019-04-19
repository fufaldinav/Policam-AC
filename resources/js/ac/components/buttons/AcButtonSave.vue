<template>
    <button
        type="button"
        class="btn btn-primary"
        @click="$emit('ac-person-save')"
    >
        {{ $t('ac.save') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonSave"
    }
</script>
