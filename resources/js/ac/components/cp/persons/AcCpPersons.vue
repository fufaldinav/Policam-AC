<template>
    <div class="container-fluid">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
            class="row"
        >
            <ac-cp-persons-menu-left></ac-cp-persons-menu-left>
            <transition
                name="ac-form-slide"
                enter-class="ac-form-slide-enter"
                enter-active-class="ac-form-slide-enter-active"
            >
                <div
                    v-if="formShown"
                    class="d-sm-block col-12 col-sm-9 col-lg-6 col-xl-7"
                    :class="displayNone"
                    v-touch:swipe.right="toggleLeftMenu"
                >
                    <div class="row mt-4">
                        <div class="container-fluid">
                            <ac-cp-persons-forms-person></ac-cp-persons-forms-person>
                        </div>
                    </div>
                </div>
            </transition>
            <ac-cp-persons-menu-right></ac-cp-persons-menu-right>
        </div>
    </div>
</template>

<script>
    import AcLoading from '../../AcLoading'
    import AcCpPersonsMenuLeft from './AcCpPersonsMenuLeft'
    import AcCpPersonsMenuRight from './AcCpPersonsMenuRight'
    import AcCpPersonsFormsPerson from './forms/AcCpPersonsFormsPerson'

    export default {
        name: "AcCpPersons",

        components: {AcLoading, AcCpPersonsMenuLeft, AcCpPersonsMenuRight, AcCpPersonsFormsPerson},

        computed: {
            loading() {
                return this.$store.state.loader.loading
            },

            breakpoint() {
                return this.$store.state.bp.current
            },

            formShown() {
                return (this.$store.state.cp.leftMenuShown === false) || (this.breakpoint !== 'xs')
            },

            displayNone() {
                return {
                    'd-none': ! this.formShown
                }
            }
        },

        methods: {
            toggleLeftMenu() {
                if (this.breakpoint !== 'xs') return
                if (this.formShown) {
                    this.$store.commit('cp/showLeftMenu')
                } else {
                    this.$store.commit('cp/showForm')
                }
            }
        },

        created() {
            this.$bus.$on('OrgSelected', orgId => {
                this.$store.commit('persons/clearSelected')
                this.$store.commit('divisions/clearSelected')
                this.$store.dispatch('messenger/unsubscribe')
                this.$store.dispatch('messenger/subscribe')
                this.$store.dispatch('loader/loadDivisions', this.$store.state.personsMustBeLoaded)
            })
        },

        mounted() {
            this.$bus.$on('EventReceived', e => {
                if (e.event.event === 2 || e.event.event === 3) {
                    // if (this.$store.state.cards.manualInput === false ){
                    //     this.$store.commit('cards/setLast', e.card)
                    // }
                }
            })
            this.$bus.$on('ControllerConnected', e => {
                if (this.$store.state.debug) console.log('Контроллер ID: ' + e.controller_id + ' вышел на связь')
            })
            this.$bus.$on('PingReceived', e => {
                if (this.$store.state.debug) console.log('Контроллер ID: ' + e.controller_id + ', devices: ' + e.devices)
            })
            this.$bus.$on('OrganizationChanged', e => {
                console.log(e)
            })
        },

        beforeDestroy() {
            this.$bus.$off('EventReceived')
            this.$bus.$off('ControllerConnected')
            this.$bus.$off('PingReceived')
            this.$bus.$off('OrgSelected')
        }
    }
</script>

<style scoped>
    .ac-form-slide-enter-active {
        transition: all .3s ease;
    }

    .ac-form-slide-enter {
        transform: translateX(100vw);
        opacity: 0;
    }
</style>
