<template xmlns:v-touch="http://www.w3.org/1999/xhtml">
    <div class="container-fluid">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
            class="row"
        >
            <ac-observer-menu-left></ac-observer-menu-left>
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
                            <form
                                class="needs-validation"
                                novalidate
                            >
                                <div class="form-row">
                                    <div class="form-group col-6 d-flex justify-content-center align-items-center">
                                        <div class="position-relative h-auto w-auto d-inline-block">
                                            <img
                                                class="img-fluid rounded shadow ac-person-photo"
                                                :src="url"
                                                alt=""
                                            >
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <div class="form-group">
                                            <label for="f">
                                                {{ $t('ac.f') }}
                                            </label>
                                            <input
                                                id="f"
                                                v-model="selectedPerson.f"
                                                type="text"
                                                class="form-control"
                                                :placeholder="$t('ac.f')"
                                                readonly
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="i">
                                                {{ $t('ac.i') }}
                                            </label>
                                            <input
                                                id="i"
                                                v-model="selectedPerson.i"
                                                type="text"
                                                class="form-control"
                                                :placeholder="$t('ac.i')"
                                                readonly
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="o">
                                                {{ $t('ac.o') }}
                                            </label>
                                            <input
                                                id="o"
                                                v-model="selectedPerson.o"
                                                type="text"
                                                class="form-control"
                                                :placeholder="$t('ac.o')"
                                                readonly
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="birthday">
                                                {{ $t('ac.birthday') }}
                                            </label>
                                            <input
                                                id="birthday"
                                                v-model="selectedPerson.birthday"
                                                type="date"
                                                class="form-control"
                                                :placeholder="$t('ac.birthday')"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4 col-sm-3">
                                        <label for="division">
                                            {{ selectedPersonDivisionTypeName }}
                                        </label>
                                        <input
                                            id="division"
                                            v-model="selectedPersonDivisionName"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('ac.division')"
                                            readonly
                                        >
                                    </div>
                                    <div class="form-group col-8 col-sm-9">
                                        <label for="address">
                                            {{ $t('ac.address') }}
                                        </label>
                                        <input
                                            id="address"
                                            v-model="selectedPerson.address"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('ac.address')"
                                            readonly
                                        >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="phone">
                                            {{ $t('ac.phone') }}
                                        </label>
                                        <input
                                            id="phone"
                                            v-model="selectedPerson.phone"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('ac.phone')"
                                            readonly
                                        >
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="uid">
                                            {{ $t('ac.uid') }}
                                        </label>
                                        <input
                                            id="uid"
                                            v-model="selectedPerson.id"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('ac.uid')"
                                            readonly
                                        >
                                    </div>
                                </div>
                                <div
                                    v-if="selectedPerson.cards.length > 0 && selectedManually"
                                    class="form-row"
                                >
                                    <div class="form-group col-6 col-sm-12">
                                        <ac-button-card-forgot></ac-button-card-forgot>
                                        <ac-button-card-lost></ac-button-card-lost>
                                        <ac-button-card-broke></ac-button-card-broke>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </transition>
            <ac-observer-menu-right></ac-observer-menu-right>
            <input id="type" name="type" type="text" hidden readonly>
        </div>
        <ac-observer-modal></ac-observer-modal>
    </div>
</template>

<script>
    import AcLoading from '../AcLoading'
    import AcObserverMenuLeft from './AcObserverMenuLeft'
    import AcObserverMenuRight from './AcObserverMenuRight'
    import AcButtonCardBroke from '../buttons/AcButtonCardBroke'
    import AcButtonCardForgot from '../buttons/AcButtonCardForgot'
    import AcButtonCardLost from '../buttons/AcButtonCardLost'
    import AcObserverModal from './AcObserverModal'

    export default {
        name: "AcObserver",

        components: {
            AcLoading, AcObserverMenuLeft, AcObserverMenuRight,
            AcButtonCardBroke, AcButtonCardForgot, AcButtonCardLost, AcObserverModal
        },

        computed: {
            loading() {
                return this.$store.state.loader.loading
            },

            selectedPerson() {
                return this.$store.state.persons.selected
            },

            selectedManually() {
                return this.$store.state.persons.manually
            },

            selectedPersonDivision() {
                if (this.selectedPerson.divisions.length === 0) return null
                let divisionId = this.selectedPerson.divisions[0]
                return this.$store.getters['divisions/getById'](divisionId)
            },

            selectedPersonDivisionName() {
                if (this.selectedPersonDivision === null) return null
                return this.selectedPersonDivision.name
            },

            selectedPersonDivisionTypeName() {
                if (this.selectedPersonDivision === null) return this.$t('ac.division')
                if (this.selectedPersonDivision.type === 1) {
                    return this.$t('ac.class')
                } else {
                    return this.$t('ac.division')
                }
            },

            photo() {
                if (this.selectedPerson.photos.length > 0) {
                    return this.selectedPerson.photos[0]
                }

                return {id: 0, hash: 0}
            },

            url() {
                return '/photos/thumbnails/' + this.photo.hash + '.jpg'
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
            },

            getPerson(id) {
                return this.$store.state.persons.collection[id]
            },
        },

        created() {
            this.$store.dispatch('loader/loadData')
        },

        beforeMount() {
            this.$store.commit('cp/showForm')
        },

        mounted() {
            let self = this
            axios.get('/controllers/get_list').then(response => {
                for (let k in response.data) {
                    window.Echo.private(`controller-events.${response.data[k].id}`)
                        .listen('EventReceived', (e) => {
                            if (e.event === 4 || e.event === 5) {
                                let person = this.getPerson(e.person)
                                if (person === undefined) return
                                self.$store.commit('persons/setSelected', person)
                                self.$store.commit('persons/setManually', false)
                            }
                        })
                        .listen('ControllerConnected', (e) => {
                            if (self.$store.state.debug) console.log('Контроллер ID: ' + e.controller_id + ' вышел на связь')
                        })
                }
            }).catch(error => {
                if (self.$store.state.debug) console.log(error)
                self.$root.alert(error, 'danger')
            })
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
