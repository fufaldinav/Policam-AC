<template>
    <div class="row justify-content-center pt-lg-4">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4 bg-white shadow-sm rounded p-2 d-flex flex-column">
            <div class="mb-0"><h4 class="text-center">Введите код с карты</h4></div>
            <div class="d-flex justify-content-center mb-2"><img :src="Card" class="w-75"></div>
            <div class="d-flex justify-content-center">
                <div class="form-group w-75">
                    <input
                        v-model="code"
                        type="text"
                        class="form-control"
                        :class="{'is-invalid': codeIsInvalid}"
                        id="inputCode"
                        placeholder="Код с карты"
                    >
                    <div class="invalid-feedback text-center">
                        Код занят или не найден
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button
                    class="btn btn-primary"
                    @click="checkCodeCode"
                >
                    <template v-if="codeChecking">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Проверка...
                    </template>
                    <template v-else>
                        Продолжить
                    </template>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import Card from '../../../../public/img/card.png'

    export default {
        name: "EnterCode",

        data() {
            return {
                Card,
                code: '',
                codeChecking: false,
                codeIsInvalid: false
            }
        },

        computed: {
            codeToCheck: {
                get() {
                    return this.code
                },

                set(code) {
                    this.codeIsInvalid = false
                    this.code = code
                }
            },
        },

        methods: {
            checkCodeCode() {
                this.codeChecking = true
                this.$store.dispatch('postreg/getReferral', this.code)
                    .then(response => {
                        if (response === 0) {
                            this.codeIsInvalid = true
                        } else {
                            window.location.href = '/reg/' + this.code
                        }
                    })
                    .catch(error => {
                        if (this.$store.state.debug) console.log(error)
                    })
                    .finally(() => {
                        this.codeChecking = false
                    })
            }
        }
    }
</script>

<style scoped>

</style>
