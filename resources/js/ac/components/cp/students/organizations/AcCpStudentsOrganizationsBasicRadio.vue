<template>
    <div class="custom-control custom-radio">
        <input
            type="radio"
            :id="'org' + org.id"
            name="basicEducation"
            class="custom-control-input"
            :value="org.id"
            v-model="checked"
            :disabled="selectedPerson.id > 0"
        >
        <label class="custom-control-label" :for="'org' + org.id">
            Школа № {{ org.name }}
        </label>
    </div>
</template>

<script>
    export default {
        name: "AcCpStudentsOrganizationsBasicRadio",

        props: {
            org: {
                type: Object,
                required: true
            }
        },

        computed: {
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            checked: {
                get() {
                    return this.selectedPerson.organizations.basic
                },
                set(state) {
                    if (state) {
                        this.selectedPerson.organizations.basic = this.org.id
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
