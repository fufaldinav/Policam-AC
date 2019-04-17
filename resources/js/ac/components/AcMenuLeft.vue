<template>
    <div class="d-none d-sm-block col-sm-3 col-xl-2 bg-white px-0 ac-menu">
        <div
            v-if="selectedDivision === null"
            key="list-divisions"
            class="list-group list-group-flush"
        >
            <ac-button-division
                v-for="(division, id) in divisions"
                :key="id"
                :division="division"
            >
            </ac-button-division>
        </div>
        <div
            v-else
            key="list-persons"
            class="list-group list-group-flush"
        >
            <ac-button-back></ac-button-back>
            <ac-button-add></ac-button-add>
            <ac-button-person
                v-for="id in selectedDivision.persons"
                :key="id"
                :person="persons[id]"
            >
            </ac-button-person>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import AcButtonAdd from "./buttons/AcButtonAdd";
    import AcButtonBack from "./buttons/AcButtonBack";
    import AcButtonPerson from "./buttons/AcButtonPerson";
    import AcButtonDivision from "./buttons/AcButtonDivision";

    export default {
        name: "AcMenuLeft",
        components: {AcButtonAdd, AcButtonBack, AcButtonPerson, AcButtonDivision},
        computed: {
            ...mapState({
                divisions: state => state.divisions.collection,
                selectedDivision: state => state.divisions.selected,
                persons: state => state.persons.collection
            })
        }
    }
</script>
