<template>
    <div class="position-absolute d-flex justify-content-center align-items-center ac-loading">
        <div class="mx-auto">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcLoading"
    }
</script>

<style scoped>
    .ac-loading {
        top: calc(50% - 2rem);
        left: calc(50% - 2rem);
        max-height: 4rem;
        max-width: 4rem;
        height: 4rem;
        width: 4rem;
    }
</style>
