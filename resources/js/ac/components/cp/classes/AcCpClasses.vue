<template>
    <div class="container-fluid">
        <div v-if="loading">
            <ac-loading></ac-loading>
        </div>
        <div
            v-else
            class="row justify-content-center my-2"
        >
            <div class="col-12 col-md-10 col-lg-8 col-xl-6 bg-white rounded shadow py-2">
                <table class="container-fluid table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{ $t('Номер') }}</th>
                        <th>{{ $t('Литера') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <ac-cp-classes-add-new></ac-cp-classes-add-new>
                    <ac-cp-classes-division
                        v-for="division of divisions"
                        v-if="division.type === 1"
                        :key="division.id"
                        :division="division"
                    >

                    </ac-cp-classes-division>
                    </tbody>
                </table>
            </div>
        </div>
        <ac-cp-classes-modal></ac-cp-classes-modal>
    </div>
</template>

<script>
    import AcLoading from '../../AcLoading'
    import AcCpClassesAddNew from './AcCpClassesAddNew'
    import AcCpClassesDivision from './AcCpClassesDivision'
    import AcCpClassesModal from './AcCpClassesModal'

    export default {
        name: "AcCpClasses",

        components: {AcLoading, AcCpClassesDivision, AcCpClassesAddNew, AcCpClassesModal},

        computed: {
            loading() {
                return this.$store.state.loader.loading
            },

            divisions() {
                return this.$store.getters['divisions/sorted']
            }
        },

        created() {
            this.$store.commit('setPersonsShouldNotBeLoaded')
            this.$store.dispatch('loader/loadDivisions', {organizationId: 0, withPersons: 0})
        }
    }
</script>

<style scoped>

</style>
