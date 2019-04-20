<template>
    <button
        type="button"
        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
        @click="selectDivision"
    >
        {{ division.name }}
        <span
            class="badge badge-pill"
            :class="[count ? 'badge-primary' : 'badge-warning']"
        >
            {{ count }}
        </span>
    </button>
</template>

<script>
    import {Person} from '../../classes'

    export default {
        name: "AcButtonDivision",

        props: {
            division: Object
        },

        computed: {
            count() {
                return this.division.persons.length
            }
        },

        methods: {
            selectDivision() {
                if (this.count === 0) {
                    let person = new Person({id: 0, divisions: [this.division.id]})
                    this.$store.commit('persons/setSelected', person)
                    this.$store.commit('cp/showForm')
                } else {
                    this.$store.commit('divisions/setSelected', this.division)
                }
            }
        }
    }
</script>
