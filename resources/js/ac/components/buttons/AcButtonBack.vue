<template>
    <button
        type="button"
        class="list-group-item list-group-item-info"
        @click="unselectDivisionAndPerson"
    >
        {{ $t('ac.back') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonBack",

        methods: {
            unselectDivisionAndPerson() {
                this.$store.commit('divisions/clearSelected')
                this.$store.commit('persons/clearSelected')
            }
        }
    }
</script>
