<template>
    <transition name="fade">
        <div class="alert alert-dismissible fade show ac-alert" :class="type" role="alert" v-if="message">
            {{ message }}
        </div>
    </transition>
</template>

<script>
    export default {
        name: "AcAlert",
        props: {
            message: {
                required: true
            },
            type: {
                type: String
            }
        }
    }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 1s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
