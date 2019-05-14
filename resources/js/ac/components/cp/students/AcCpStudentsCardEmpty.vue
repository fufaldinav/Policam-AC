<template>
    <div class="col-12 col-md-6 d-flex justify-content-center">
        <div
            class="card shadow-sm mb-3 ac-cp-student-card justify-content-center align-items-center"
            :class="cardClass"
            @click="addPerson"
            @mouseenter="highlighted = true"
            @mouseleave="highlighted = false"
        >
            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus-circle"
                 :class="iconClass"
                 class="card-img m-3 ac-cp-student-add" role="img" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92h-92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"></path>
            </svg>
        </div>
    </div>
</template>

<script>
    import {Person} from '../../../classes'

    export default {
        name: "AcCpStudentsCardEmpty",

        data() {
            return {
                highlighted: false
            }
        },

        computed: {
            cardClass() {
                return {
                    'text-white bg-info': this.highlighted
                }
            },

            iconClass() {
                return {
                    'ac-cp-student-add-white': this.highlighted
                }
            }
        },

        methods: {
            addPerson() {
                this.$store.commit('persons/setSelected', new Person({id: 0}))
            }
        }
    }
</script>

<style scoped>
    .ac-cp-student-card {
        width: 100%;
        max-width: 540px;
    }

    .ac-cp-student-card:hover {
        cursor: pointer;
    }

    .ac-cp-student-add {
        height: 3rem;
        width: 3rem;
        color: #3f9ae5;
        opacity: 0.9;
    }

    .ac-cp-student-add-white {
        color: #ffffff;
    }
</style>
