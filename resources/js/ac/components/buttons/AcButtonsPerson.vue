<template>
    <button
        type="button"
        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
        @click="selectPerson"
    >
        {{ person.f }} {{ person.i }}
        <span v-if="noRCorNoActivated" class="badge badge-pill" :class="{'badge-danger': noRCorNoActivated}">
            !
        </span>
    </button>
</template>

<script>
    import {Person} from '../../classes'

    export default {
        name: "AcButtonsPerson",

        props: {
            person: {
                type: Object,
                required: true
            }
        },

        computed: {
            noRCorNoActivated() {
                if (this.person.referral_code === null) {
                    return true
                }
                return this.person.referral_code.activated === 0
            }
        },

        methods: {
            selectPerson () {
                this.$store.commit('persons/setSelected', new Person(this.person))
                this.$store.commit('persons/setManually')
                this.$store.commit('cp/showForm')
            }
        }
    }
</script>
