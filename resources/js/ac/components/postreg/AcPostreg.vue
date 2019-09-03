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
                    <b>Внимание!</b> Если Вы сотрудник учреждения, где Вам выдали карту (или браслет), но нужно
                    зарегистрировать ребёнка, выбирайте обе роли!
                </p>
                <h5 class="mt-2 px-2 px-xl-5 text-center">Выберите роль:</h5>
                <div class="d-flex container-fluid justify-content-center mt-2">
                    <div class="btn-group" role="group" aria-label="Role selector">
                        <button
                            v-for="role of roles"
                            type="button"
                            class="btn"
                            :class="buttonClass(role.type)"
                            @click="toggleRole(role.type)"
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
                        :disabled="myRoles.length == 0"
                        @click="toStep(2)"
                    >
                        Продолжить
                    </button>
                </div>
            </div>
            <div v-if="step == 2 && myRoles.indexOf(4) > -1">
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
                <div class="container-fluid row">
                    <div
                        v-for="student of students"
                        class="d-flex col-12 justify-content-center"
                    >
                        <div class="card mt-2" style="width: 18rem;">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h5 class="mr-auto card-title">{{ student.f }} {{ student.i }} ({{ student.gender == 1 ? 'Д' : 'М'}})</h5>
                                    <button
                                        type="button"
                                        class="close align-self-start"
                                        aria-label="Close"
                                        @click="removeStudent(student)"
                                    >
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="d-flex">
                                    <p class="mr-auto card-text">{{ student.o }}</p>
                                    <p class="card-text small">{{ student.birthday }}</p>
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-warning"
                                    @click="showEditForm(student)"
                                >
                                    Редактировать
                                </button>
                            </div>
                        </div>
                    </div>
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
        <ac-postreg-add-student-form windowType="windowType"></ac-postreg-add-student-form>
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

            myRoles() {
                return this.$store.state.postreg.myRoles
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

            toggleRole(role) {
                if (this.myRoles.indexOf(role) == -1) {
                    this.$store.commit('postreg/addRole', role)
                } else {
                    this.$store.commit('postreg/removeRole', role)
                }
            },

            buttonClass(roleType) {
                return {
                    'btn-success': this.myRoles.indexOf(roleType) > -1,
                    'btn-secondary': this.myRoles.indexOf(roleType) == -1
                }
            },

            showAddForm() {
                this.$store.commit('postreg/setStudentFormType', 'add')
                $('#addStudentForm').modal('show')
            },

            showEditForm(student) {
                this.$store.commit('postreg/setStudentFormType', 'edit')
                this.$store.commit('postreg/setCurrentStudent', student)
                $('#addStudentForm').modal('show')
            },

            removeStudent(student) {
                this.$store.commit('postreg/removeStudent', student)
            }
        },

        mounted() {
            $('#addStudentForm').modal({
                show: false
            })
            $('#addStudentForm').on('hidden.bs.modal', () => {
                this.$store.commit('postreg/clearCurrentStudent', this.student)
            })
        }
    }
</script>

<style scoped>

</style>
