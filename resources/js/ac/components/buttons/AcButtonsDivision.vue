<template>
    <button
        type="button"
        class="list-group-item list-group-item-action d-flex align-items-center"
        @click="selectDivision"
    >
        <div class="flex-grow-1">
            {{ division.name }}
        </div>
        <span
            v-if="inactiveRC"
            class="badge badge-pill badge-danger mr-2"
        >
            !
        </span>
        <span
            class="badge badge-pill"
            :class="[count ? 'badge-primary' : 'badge-warning']"
        >
            {{ count }}
        </span>
    </button>
</template>

<script>
    export default {
        name: "AcButtonsDivision",

        props: {
            division: Object
        },

        computed: {
            count() {
                return this.division.persons.length
            },

            inactiveRC() {
                return this.division.persons.find(person => {
                    let p = this.$store.getters['persons/getById'](person)
                    if (p === undefined) return false
                    if (p.referral_code !== null) {
                        return p.referral_code.activated === 0
                    }
                    return false
                }) !== undefined
            }
        },

        methods: {
            selectDivision() {
                this.$store.commit('divisions/setSelected', this.division)
            }
        }
    }
</script>
