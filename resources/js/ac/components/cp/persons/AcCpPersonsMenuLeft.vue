<template>
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
                class="list-group list-group-flush"
            >
                <ac-buttons-division
                    v-for="(division, id) in divisions"
                    :key="id"
                    :division="division"
                >
                </ac-buttons-division>
            </div>
            <div
                v-else
                class="list-group list-group-flush"
            >
                <ac-buttons-back></ac-buttons-back>
                <ac-cp-persons-buttons-add></ac-cp-persons-buttons-add>
                <ac-buttons-person
                    v-for="id in selectedDivisionPersons"
                    :key="id"
                    :person="persons[id]"
                >
                </ac-buttons-person>
            </div>
        </div>
    </transition>
</template>

<script>
    import AcCpPersonsButtonsAdd from './buttons/AcCpPersonsButtonsAdd'
    import AcButtonsBack from '../../buttons/AcButtonsBack'
    import AcButtonsPerson from '../../buttons/AcButtonsPerson'
    import AcButtonsDivision from '../../buttons/AcButtonsDivision'

    export default {
        name: "AcCpPersonsMenuLeft",

        components: {AcCpPersonsButtonsAdd, AcButtonsBack, AcButtonsPerson, AcButtonsDivision},

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

            selectedDivision() {
                return this.$store.state.divisions.selected
            },

            selectedDivisionPersons() {
                return this.$store.getters['divisions/selectedSortedPersons']
            },

            persons() {
                return this.$store.state.persons.collection
            },

            breakpoint() {
                return this.$store.state.bp.current
            }
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
