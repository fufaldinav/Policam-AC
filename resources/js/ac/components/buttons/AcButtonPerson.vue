<template>
    <button type="button" class="list-group-item list-group-item-action" @click="123">
        {{ person.f }} {{ person.i }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonPerson",
        props: {
            person: Object
        }
    }
</script>

<style scoped>

</style>
