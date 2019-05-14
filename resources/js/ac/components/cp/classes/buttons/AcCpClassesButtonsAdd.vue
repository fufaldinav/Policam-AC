<template>
    <button
        class="btn btn-primary"
        :class="buttonClass"
        type="button"
        :disabled="buttonDisabled"
        @click="addDivision"
    >
        {{ $t('Сохранить') }}
    </button>
</template>

<script>
    import {Division} from '../../../../classes'

    export default {
        name: "AcCpClassesButtonsAdd",

        props: {
            buttonClass: {
                type: String
            },

            buttonDisabled: {
                type: Boolean
            },

            divisionName: {
                type: String
            }
        },

        computed: {
            selectedOrganization() {
                return this.$store.state.organizations.selected
            }
        },

        methods: {
            addDivision() {
                if (this.$store.state.modal.shown) {
                    this.$store.dispatch('divisions/saveSelected')

                    this.$store.dispatch('modal/close')
                } else {
                    this.$store.commit('divisions/setSelected', new Division({name: this.divisionName, organization_id: this.selectedOrganization.id}))

                    this.$store.commit('modal/setTitle', this.$t('Сохранение'))

                    this.$store.commit('modal/setMessage', this.$t('Вы действительно хотите сохранить подразделение?'))

                    this.$store.commit('modal/setAcceptButton', 'saveDivision')

                    this.$store.dispatch('modal/show')
                }
            }
        }
    }
</script>

<style scoped>

</style>
