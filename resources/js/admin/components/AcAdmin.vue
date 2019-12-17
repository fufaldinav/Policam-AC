<template>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">Серийный номер</th>
            <th scope="col">Тип</th>
            <th scope="col">Версия ПО</th>
            <th scope="col">Локальный IP</th>
            <th scope="col">Последнее соединение</th>
            <th scope="col">Организация</th>
            <th scope="col">Очередь событий / в ЧС</th>
            <th scope="col">Очередь сообщений / в ЧС</th>
            <th scope="col">Slave</th>
            <th scope="col">Тревога</th>
            <th scope="col">Ошибка SD</th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="ctrl of controllers"
            :key="ctrl.id"
        >
            <th scope="row">{{ ctrl.id }}</th>
            <td>{{ ctrl.sn }}</td>
            <td>{{ ctrl.type }}</td>
            <td>{{ ctrl.fw }}</td>
            <td>{{ ctrl.ip }}</td>
            <td>{{ ctrl.last_conn }}</td>
            <td>{{ ctrl.organization_id }}</td>
            <td>{{ ctrl.events_queue }} / {{ ctrl.events_bl }}</td>
            <td>{{ ctrl.messages_queue }} / {{ ctrl.messages_bl }}</td>
            <td>{{ ctrl.devices.length }}</td>
            <td>
                <img v-if="ctrl.alarm === 1" :src="Alarm1" alt="">
                <img v-else :src="Alarm0" alt="">
            </td>
            <td>
                <img v-if="ctrl.sd_error === 1" :src="SdError1" alt="">
                <img v-else :src="SdError0" alt="">
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import Loading from './Loading'
    import SdError0 from '../../../../public/img/admin/sd-error-0.png'
    import SdError1 from '../../../../public/img/admin/sd-error-1.png'
    import Alarm0 from '../../../../public/img/admin/alarm-0.png'
    import Alarm1 from '../../../../public/img/admin/alarm-1.png'

    export default {
        name: "AcAdmin",

        data() {
            return {
                SdError0, SdError1, Alarm0, Alarm1
            }
        },

        components: {
            Loading
        },

        computed: {
            controllers() {
                return this.$store.state.admin.controllers
            },
        },

        mounted() {
            this.$store.dispatch('admin/loadControllers')
                .then(response => {
                    for (let controller of response) {
                        this.$store.commit('admin/addController', controller)
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
