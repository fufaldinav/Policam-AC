<template>
    <div class="list-group list-group-flush">
        <button
            type="button"
            class="list-group-item list-group-item-info"
            @click="cancel"
        >
            Назад
        </button>
        <li class="list-group-item">
            <input
                v-model="code"
                :class="searchResult"
                type="text" class="form-control" placeholder="Введите штрих-код" size="13" maxlength="13"
            >
            <div class="valid-feedback text-center">
                Штрих-код найден!
            </div>
            <div class="invalid-feedback">
                Штрих-код не найден!
            </div>
        </li>
        <button
            type="button"
            class="list-group-item list-group-item-success"
            @click="searchCode"
        >
            Найти
        </button>
    </div>
</template>

<script>
    export default {
        name: "AcCpPersonsSearchByCodeGroup",

        data() {
          return {
                code: null,
                person: null,
                searchCompleted: false
            }
        },

        components: {},

        computed: {
            searchResult() {
                return {
                    'is-valid': this.person !== null && this.searchCompleted,
                    'is-invalid': this.person === null && this.searchCompleted
                }
            },
        },

        methods: {
            cancel() {
                this.$store.commit('persons/setSearchByCode', false);
            },

            searchCode() {
                this.$store.dispatch('persons/searchByCode', this.code);
                this.searchCompleted = true
            }
        }
    }
</script>

<style scoped>

</style>
