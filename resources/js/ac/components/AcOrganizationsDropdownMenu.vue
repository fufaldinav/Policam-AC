<template>
    <div class="nav-item">
        <div v-if="organizations.length === 1">
            <a class="navbar-brand" href="" @click.prevent>{{ currentOrganization.name }}</a>
        </div>
        <div
            v-else-if="organizations.length > 0"
            class="nav-item dropdown"
        >
            <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false">{{ currentOrganization.name }}</a>
            <div class="dropdown-menu">
                <a
                    v-for="org in organizations"
                    :key="org.id"
                    href=""
                    class="dropdown-item"
                    @click.prevent="changeOrganization(org)"
                >
                    {{ org.name }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcOrganizationsDropdownMenu",

        computed: {
            organizations() {
                return this.$store.getters['organizations/sorted']
            },

            currentOrganization() {
                return this.$store.state.organizations.selected
            }
        },

        created() {
            this.$store.dispatch('loader/loadOrganizations')
        },

        methods: {
                changeOrganization(org) {
                this.$store.commit('persons/clearSelected')
                this.$store.commit('divisions/clearSelected')
                this.$store.dispatch('messenger/unsubscribe')
                this.$store.commit('organizations/setSelected', org)
                this.$store.dispatch('messenger/subscribe')
                this.$store.dispatch('loader/loadDivisions', {organizationId: org.id, withPersons: this.$store.state.personsMustBeLoaded})
            }
        },
    }
</script>

<style scoped>

</style>
