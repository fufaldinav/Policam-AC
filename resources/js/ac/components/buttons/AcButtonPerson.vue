<template>
    <button type="button" class="list-group-item list-group-item-action" @click="selectPerson">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: "AcButtonPerson",
        props: {
            person: Object
        },
        methods: {
            selectPerson: function () {
                console.log(this.person.id);
            }
        }
    }
</script>

<style scoped>

</style>
