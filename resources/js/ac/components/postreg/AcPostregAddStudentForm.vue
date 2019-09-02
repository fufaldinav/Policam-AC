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
                        <input type="text" class="form-control" placeholder="Например, Иванов" aria-label="Фамилия"
                               aria-describedby="student-f">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-i">Имя</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Например, Иван" aria-label="Имя"
                               aria-describedby="student-i">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="student-o">Отчество</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Например, Иванович" aria-label="Отчество"
                               aria-describedby="student-o">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-birthday">Дата рождения</label>
                        </div>
                        <input
                            id="student-birthday"
                            type="date"
                            class="form-control"
                            placeholder="Например, 24.10.2010"
                            required
                        >
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="student-card">Карта/браслет</label>
                        </div>
                        <select class="custom-select" id="student-card">
                            <option selected>Выберите карту...</option>
                            <option
                                v-for="card of cards"
                                value="card.id"
                            >
                                {{ card.code }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="addStudent()"
                    >
                        Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcPostregAddStudentForm",

        data() {
            return {
                students() {
                    return undefined;
                }
            }
        },

        computed: {
            cards() {
                return this.$store.state.postreg.cards
            }
        }
    }
</script>

<style scoped>

</style>
