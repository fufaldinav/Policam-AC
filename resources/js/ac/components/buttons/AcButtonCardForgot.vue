<template>
    <button
        type="button"
        class="btn btn-primary mb-1"
        @click="sendToServer"
    >
        {{ $t('ac.card_forgot') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonCardForgot",

        methods: {
            sendToServer() {
            }
        }
    }
</script>
