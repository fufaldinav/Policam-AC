<template>
    <div class="container-fluid">
        <div v-if="loading">
            <loading></loading>
        </div>
        <div
            v-else
            class="row justify-content-center p-2 pt-lg-4"
        >
            <div class="col-12 col-lg-8 col-xl-6 bg-white shadow-sm rounded p-2">
                <div v-if="step === 0">
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
                            @click="toStepForward(1)"
                        >
                            Продолжить
                        </button>
                    </div>
                </div>
                <div v-if="step === 1">
                    <div class="text-center"><h3>Выбор роли</h3>Нам нужно знать, для чего вы здесь :)</div>
                    <p class="mt-2 px-2 px-xl-5">
                        Важно понимать, зачем Вы здесь, быть может, Вы родитель и хотите записать своего ребёнка в школу
                        или
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
                            @click="toStepBack(0)"
                        >
                            Назад
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="myRoles.length === 0"
                            @click="toStepForward(2)"
                        >
                            Продолжить
                        </button>
                    </div>
                </div>
                <div v-if="step === 2 && myRoles.indexOf(4) > -1">
                    <div class="text-center"><h3>Регистрируем детей</h3>Отлично, Вы выбрали роль "Родитель"</div>
                    <p class="mt-2 px-2 px-xl-5">
                        Теперь Вам необходимо добавить всех Ваших детей.
                    </p>
                    <p class="mt-2 px-2 px-xl-5">
                        Нажмите кнопку ниже и Вы увидете форму, где необходимо внести данные.
                    </p>
                    <p class="mt-2 px-2 px-xl-5">
                        На этом этапе необходимо выбрать, в каком классе учится Ваш ребенок. Дополнительное образование
                        мы выберем на следующем шаге.
                    </p>
                    <p class="mt-2 px-2 px-xl-5">
                        <b>Внимание!</b> При заполнении будьте предельно внимательны, ошибки недопустимы!
                    </p>
                    <div class="d-flex container-fluid justify-content-center mt-2">
                        <button
                            type="button"
                            class="btn btn-info"
                            @click="showAddForm()"
                        >
                            Добавить ученика
                        </button>
                    </div>
                    <div class="container-fluid row mt-3">
                        <div
                            v-for="student of students"
                            :key="student.id"
                            class="d-flex col-12 justify-content-center"
                        >
                            <div
                                :class="bgClass(student)"
                                class="card mt-2 rounded-0 border-0 shadow-sm"
                                style="width: 18rem;"
                            >
                                <div class="card-body">
                                    <div class="d-flex">
                                        <h5 class="mr-auto card-title">
                                            {{ student.f }} {{ student.i }} ({{student.gender === 1 ? 'М' : 'Ж'}})
                                        </h5>
                                        <button
                                            type="button"
                                            class="close align-self-start"
                                            aria-label="Close"
                                            @click="showConfirmRemoveWindow(student)"
                                        >
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="d-flex">
                                        <p class="mr-auto card-text">{{ student.o }}</p>
                                        <p class="card-text small">{{ parseDate(student.birthday) }}</p>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <h5>{{ getOrganizationName(student.organization) }}{{
                                            getDivisionName(student.division) }}</h5>
                                    </div>
                                    <button
                                        type="button"
                                        class="btn btn-warning mt-2 shadow-sm"
                                        @click="showEditForm(student)"
                                    >
                                        Редактировать
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex container-fluid justify-content-center mt-3">
                        <button
                            type="button"
                            class="btn btn-danger mr-2"
                            @click="toStepBack(1)"
                        >
                            Назад
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="students.length === 0"
                            @click="toStepForward(3)"
                        >
                            Продолжить
                        </button>
                    </div>
                </div>
                <div v-if="step === 3 && myRoles.indexOf(4) > -1 && students.length > 0">
                    <div class="text-center"><h3>Дополнительное образование</h3></div>
                    <p class="mt-3 px-2 px-xl-5">
                        Сейчас Вы сможете запросить доступ, для посещения ребёнком разлиных секций и кружков.
                    </p>
                    <p class="mt-2 px-2 px-xl-5">
                        Необходимо в списке слева выбрать ученика, а в списке справа учреждения, которые он посещяет.
                    </p>
                    <p class="mt-2 px-2 px-xl-5">
                        Для каждого ребенка Вы можете выбрать свой набор учреждений.
                    </p>
                    <div class="row justify-content-around">
                        <div class="col-12 col-sm-6 px-3 pl-sm-5 mb-2 mb-sm-0">
                            <div class="list-group">
                                <button
                                    v-for="student of students"
                                    type="button"
                                    class="list-group-item list-group-item-action d-flex align-items-center"
                                    :class="listGroupItemStudentClass(student)"
                                    @click="toggleCurrentStudent(student)"
                                >
                                    <input
                                        class="mr-1"
                                        type="radio"
                                        :checked="listGroupItemStudentChecked(student)"
                                    >
                                    <div class="flex-grow-1">{{ student.f }} {{ student.i }}</div>
                                    <span
                                        class="badge badge-pill"
                                        :class="badgeClass(student)"
                                    >
                                        {{ student.additionalOrganizations.length }}
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 px-3 pr-sm-5">
                            <div class="list-group">
                                <button
                                    v-for="organization of organizations"
                                    v-if="organization.type === 2"
                                    type="button"
                                    class="list-group-item list-group-item-action d-flex align-items-center"
                                    :class="listGroupItemOrgClass(organization)"
                                    @click="toggleOrganizationToStudent(organization)"
                                >
                                    <input
                                        class="mr-1"
                                        type="checkbox"
                                        :checked="listGroupItemOrgChecked(organization)"
                                    >
                                    <div class="flex-grow-1">{{ organization.name }}</div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex container-fluid justify-content-center mt-3">
                        <button
                            type="button"
                            class="btn btn-danger mr-2"
                            @click="toStepBack(2)"
                        >
                            Назад
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="students.length === 0"
                            @click="toStepForward(4)"
                        >
                            Продолжить
                        </button>
                    </div>
                </div>
                <div v-if="step === 4 && myRoles.indexOf(9) > -1">
                    <div class="text-center"><h3>Регистрируем себя</h3>Отлично, Вы выбрали роль "Сотрудник"</div>
                    <p class="mt-2 px-2 px-xl-5">
                        Теперь Вам необходимо зарегистрировать свою карту.
                    </p>
                    <p class="mt-2 px-2 px-xl-5">
                        Нажмите кнопку ниже и Вы увидете форму, где необходимо проверить данные, указанные при
                        регистрации, и дополнить анкету.
                    </p>
                    <p class="mt-2 px-2 px-xl-5">
                        При заполнении будьте предельно внимательны, ошибки недопустимы!
                    </p>
                    <div
                        v-if="userChecked"
                        class="container-fluid row mt-3"
                    >
                        <div class="d-flex col-12 justify-content-center">
                            <div
                                :class="bgClass(user)"
                                class="card mt-2 rounded-0 border-0 shadow-sm"
                                style="width: 18rem;"
                            >
                                <div class="card-body">
                                    <h5 class="mr-auto card-title">
                                        {{ user.f }} {{ user.i }} ({{user.gender === 1 ? 'М' : 'Ж'}})
                                    </h5>
                                    <div class="d-flex">
                                        <p class="mr-auto card-text">{{ user.o }}</p>
                                        <p class="card-text small">{{ parseDate(user.birthday) }}</p>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <h5>{{ getOrganizationName(user.organization) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex container-fluid justify-content-center mt-2">
                        <button
                            type="button"
                            class="btn btn-info"
                            @click="showRegistrationForm()"
                        >
                            {{ editButtonName }}
                        </button>
                    </div>
                    <div class="d-flex container-fluid justify-content-center mt-2">
                        <button
                            type="button"
                            class="btn btn-danger mr-2"
                            @click="toStepBack(3)"
                        >
                            Назад
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="! userChecked"
                            @click="toStepForward(5)"
                        >
                            Продолжить
                        </button>
                    </div>
                </div>
                <div v-if="step === 5">
                    <div class="text-center"><h3>Готово!</h3></div>
                    <p
                        v-if="! sending && sendingError"
                        class="text-center text-danger my-2"
                    >
                        Ошибка отправки! Попробуйте еще раз
                    </p>
                    <p
                        v-if="! sending && ! sendingError"
                        class="text-center my-2"
                    >
                        Осталось лишь отправить заявку.
                    </p>
                    <div
                        v-if="sending"
                        class="progress my-2"
                    >
                        <div
                            class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                            role="progressbar"
                            :style="{width: sendingProgress + '%'}"
                            :aria-valuenow="sendingProgress"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>
                    <div class="d-flex container-fluid justify-content-center mt-2">
                        <button
                            type="button"
                            class="btn btn-danger mr-2"
                            :disabled="sending"
                            @click="toStepBack(4)"
                        >
                            Назад
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="sending"
                            @click="sendDataToServer()"
                        >
                            Отправить
                        </button>
                    </div>
                </div>
                <div v-if="step === 6">
                    <div class="text-center"><h3>Данные отправлены!</h3>Вы будете автоматические перенаправлены на
                        главную страницу.
                    </div>
                    <p class="mt-2 px-2 px-xl-5">
                        Если перенаправление не прошло автоматически, нажмите на <a href="/">ссылку</a> или кнопку ниже.
                    </p>
                    <div class="d-flex container-fluid justify-content-center mt-2">
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="redirect"
                        >
                            Перейти
                        </button>
                    </div>
                </div>
            </div>
            <postreg-add-student-form windowType="windowType"></postreg-add-student-form>
            <postreg-confirm-remove-student></postreg-confirm-remove-student>
            <postreg-card-registration-form></postreg-card-registration-form>
        </div>
    </div>
</template>

<script>
    import Loading from './Loading'
    import PostregAddStudentForm from './PostregAddStudentForm'
    import PostregConfirmRemoveStudent from './PostregConfirmRemoveStudent'
    import PostregCardRegistrationForm from './PostregCardRegistrationForm'

    export default {
        name: "AcPostreg",

        components: {Loading, PostregAddStudentForm, PostregConfirmRemoveStudent, PostregCardRegistrationForm},

        data() {
            return {
                sending: false,
                sendingError: false,
                sendingTimer: null,
                sendingProgress: 0
            }
        },

        computed: {
            loading() {
                return this.$store.state.postreg.loading
            },

            step() {
                return this.$store.state.postreg.step
            },

            myRoles() {
                return this.$store.state.postreg.myRoles
            },

            organizations() {
                return this.$store.getters['postreg/getSortedOrganizations']
            },

            roles() {
                return this.$store.state.postreg.roles
            },

            student() {
                return this.$store.state.postreg.currentStudent
            },

            students() {
                return this.$store.getters['postreg/getSortedStudents']
            },

            user() {
                return this.$store.state.postreg.user
            },

            userChecked() {
                return this.$store.state.postreg.userChecked
            },

            editButtonName() {
                if (this.userChecked) {
                    return 'Редактировать анкету'
                } else {
                    return 'Заполнить анкету'
                }
            }
        },

        methods: {
            toStepForward(step) {
                if (step === 2 && this.myRoles.indexOf(4) === -1) {
                    step = 4
                }
                if (step === 4) {
                    this.$store.commit('postreg/clearCurrentStudent')
                }
                if (step === 4 && this.myRoles.indexOf(9) === -1) {
                    step = 5
                }
                if (step === 6) {
                    setTimeout(this.redirect, 5000)
                }

                this.$store.commit('postreg/toStep', step)
            },

            toStepBack(step) {
                if (step === 2) {
                    this.$store.commit('postreg/clearCurrentStudent')
                }
                if (step === 4 && this.myRoles.indexOf(9) === -1) {
                    step = 3
                }
                if (step === 3 && this.myRoles.indexOf(4) > -1 && this.students.length === 0) {
                    step = 2
                }
                if (step === 3 && this.myRoles.indexOf(4) === -1) {
                    step = 1
                }
                this.$store.commit('postreg/toStep', step)
            },

            toggleRole(role) {
                if (this.myRoles.indexOf(role) === -1) {
                    this.$store.commit('postreg/addRole', role)
                } else {
                    this.$store.commit('postreg/removeRole', role)
                }
            },

            buttonClass(roleType) {
                return {
                    'btn-success': this.myRoles.indexOf(roleType) > -1,
                    'btn-secondary': this.myRoles.indexOf(roleType) === -1
                }
            },

            showAddForm() {
                this.student.id = this.$store.getters['postreg/studentsCount'] + 1
                this.$store.commit('postreg/setStudentFormType', 'add')
                $('#addStudentForm').modal('show')
            },

            showEditForm(student) {
                this.$store.commit('postreg/setStudentFormType', 'edit')
                this.$store.commit('postreg/setCurrentStudent', student)
                $('#addStudentForm').modal('show')
            },

            showRegistrationForm() {
                $('#cardRegistrationForm').modal('show')
            },

            showConfirmRemoveWindow(student) {
                this.$store.commit('postreg/setCurrentStudent', student)
                $('#confirmRemoveStudent').modal('show')
            },

            parseDate(dateString) {
                let date = new Date(dateString)
                let options = {
                    year: 'numeric',
                    month: 'numeric',
                    day: 'numeric'
                };
                return date.toLocaleString("ru", options)
            },

            getOrganizationName(organizationId) {
                if (organizationId === 0) return 'Н/Д'
                return this.$store.getters['postreg/getOrganizationById'](organizationId).name
            },

            getDivisionName(divisionId) {
                let name = ' - '
                if (divisionId === 0) {
                    name += 'Н/Д'
                } else {
                    name += this.$store.getters['postreg/getDivisionById'](divisionId).name
                }
                return name
            },

            bgClass(student) {
                return {
                    'postreg-bg-men': student.gender === 1,
                    'postreg-bg-women': student.gender === 2
                }
            },

            toggleCurrentStudent(student) {
                if (this.student.id === student.id) {
                    this.$store.commit('postreg/clearCurrentStudent')
                } else {
                    this.$store.commit('postreg/setCurrentStudent', student)
                }
            },

            toggleOrganizationToStudent(organization) {
                let org = this.student.additionalOrganizations.find(org => (org.id === organization.id))
                if (org === undefined) {
                    this.student.additionalOrganizations.push(organization)
                } else {
                    let index = this.student.additionalOrganizations.indexOf(org)
                    this.student.additionalOrganizations.splice(index, 1)
                }
            },

            badgeClass(student) {
                return {
                    'badge-success': student.additionalOrganizations.length > 0,
                    'badge-secondary': student.additionalOrganizations.length === 0
                }
            },

            listGroupItemStudentChecked(student) {
                return this.student.id === student.id
            },

            listGroupItemStudentClass(student) {
                return {
                    'active': this.listGroupItemStudentChecked(student)
                }
            },

            listGroupItemOrgChecked(organization) {
                return this.student.additionalOrganizations.indexOf(organization) > -1
            },

            listGroupItemOrgClass(organization) {
                return {
                    'active': this.listGroupItemOrgChecked(organization),
                    'disabled': this.student.id === 0
                }
            },

            sendDataToServer() {
                this.sending = true
                this.sendingError = false
                this.sendingProgress = 0
                this.sendingProgressing()
                this.sendingTimer = setInterval(this.sendingProgressing, 1000)
                this.$store.dispatch('postreg/sendDataToServer')
                    .then(() => {
                        clearInterval(this.sendingTimer)
                        this.toStepForward(6)
                    })
                    .catch(error => {
                        if (this.$store.state.debug) console.log(error)
                        this.sendingError = true
                    })
                    .finally(() => {
                        this.sending = false
                        this.sendingProgress = 100
                    })
            },

            sendingProgressing() {
                if (this.sendingProgress < 100) {
                    this.sendingProgress += 20
                } else if (this.sendingError === true) {
                    clearInterval(this.sendingTimer)
                    this.sending = false
                } else {
                    this.sendingError = true
                }
            },

            redirect() {
                window.location.href = '/'
            }
        },

        mounted() {
            $('#addStudentForm').modal({
                show: false
            })
            $('#confirmRemoveStudent').modal({
                show: false
            })
            $('#cardRegistrationForm').modal({
                show: false
            })
            $(document).on('hidden.bs.modal', '#addStudentForm', () => {
                if (this.$store.state.postreg.studentFormType === 'edit') {
                    this.$store.commit('postreg/revertStudent')
                    // if (this.$store.state.postreg.currentStudent.code !== this.$store.state.postreg.studentToUpdate.code) {
                    //     this.$store.commit('postreg/setCodeActivatedStatus', {
                    //         codeId: this.$store.state.postreg.studentToUpdate.code,
                    //         activated: 0
                    //     })
                    // }
                }
                this.$store.commit('postreg/clearCurrentStudent')
            })
            $(document).on('hidden.bs.modal', '#confirmRemoveStudent', () => {
                this.$store.commit('postreg/clearCurrentStudent')
            })

            this.$store.dispatch('postreg/loadUserInfo')

            this.$store.dispatch('postreg/loadOrganizations', 2)
                .then(response => {
                    for (let organization of response) {
                        this.$store.commit('postreg/addOrganization', organization)
                    }
                })
                .catch(error => {
                    if (this.$store.state.debug) console.log(error)
                })
        }
    }
</script>

<style scoped>

</style>
