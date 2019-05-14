<template>
    <div class="custom-control custom-checkbox">
        <input
            type="checkbox"
            :id="'org' + org.id"
            class="custom-control-input"
            :value="org.id"
            v-model="checked"
        >
        <label class="custom-control-label" :for="'org' + org.id">{{ org.name }}</label>
    </div>
</template>

<script>
    export default {
        name: "AcCpStudentsOrganizationsAdditionalCheckbox",

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            checked: {
                get() {
                    return this.selectedPerson.organizations.additional.indexOf(this.org.id) > -1
                },
                set(state) {
                    let index = this.selectedPerson.organizations.additional.indexOf(this.org.id)
                    if (state) {
                        if (index === -1) {
                            this.selectedPerson.organizations.additional.push(this.org.id)
                        }
                    } else {
                        if (index > -1) {
                            this.selectedPerson.organizations.additional.splice(index, 1)
                        }
                    }
                }
            }
        },

        props: {
            org: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
