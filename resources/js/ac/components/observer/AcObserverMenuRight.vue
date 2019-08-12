<template>
    <div class="d-none d-lg-block col-lg-3 bg-white ac-menu ac-menu-right py-2">
        <div class="events">
            <ac-observer-toasts
                v-for="(event, id) in events"
                :key="id"
                :event="event"
            >
            </ac-observer-toasts>
        </div>
    </div>
</template>

<script>
    import AcObserverToasts from "./AcObserverToasts"

    export default {
        name: "AcObserverMenuRight",

        components: {AcObserverToasts},

        computed: {
            events() {
                return this.$store.getters['history/getAll']
            },
        },

        mounted() {
            this.$store.dispatch('history/getLast')
        }
    }
</script>
