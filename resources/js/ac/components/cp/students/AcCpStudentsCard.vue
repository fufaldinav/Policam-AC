<template>
    <div
        class="col-12 col-md-6 d-flex justify-content-center"
        @click="openPerson"
    >
        <div class="card mb-3 ac-cp-student-card">
            <div class="row no-gutters">
                <div class="col-4">
                    <img :src="photoUrl" class="card-img" alt="">
                </div>
                <div class="col-8">
                    <div class="card-body">
                        <h5 class="card-title mb-0">{{ person.f }} {{ person.i }}</h5>
                        <p class="card-text">
                            <small>{{ person.o }}</small>
                        </p>
                        <p class="card-text">{{ $t('Дата рождения')}}: {{ person.birthday }}</p>
                        <p class="card-text">{{ $t('Адрес')}}: {{ person.address }}</p>
                        <p class="card-text">{{ $t('Номер телефона')}}: {{ person.phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcCpStudentsCard",

        props: {
            person: {
                type: Object,
                required: true
            }
        },

        computed: {
            photo() {
                if (this.person.photos.length === 0) return null
                return this.person.photos[0]
            },

            photoUrl() {
                console.log(window.location.hostname)
                return '/photos/thumbnails/' + (this.photo !== null ? this.photo : '0') + '.jpg'
            }
        },

        methods: {
            openPerson() {
                console.log('open')
            }
        }
    }
</script>

<style scoped>
    .ac-cp-student-card {
        width: 100%;
        max-width: 540px;
    }
</style>
