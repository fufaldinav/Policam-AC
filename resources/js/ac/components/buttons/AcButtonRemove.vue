<template>
    <button
        type="button"
        class="btn btn-danger"
        @click="$emit('ac-person-remove')"
    >
        {{ $t('ac.delete') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonRemove"
    }
</script>
