<template xmlns:v-touch="http://www.w3.org/1999/xhtml">
    <transition
        name="ac-menu-slide"
        enter-class="ac-menu-slide-enter"
        enter-active-class="ac-menu-slide-enter-active"
    >
        <div
            v-if="leftMenuShown"
            class="d-sm-block col-sm-3 col-xl-2 bg-white px-0 ac-menu ac-menu-left"
            :class="displayNone"
            v-touch:swipe.left="showForm"
        >
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
                    v-for="id in selectedDivisionPersons"
                    :key="id"
                    :person="persons[id]"
                >
                </ac-button-person>
            </div>
        </div>
    </transition>
</template>

<script>
    import {mapState} from 'vuex'
    import AcButtonAdd from '../../buttons/AcButtonAdd'
    import AcButtonBack from '../../buttons/AcButtonBack'
    import AcButtonPerson from '../../buttons/AcButtonPerson'
    import AcButtonDivision from '../../buttons/AcButtonDivision'

    export default {
        name: "AcCpPersonsMenuLeft",

        components: {AcButtonAdd, AcButtonBack, AcButtonPerson, AcButtonDivision},

        computed: {
            leftMenuShown() {
                return (this.$store.state.cp.leftMenuShown === true) || (this.breakpoint !== 'xs')
            },

            displayNone() {
                return {
                    'd-none': ! this.leftMenuShown
                }
            },

            divisions() {
                return this.$store.getters['divisions/sorted']
            },

            selectedDivisionPersons() {
                return this.$store.getters['divisions/selectedSortedPersons']
            },

            ...mapState({
                selectedDivision: state => state.divisions.selected,
                persons: state => state.persons.collection,
                breakpoint: state => state.bp.current
            })
        },

        methods: {
            showForm() {
                if (this.breakpoint !== 'xs') return
                this.$store.commit('cp/showForm')
            }
        },
    }
</script>

<style scoped>
    .ac-menu-slide-enter-active {
        transition: all .3s ease;
    }

    .ac-menu-slide-enter {
        transform: translateX(-100vw);
        opacity: 0;
    }
</style>
