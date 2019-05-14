<template>
    <button
        type="button"
        class="list-group-item list-group-item-info"
        @click="unselectDivisionAndPerson"
    >
        {{ $t('Назад') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonsBack",

        methods: {
            unselectDivisionAndPerson() {
                this.$store.commit('divisions/clearSelected')
                this.$store.commit('persons/clearSelected')
            }
        }
    }
</script>
