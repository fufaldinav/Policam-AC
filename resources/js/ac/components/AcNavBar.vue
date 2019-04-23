<template>
    <div class="navbar navbar-expand-sm navbar-light sticky-top px-0 bg-white ac-navbar">
        <div class="container-fluid">
            <slot name="ac-logo"></slot>
            <slot name="ac-organizations"></slot>
            <slot name="ac-menu-button"></slot>
            <div class="collapse navbar-collapse bg-white" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <slot name="ac-nav-left-side"></slot>
                <!-- Right Side Of Navbar -->
                <slot name="ac-nav-right-side"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcNavBar"
    }
</script>

<style scoped>

</style>
