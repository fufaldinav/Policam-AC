<template>
    <button type="button" class="list-group-item list-group-item-action" @click="selectPerson">
        {{ getPerson.f }}
    </button>
</template>

<script>
    export default {
        name: "AcButtonPerson",
        props: {
            person: Object
        },
        computed: {
            getPerson() {
                return this.$store.getters.person(this.person.id);
            },
            fullName() {
                return this.getPerson.f;
            }
        },
        methods: {
            selectPerson: function () {
                this.$set(this.person, 'f', "123");
                this.$store.commit('updatePerson', this.person);
                console.log(this.person.id);
            }
        }
    }
</script>

<style scoped>

</style>
