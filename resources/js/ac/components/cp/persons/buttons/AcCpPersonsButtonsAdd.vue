<template>
    <button
        type="button"
        class="list-group-item list-group-item-success"
        @click="selectPerson"
    >
        {{ $t('Добавить') }}
    </button>
</template>

<script>
    import {Person} from '../../../../classes'

    export default {
        name: "AcCpPersonsButtonsAdd",

        methods: {
            selectPerson() {
                let division = this.$store.state.divisions.selected
                let person = new Person({id: 0, divisions: [division.id]})
                this.$store.commit('persons/setSelected', person)
                this.$store.commit('cp/showForm')
            }
        }
    }
</script>
