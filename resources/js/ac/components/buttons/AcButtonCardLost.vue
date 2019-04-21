<template>
    <button
        type="button"
        class="btn btn-warning mb-1"
        @click="sendToServer"
    >
        {{ $t('ac.card_lost') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonCardLost",

        methods: {
            sendToServer() {
            }
        }
    }
</script>
