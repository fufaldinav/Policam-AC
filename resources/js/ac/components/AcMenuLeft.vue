<template>
    <div class="d-none d-sm-block col-sm-3 col-xl-2 bg-white px-0 ac-menu">
        <div class="list-group list-group-flush" v-if="currentDivision === null">
            <ac-button-division v-for="division in divisions" :key="division.id" :division="division"
                                @ac-division-changed="setCurrentDivision"></ac-button-division>
        </div>
        <div class="list-group list-group-flush" v-else>
            <ac-button-back @ac-division-changed="setCurrentDivision"></ac-button-back>
            <ac-button-add></ac-button-add>
            <ac-button-person v-for="person in divisions[currentDivision].persons()"
                              :key="persons[person].id" :person="persons[person]"></ac-button-person>
        </div>
    </div>
</template>

<script>
    import AcButtonAdd from "./buttons/AcButtonAdd";
    import AcButtonBack from "./buttons/AcButtonBack";
    import AcButtonPerson from "./buttons/AcButtonPerson";
    import AcButtonDivision from "./buttons/AcButtonDivision";

    export default {
        name: "AcMenuLeft",
        components: {
            AcButtonAdd,
            AcButtonBack,
            AcButtonPerson,
            AcButtonDivision
        },
        props: {
            currentDivision: Number,
            divisions: Object,
            persons: Object
        },
        methods: {
            setCurrentDivision: function (id) {
                this.$emit('ac-division-changed', id);
            }
        }
    }
</script>
