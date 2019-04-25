<template>
    <div class="card mb-2" style="width: 18rem;">
        <slot name="img"></slot>
        <div class="card-body">
            <h5 class="card-title"><slot name="title"></slot></h5>
            <p class="card-text"><slot name="text"></slot></p>
            <ac-cp-button-go v-if="active" :url="url"></ac-cp-button-go>
            <ac-cp-button-unavailable v-else="active"></ac-cp-button-unavailable>
        </div>
    </div>
</template>

<script>
    import AcCpButtonGo from './buttons/AcCpButtonGo'
    import AcCpButtonUnavailable from './buttons/AcCpButtonUnavailable'

    export default {
        name: "AcCpFunctionCard",

        components: {AcCpButtonGo, AcCpButtonUnavailable},

        props: ['active', 'url']
    }
</script>

<style scoped>

</style>
