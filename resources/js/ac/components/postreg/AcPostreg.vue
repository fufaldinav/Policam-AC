<template>
    <div class="row justify-content-center p-2 pt-lg-4">
        <div class="col-12 col-lg-8 col-xl-6 bg-white shadow-sm rounded p-2">
            <div v-if="step == 0">
                <div class="text-center"><h3>Приветствуем!</h3>Вы успешно зарегистрировались на нашем портале.</div>
                <p class="mt-2 px-2 px-xl-5">
                    Осталось совсем немного и Вы сможете продолжить работу. Всего пару шагов. Просим
                    внимательно
                    изучить каждый шаг и следить за подсказками.
                </p>
                <div class="d-flex container-fluid justify-content-center mt-2">
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="toStep(1)"
                    >
                        Продолжить
                    </button>
                </div>
            </div>
            <div v-if="step == 1">
                <div class="text-center"><h3>Выбор роли</h3>Нам нужно знать, для чего вы здесь :)</div>
                <p class="mt-2 px-2 px-xl-5">
                    Важно понимать, зачем Вы здесь, быть может, Вы родитель и хотите записать своего ребёнка в школу или
                    в учреждение дополнительного образования.
                </p>
                <p class="mt-2 px-2 px-xl-5">
                    А может Вы работаете в таких учреждения, тогда Ваша роль - сотрудник
                </p>
                <p class="mt-2 px-2 px-xl-5">
                    Но если вы секретарь или большой босс, выбор соответствующей роли за Вами!
                </p>
                <div class="d-flex container-fluid justify-content-center mt-2">
                    <div class="btn-group" role="group" aria-label="Role selector">
                        <button
                            v-for="role of roles"
                            type="button"
                            class="btn"
                            :class="buttonClass(role.type)"
                            @click="setRole(role.type)"
                        >
                            {{ role.name }}
                        </button>
                    </div>
                </div>
                <div class="d-flex container-fluid justify-content-center mt-2">
                    <button
                        type="button"
                        class="btn btn-danger mr-2"
                        @click="toStep(0)"
                    >
                        Назад
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        :disabled="role == 0"
                        @click="toStep(2)"
                    >
                        Продолжить
                    </button>
                </div>
            </div>
            <div v-if="step == 2 && role == 4">
                <div class="text-center"><h3>Регистрируем детей</h3>Отлично, Вы выбрали роль "Родитель"</div>
                <p class="mt-2 px-2 px-xl-5">
                    Теперь Вам необходимо добавить всех Ваших детей.
                </p>
                <p class="mt-2 px-2 px-xl-5">
                    Нажмите кнопку ниже и Вы увидете форму, где необходимо внести данные.
                </p>
                <p class="mt-2 px-2 px-xl-5">
                    При заполнении будьте предельно внимательны, ошибки недопустимы!
                </p>
                <div class="d-flex container-fluid justify-content-center mt-2">
                    <button
                        type="button"
                        class="btn btn-info"
                        @click="showAddForm()"
                    >
                        Добавить
                    </button>
                </div>
                <div class="d-flex container-fluid justify-content-center mt-2">
                    <button
                        type="button"
                        class="btn btn-danger mr-2"
                        @click="toStep(1)"
                    >
                        Назад
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        :disabled="students.length == 0"
                        @click="toStep(3)"
                    >
                        Продолжить
                    </button>
                </div>
            </div>
        </div>
        <ac-postreg-add-student-form></ac-postreg-add-student-form>
    </div>
</template>

<script>
    import AcPostregAddStudentForm from './AcPostregAddStudentForm'

    export default {
        name: "AcPostreg",

        components: {AcPostregAddStudentForm},

        computed: {
            step() {
                return this.$store.state.postreg.step
            },

            role() {
                return this.$store.state.postreg.role
            },

            roles() {
                return this.$store.state.postreg.roles
            },

            students() {
                return this.$store.state.postreg.students
            }
        },

        methods: {
            toStep(step) {
                this.$store.commit('postreg/toStep', step)
            },

            setRole(role) {
                this.$store.commit('postreg/setRole', role)
            },

            buttonClass(roleType) {
                return {
                    'btn-success': this.role == roleType,
                    'btn-secondary': this.role !== roleType
                }
            },

            showAddForm() {
                $('#addStudentForm').modal('show')
            }
        },

        mounted() {
            $('#addStudentForm').modal({
                show: false
            })
        }
    }
</script>

<style scoped>

</style>
