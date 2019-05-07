<template>
    <tr>
        <td class="p-1">
            {{ classNumber }}
        </td>
        <td class="p-1">
            {{ classLetter }}
        </td>
        <td class="p-1">
            <ac-cp-classes-buttons-remove
                :division="division"
                :buttonClass="buttonClass"
            >
            </ac-cp-classes-buttons-remove>
        </td>
    </tr>
</template>

<script>
    import AcCpClassesButtonsRemove from './buttons/AcCpClassesButtonsRemove'

    export default {
        name: "AcCpClassesDivision",

        components: {AcCpClassesButtonsRemove},

        props: {
            division: {
                type: Object,
                required: true
            }
        },

        computed: {
            splittedName() {
                return this.division.name.split(' ', 2)
            },

            classNumber() {
                return this.splittedName[0]
            },

            classLetter() {
                if (this.splittedName.length > 1) {
                    return this.splittedName[1].replace(/"/g, '')
                } else {
                    return ''
                }
            },

            buttonClass() {
                if (this.$store.state.bp.current !== 'xs') {
                    return 'btn-sm'
                }
            }
        }
    }
</script>

<style scoped>

</style>
