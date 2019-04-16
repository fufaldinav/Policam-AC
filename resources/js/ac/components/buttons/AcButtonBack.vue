<template>
    <button type="button" class="list-group-item list-group-item-info"  @click="selectDivision">
        {{ $t('ac.back') }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonBack",
        methods: {
            selectDivision() {
                this.$store.commit('divisions/setSelected');
                this.$store.commit('persons/setSelected');
            }
        }
    }
</script>

<style scoped>

</style>
