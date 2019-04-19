<template>
    <button
        type="button"
        class="btn btn-primary"
        @click="$emit('ac-update-person')"
    >
        {{ $t('ac.update') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonUpdate"
    }
</script>
