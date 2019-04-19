<template>
    <button
        type="button"
        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
        :disabled="count == 0"
        @click="selectDivision"
    >
        {{ division.name }}
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
        name: "AcButtonDivision",

        props: {
            division: Object
        },

        computed: {
            count() {
                return this.division.persons.length;
            }
        },

        methods: {
            selectDivision() {
                this.$store.commit('divisions/setSelected', this.division);
            }
        }
    }
</script>
