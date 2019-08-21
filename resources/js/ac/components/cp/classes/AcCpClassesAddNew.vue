<template>
    <tr>
        <td class="p-1">
            <input
                id="number"
                v-model="classNumber"
                class="form-control-sm"
                :class="inputClass"
                type="text"
                size="2"
                maxlength="2"
                required
            >
        </td>
        <td class="p-1">
            <input
                id="letter"
                v-model="classLetter"
                class="form-control-sm"
                :class="inputClass"
                type="text"
                size="1"
                maxlength="1"
                required
            >
        </td>
        <td class="p-1">
            <ac-cp-classes-buttons-add
                :buttonClass="buttonClass"
                :buttonDisabled="saveButtonDisabled"
                :divisionName="divisionName"
            >
            </ac-cp-classes-buttons-add>
        </td>
        <td class="p-1"></td>
    </tr>
</template>

<script>

    import AcCpClassesButtonsAdd from './buttons/AcCpClassesButtonsAdd'

    export default {
        name: "AcCpClassesAddNew",

        components: {AcCpClassesButtonsAdd},

        data() {
            return {
                classNumber: null,
                classLetter: null
            }
        },

        computed: {
            divisionName() {
                if (this.classLetter !== null) this.classLetter = this.classLetter.toUpperCase()
                return `${this.classNumber} "${this.classLetter}"`
            },

            buttonClass() {
                if (this.$store.state.bp.current !== 'xs') {
                    return 'btn-sm'
                }
            },

            inputClass() {
                if (this.$store.state.bp.current === 'xs') {
                    return 'form-control'
                } else {
                    return 'form-control-sm'
                }
            },

            saveButtonDisabled() {
                return ! this.$store.getters['divisions/checkName'](this.divisionName)
            }
        }
    }
</script>

<style scoped>

</style>
