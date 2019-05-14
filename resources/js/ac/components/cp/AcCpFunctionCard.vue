<template>
    <div class="card mb-2 ac-function-card">
        <slot name="img"></slot>
        <div class="card-body">
            <h5 class="card-title">
                <slot name="title"></slot>
            </h5>
            <p class="card-text">
                <slot name="text"></slot>
            </p>
            <ac-cp-buttons-go v-if="active" :url="url"></ac-cp-buttons-go>
            <ac-cp-buttons-unavailable v-else="active"></ac-cp-buttons-unavailable>
        </div>
    </div>
</template>

<script>
    import AcCpButtonsGo from './buttons/AcCpButtonsGo'
    import AcCpButtonsUnavailable from './buttons/AcCpButtonsUnavailable'

    export default {
        name: "AcCpFunctionCard",

        components: {AcCpButtonsGo, AcCpButtonsUnavailable},

        props: ['active', 'url']
    }
</script>

<style scoped>
    .ac-function-card {
        width: 18rem;
    }
</style>
