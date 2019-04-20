<template>
    <div>
        <slot name="header"></slot>
        <slot name="main"></slot>
        <slot name="footer"></slot>
    </div>
</template>

<script>
    export default {
        name: "AcLayout"
    }
</script>

<style scoped>

</style>
