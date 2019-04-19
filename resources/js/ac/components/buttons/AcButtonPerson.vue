<template>
    <button
        type="button"
        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
        @click="selectPerson"
    >
        {{ person.f }} {{ person.i }}
        <span v-if="noCards" class="badge badge-pill" :class="{'badge-danger': noCards}">
            !
        </span>
    </button>
</template>

<script>
    import {Person} from '../../classes';

    export default {
        name: "AcButtonPerson",

        props: {
            person: Object
        },

        computed: {
            noCards() {
                return this.person.cards.length === 0;
            }
        },

        methods: {
            selectPerson: function () {
                this.$store.commit('persons/setSelected', new Person(this.person));
            }
        }
    }
</script>
