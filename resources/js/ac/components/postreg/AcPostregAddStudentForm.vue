<template>
    <div class="modal fade" id="addStudentForm" tabindex="-1" role="dialog" aria-labelledby="addStudentFormTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавление ребёнка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-f">Фамилия</span>
                        </div>
                        <input
                            v-model="student.f"
                            type="text"
                            class="form-control"
                            :class="checkInputForFClass(student.f)"
                            placeholder="Например, Иванов"
                            aria-label="Фамилия"
                            aria-describedby="student-f"
                            required
                        >
                        <div class="invalid-feedback">
                            Фамилия должна начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-i">Имя</span>
                        </div>
                        <input
                            v-model="student.i"
                            type="text"
                            class="form-control"
                            :class="checkInputClass(student.i)"
                            placeholder="Например, Иван"
                            aria-label="Имя"
                            aria-describedby="student-i"
                            required
                        >
                        <div class="invalid-feedback">
                            Имя должно начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-o">Отчество</span>
                        </div>
                        <input
                            v-model="student.o"
                            type="text"
                            class="form-control"
                            :class="checkInputClass(student.o)"
                            placeholder="Например, Иванович"
                            aria-label="Отчество"
                            aria-describedby="student-o"
                            required
                        >
                        <div class="invalid-feedback">
                            Отчество должно начинаться с большой буквы и содержать только буквы русского алфавита
                        </div>
                    </div>
                    <div class="container-fluid justify-content-center d-flex mb-3">
                        <div class="form-check form-check-inline">
                            <input
                                v-model="student.gender"
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="student-boy"
                                value="0"
                                required
                            >
                            <label class="form-check-label" for="student-boy">Мальчик</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                v-model="student.gender"
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="student-girl"
                                value="1"
                                required
                            >
                            <label class="form-check-label" for="student-girl">Девочка</label>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-birthday">Дата рождения</label>
                        </div>
                        <input
                            v-model="student.birthday"
                            id="student-birthday"
                            type="date"
                            class="form-control"
                            placeholder="Например, 24.10.2010"
                            required
                        >
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-card">Карта/браслет</label>
                        </div>
                        <select
                            v-model="student.card"
                            class="custom-select"
                            id="student-card"
                            required
                        >
                            <option disabled value="">Выберите карту...</option>
                            <option
                                v-for="card of cards"
                                :value="card.id"
                            >
                                {{ card.code }}
                            </option>
                        </select>
                    </div>
                </div>
                <p
                    v-if="buttonDisabled"
                    class="text-center"
                >
                    Заполнены не все поля!
                </p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button
                        v-if="windowType == 'add'"
                        type="button"
                        class="btn btn-primary"
                        :disabled="buttonDisabled"
                        @click="addStudent()"
                    >
                        Добавить
                    </button>
                    <button
                        v-if="windowType == `edit`"
                        type="button"
                        class="btn btn-primary"
                        :disabled="buttonDisabled"
                        @click="addStudent()"
                    >
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcPostregAddStudentForm",

        computed: {
            student() {
                return this.$store.state.postreg.currentStudent
            },

            cards() {
                return this.$store.state.postreg.cards
            },

            buttonDisabled() {
                return this.student.f == null || this.student.i == null || this.student.o == null || this.student.gender == null || this.student.birthday == null || this.student.card == null ||
                    this.student.f == '' || this.student.i == '' || this.student.o == '' || this.student.gender == '' || this.student.birthday == '' || this.student.card == ''
                || ! this.checkInputForF(this.student.f) || ! this.checkInput(this.student.i) || ! this.checkInput(this.student.o)
            },

            buttonDisabledTooltip() {
                if (this.buttonDisabled) {
                    return 'Введены не все поля'
                }
            },

            windowType() {
                return this.$store.state.postreg.studentFormType
            }
        },

        methods: {
            addStudent() {
                this.$store.commit('postreg/addStudent', this.student)
                $('#addStudentForm').modal('hide')
            },

            checkInputForF(input) {
                let reg = /^[А-ЯЁ][а-яё]+[-]*[А-ЯЁа-яё]*$/
                return reg.test(input)
            },

            checkInput(input) {
                let reg = /^[А-ЯЁ][а-яё]+$/
                return reg.test(input)
            },

            checkInputClass(input) {
                return {
                    'is-invalid': input !== null && input !== '' && ! this.checkInput(input) && input.length >= 2
                }
            },

            checkInputForFClass(input) {
                return {
                    'is-invalid': input !== null && input !== '' && ! this.checkInputForF(input) && input.length >= 2
                }
            },
        }
    }
</script>

<style scoped>

</style>
