<template>
    <button type="button"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            :disabled="count == 0" @click="$emit('ac-division-changed', division.id)">
        <slot></slot>
        <span class="badge badge-primary badge-pill">{{ count }}</span>
    </button>
</template>

<script>
    export default {
        name: "AcButtonDivision",
        props: {
            division: Object
        },
        data: function () {
            return {
                count: this.division.persons().length
            }
        }
    }
</script>

<style scoped>

</style>
