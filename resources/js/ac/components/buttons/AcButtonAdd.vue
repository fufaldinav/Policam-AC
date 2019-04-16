<template>
    <button type="button" class="list-group-item list-group-item-success" @click="selectPerson">
        {{ $t('ac.add') }}
    </button>
</template>

<script>
    import {Person} from '../../classes';

    export default {
        name: "AcButtonAdd",
        methods: {
            selectPerson: function () {
                let person = new Person({id: 0});
                this.$store.commit('persons/setSelected', person);
            }
        }
    }
</script>

<style scoped>

</style>
