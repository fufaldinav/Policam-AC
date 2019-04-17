<template>
    <button
        type="button"
        class="list-group-item list-group-item-action"
        @click="selectPerson"
    >
        {{ person.f }} {{ person.i }}
    </button>
</template>

<script>
    import {Person} from '../../classes';

    export default {
        name: "AcButtonPerson",
        props: {
            person: Object
        },
        methods: {
            selectPerson: function () {
                this.$store.commit('persons/setSelected', new Person(this.person));
            }
        }
    }
</script>
