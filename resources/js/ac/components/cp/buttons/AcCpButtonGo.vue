<template>
    <a :href="url" class="btn btn-primary">{{ $t('Перейти') }}</a>
</template>

<script>
    export default {
        name: "AcCpButtonGo",

        props: ['url']
    }
</script>

<style scoped>

</style>
