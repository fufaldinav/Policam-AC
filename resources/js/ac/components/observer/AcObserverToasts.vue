<template>
    <div :id="id" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="" class="rounded mr-2" alt="">
            <strong class="mr-auto">{{ getEventType(event.event) }}</strong>
            <small>{{ event.time }}</small>
            <!--            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">-->
            <!--                <span aria-hidden="true">&times;</span>-->
            <!--            </button>-->
        </div>
        <div class="toast-body">{{ personName }}</div>
    </div>
</template>

<script>
    export default {
        name: "AcObserverToasts",

        props: {
            event: Object
        },

        computed: {
            id() {
                return 'toast' + this.event.id
            },

            personName() {
                let person = this.$store.getters['persons/getById'](this.event.person_id)

                return person.f !== null ? `${person.f} ${person.i}` : 'Неизвестный'
            }
        },

        methods: {
            getEventType(id) {
                return this.$store.getters['history/getType'](id)
            }
        },

        mounted() {
            let selector = `#${this.id}`
            $(selector).toast({ autohide: false, delay: 5000 })
            $(selector).toast('show')
        }
    }
</script>

<style scoped>

</style>
