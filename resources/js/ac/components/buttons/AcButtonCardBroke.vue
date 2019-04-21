<template>
    <button
        type="button"
        class="btn btn-danger mb-1"
        @click="sendToServer"
    >
        {{ $t('ac.card_broke') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonCardBroke",

        methods: {
            sendToServer() {
            }
        }
    }
</script>
