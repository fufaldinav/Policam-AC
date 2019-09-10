<template>
    <div class="col-12 col-md-6 d-flex justify-content-center">
        <div
            class="card shadow-sm mb-3 ac-cp-student-card"
            :class="cardClass"
            @click="selectPerson"
            @mouseenter="highlighted = true"
            @mouseleave="highlighted = false"
        >
            <div class="row no-gutters">
                <div class="col-4">
                    <img :src="photoUrl" class="card-img shadow-sm m-1" alt="">
                </div>
                <div class="col-8">
                    <div class="card-body">
                        <h5 class="card-title mb-0">{{ person.f }} {{ person.i }}</h5>
                        <p class="card-text">
                            <small>{{ person.o }}</small>
                        </p>
                        <p class="card-text">Дата рождения: {{ person.birthday }}</p>
                        <p v-if="person.address" class="card-text">Адрес: {{ person.address }}</p>
                        <p v-if="person.phone" class="card-text">Номер телефона: {{ person.phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Person} from '../../../classes'

    export default {
        name: "AcCpStudentsCard",

        props: {
            person: {
                type: Object,
                required: true
            }
        },

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

            photo() {
                if (this.person.photos.length === 0) return null
                return this.person.photos[0]
            },

            photoUrl() {
                return '/photos/thumbnails/' + (this.photo !== null ? this.photo.hash : '0') + '.jpg'
            }
        },

        methods: {
            selectPerson() {
                this.$store.commit('persons/setSelected', new Person(this.person))
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
</style>
