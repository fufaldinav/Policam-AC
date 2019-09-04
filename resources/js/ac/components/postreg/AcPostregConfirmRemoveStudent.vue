<template>
    <div class="modal fade" id="confirmRemoveStudent" tabindex="-1" role="dialog"
         aria-labelledby="confirmRemoveStudentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRemoveStudentLabel">Подтвердите удаление</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Вы действительно хотите удалить ученика?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        @click="removeStudent"
                    >
                        Удалить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcPostregConfirmRemoveStudent",

        computed: {
            student() {
                return this.$store.state.postreg.currentStudent
            }
        },

        methods: {
            removeStudent() {
                this.$store.commit('postreg/removeStudent', this.student)
                $('#confirmRemoveStudent').modal('hide')
            }
        }
    }
</script>

<style scoped>

</style>
