<template xmlns:v-touch="http://www.w3.org/1999/xhtml">
    <div class="container-fluid">
        <div v-if="loading">
            loading...
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
                            <ac-form-person></ac-form-person>
                        </div>
                    </div>
                </div>
            </transition>
            <ac-cp-persons-menu-right></ac-cp-persons-menu-right>
        </div>
    </div>
</template>

<script>
    import AcCpPersonsMenuLeft from './AcCpPersonsMenuLeft'
    import AcCpPersonsMenuRight from './AcCpPersonsMenuRight'
    import AcFormPerson from '../forms/AcFormPerson'

    export default {
        name: "AcCpPersons",

        components: {AcCpPersonsMenuLeft, AcCpPersonsMenuRight, AcFormPerson},

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
            this.$store.dispatch('loader/loadData');
        },

        mounted() {
            axios.get('/controllers/get_list').then(function (response) {
                for (let k in response.data) {
                    window.Echo.private(`controller-events.${response.data[k].id}`)
                        .listen('EventReceived', (e) => {
                            if (e.event === 2 || e.event === 3) {
                                this.$store.commit('cards/setLast', e.card);
                            }
                        })
                        .listen('ControllerConnected', (e) => {
                            console.log(e.controller_id); //TODO delete
                        });
                }
            }).catch(function (error) {
                this.$root.alert(error, 'danger');
                console.log(error);
            });
        },
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
