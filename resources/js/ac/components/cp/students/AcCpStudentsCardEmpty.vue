<template>
    <div
        class="col-12 col-md-6 d-flex justify-content-center"
        @click="addPerson"
    >
        <div class="card mb-3 ac-cp-student-card">
            <div class="row no-gutters">
                <div class="col-4">
                    <img src="" class="card-img" alt="">
                </div>
                <div class="col-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $t('Добавить') }}</h5>
                        <p class="card-text">{{ $t('Нажмите на карточку, чтобы добавить нового ученика') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcCpStudentsCardEmpty",

        methods: {
            addPerson() {
                console.log('add')
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
