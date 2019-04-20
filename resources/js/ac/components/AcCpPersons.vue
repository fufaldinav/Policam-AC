<template xmlns:v-touch="http://www.w3.org/1999/xhtml">
    <div>
        <div v-if="loading">
            loading...
        </div>
        <div v-else>
            <div class="container-fluid">
                <div class="row">
                    <ac-menu-left></ac-menu-left>
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
                    <ac-menu-right></ac-menu-right>
                    <input id="type" name="type" type="text" hidden readonly>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AcMenuLeft from './AcMenuLeft'
    import AcMenuRight from './AcMenuRight'
    import AcFormPerson from './forms/AcFormPerson'

    export default {
        name: "AcCpPersons",

        components: {AcMenuLeft, AcMenuRight, AcFormPerson},

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
            },

            buttonPosition() {
                if (this.formShown) {
                    return 'ac-menu-button-left'
                } else {
                    return 'ac-menu-button-right'
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
            this.$store.dispatch('loader/loadDivisions');
        },

        mounted() {
            axios.get('/controllers/get_list').then(function (response) {
                for (let k in response.data) {
                    window.Echo.private(`controller-events.${response.data[k].id}`)
                        .listen('EventReceived', (e) => {
                            if (e.event === 2 || e.event === 3) {
                                this.$store.commit('cards/setLast', e.card);
                                console.log(e); //TODO delete
                            } else if (e.event === 4 || e.event === 5) {
                                console.log(e); //TODO delete
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
