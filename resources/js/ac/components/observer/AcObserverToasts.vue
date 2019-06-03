<template>
    <div :id="id" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header" :class="headerClass">
            <strong class="mr-auto" :class="textClass">{{ getEventType(event.event) }}</strong>
            <small :class="textClass">{{ event.time }}</small>
<!--                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">-->
<!--                    <span aria-hidden="true">&times;</span>-->
<!--                </button>-->
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
            },

            headerClass() {
                let e = this.event.event
                return {
                    'bg-danger': (e === 2 || e === 3),
                    'bg-success': (e === 4 || e === 5 || e === 16 || e === 17)
                }
            },

            textClass() {
                let e = this.event.event
                return {
                    'text-light': (e === 2 || e === 3 || e === 4 || e === 5 || e === 16 || e === 17)
                }
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
