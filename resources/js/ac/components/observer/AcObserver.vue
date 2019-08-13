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
                                                {{ $t('Фамилия').toUpperCase() }}
                                            </label>
                                            <input
                                                id="f"
                                                v-model="selectedPerson.f"
                                                type="text"
                                                class="form-control"
                                                :placeholder="$t('Фамилия')"
                                                readonly
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="i">
                                                {{ $t('Имя') }}
                                            </label>
                                            <input
                                                id="i"
                                                v-model="selectedPerson.i"
                                                type="text"
                                                class="form-control"
                                                :placeholder="$t('Имя')"
                                                readonly
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="o">
                                                {{ $t('Отчество') }}
                                            </label>
                                            <input
                                                id="o"
                                                v-model="selectedPerson.o"
                                                type="text"
                                                class="form-control"
                                                :placeholder="$t('Отчество')"
                                                readonly
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="birthday">
                                                {{ $t('Дата рождения') }}
                                            </label>
                                            <input
                                                id="birthday"
                                                v-model="selectedPerson.birthday"
                                                type="date"
                                                class="form-control"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4 col-sm-3">
                                        <label for="division">
                                            {{ selectedPersonDivisionNameByType }}
                                        </label>
                                        <input
                                            id="division"
                                            v-model="selectedPersonDivisionName"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('Подразделение')"
                                            readonly
                                        >
                                    </div>
                                    <div class="form-group col-8 col-sm-9">
                                        <label for="address">
                                            {{ $t('Адрес') }}
                                        </label>
                                        <input
                                            id="address"
                                            v-model="selectedPerson.address"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('Адрес')"
                                            readonly
                                        >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="phone">
                                            {{ $t('Номер телефона') }}
                                        </label>
                                        <input
                                            id="phone"
                                            v-model="selectedPerson.phone"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('Номер телефона')"
                                            readonly
                                        >
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="uid">
                                            {{ $t('Уникальный номер') }}
                                        </label>
                                        <input
                                            id="uid"
                                            v-model="selectedPerson.id"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('Уникальный номер')"
                                            readonly
                                        >
                                    </div>
                                </div>
                                <div
                                    v-if="selectedPerson.cards.length > 0 && selectedManually"
                                    class="form-row"
                                >
                                    <div class="form-group col-6 col-sm-12">
                                        <ac-observer-buttons-card-forgot></ac-observer-buttons-card-forgot>
                                        <ac-observer-buttons-card-lost></ac-observer-buttons-card-lost>
                                        <ac-observer-buttons-card-broke></ac-observer-buttons-card-broke>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </transition>
            <ac-observer-menu-right></ac-observer-menu-right>
        </div>
        <ac-observer-modal></ac-observer-modal>
    </div>
</template>

<script>
    import AcLoading from '../AcLoading'
    import AcObserverMenuLeft from './AcObserverMenuLeft'
    import AcObserverMenuRight from './AcObserverMenuRight'
    import AcObserverButtonsCardBroke from './buttons/AcObserverButtonsCardBroke'
    import AcObserverButtonsCardForgot from './buttons/AcObserverButtonsCardForgot'
    import AcObserverButtonsCardLost from './buttons/AcObserverButtonsCardLost'
    import AcObserverModal from './AcObserverModal'

    export default {
        name: "AcObserver",

        components: {
            AcLoading, AcObserverMenuLeft, AcObserverMenuRight,
            AcObserverButtonsCardBroke, AcObserverButtonsCardForgot, AcObserverButtonsCardLost, AcObserverModal
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

            selectedPersonDivisionNameByType() {
                if (this.selectedPersonDivision === null) return this.$t('Подразделение')
                if (this.selectedPersonDivision.type === 1) {
                    return this.$t('Класс')
                } else {
                    return this.$t('Подразделение')
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
            this.$store.dispatch('loader/loadDivisions', {organizationId: 0, withPersons: 1})
        },

        beforeMount() {
            this.$store.commit('cp/showForm')
        },

        mounted() {
            this.$bus.$on('EventReceived', e => {
                if (e.event.event === 4 || e.event.event === 5 || e.event.event === 16 || e.event.event === 17) {
                    e.event.person_id = e.person_id
                    this.$store.commit('history/add', e.event)

                    let person = this.getPerson(e.person_id)
                    if (person === undefined) return

                    this.$store.commit('persons/setSelected', person)
                    this.$store.commit('persons/setManually', false)
                } else if (e.event.event === 2 || e.event.event === 3) {
                    e.event.person_id = e.person_id
                    this.$store.commit('history/add', e.event)
                }
            })
            this.$bus.$on('ControllerConnected', e => {
                if (this.$store.state.debug) console.log('Контроллер ID: ' + e.controller_id + ' вышел на связь')
            })
        },

        beforeDestroy() {
            this.$bus.$off('EventReceived')
            this.$bus.$off('ControllerConnected')
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
