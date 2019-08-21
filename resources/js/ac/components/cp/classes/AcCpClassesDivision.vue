<template>
    <tr>
        <td class="p-1">
            <input
                v-if="thisSelected"
                id="number"
                v-model="classNumber"
                class="form-control-sm"
                :class="inputClass"
                type="text"
                size="2"
                maxlength="2"
                required
            >
            <div v-else>
                {{ classNumber }}
            </div>
        </td>
        <td class="p-1">
            <input
                v-if="thisSelected"
                id="letter"
                v-model="classLetter"
                class="form-control-sm"
                :class="inputClass"
                type="text"
                size="1"
                maxlength="1"
                required
            >
            <div v-else>
                {{ classLetter }}
            </div>
        </td>
        <td class="p-1">
            <ac-cp-classes-buttons-save
                v-if="thisSelected"
                :buttonClass="buttonClass"
                :buttonDisabled="saveButtonDisabled"
            >
            </ac-cp-classes-buttons-save>
            <ac-cp-classes-buttons-edit
                v-else
                :buttonClass="buttonClass"
                :division="division"
            >
            </ac-cp-classes-buttons-edit>
        </td>
        <td class="p-1">
            <ac-cp-classes-buttons-cancel
                v-if="thisSelected"
                :buttonClass="buttonClass"
            >
            </ac-cp-classes-buttons-cancel>
            <ac-cp-classes-buttons-remove
                v-else
                :division="division"
                :buttonClass="buttonClass"
            >
            </ac-cp-classes-buttons-remove>
        </td>
    </tr>
</template>

<script>
    import AcCpClassesButtonsCancel from './buttons/AcCpClassesButtonsCancel'
    import AcCpClassesButtonsEdit from './buttons/AcCpClassesButtonsEdit'
    import AcCpClassesButtonsRemove from './buttons/AcCpClassesButtonsRemove'
    import AcCpClassesButtonsSave from './buttons/AcCpClassesButtonsSave'

    export default {
        name: "AcCpClassesDivision",

        components: {
            AcCpClassesButtonsCancel,
            AcCpClassesButtonsEdit,
            AcCpClassesButtonsRemove,
            AcCpClassesButtonsSave
        },

        props: {
            division: {
                type: Object,
                required: true
            }
        },

        computed: {
            selected() {
                return this.$store.state.divisions.selected
            },

            thisSelected() {
                return this.selected !== null && this.division.id === this.selected.id && ! this.$store.state.modal.shown
            },

            splittedName() {
                if (this.thisSelected) {
                    return this.selected.name.split(' ', 2)
                } else {
                    return this.division.name.split(' ', 2)
                }
            },

            classNumber:
                {
                    get() {
                        return this.splittedName[0]
                    },

                    set(number) {
                        this.selected.name = `${number} "${this.classLetter}"`
                    }
                }
            ,

            classLetter: {
                get() {
                    if (this.splittedName.length > 1) {
                        return this.splittedName[1].replace(/"/g, '')
                    } else {
                        return ''
                    }
                },

                set(letter) {
                    this.selected.name = `${this.classNumber} "${letter.toUpperCase()}"`
                }
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
                return ! this.$store.getters['divisions/checkName'](this.selected.name) && this.selected.name !== this.division.name
            }
        }
    }
</script>

<style scoped>

</style>
