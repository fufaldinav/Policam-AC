<template>
    <div>
        <div class="section" style="padding-top: 0px;">
            <div class="main-history" id="mainHistory">
                <div class="main-history-bg"></div>
                <h2 class="wow fadeInUp" data-wow-duration="1s">ИСТОРИЯ УСПЕХА</h2>
                <div class="title-line"></div>
                <div class="main-history-content owl-carousel owl-history owl-theme owl-loaded">
                    <div
                        v-for="entry of entries"
                        class="container"
                    >
                        <div class="row justify-content-center">
                            <div class="col-sm-5">
                                <div class="main-history-item">
                                    <img :src="MainHistoryProduct" alt="История успеха">
                                    <h3>{{ entry.year }} год</h3>
                                    <p>{{ entry.description }}</p>
                                    <div class="history-button mt-4">
										<span class="main-button-link">
											<a
                                                href=""
                                                @click.prevent="showDetails(entry)"
                                            >
                                                Узнать подробнее
                                            </a>
										</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="historyDetails" tabindex="-1" role="dialog" aria-labelledby="historyDetailsTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content rounded-0 shadow-lg" style="border: 0;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="historyDetailsTitle">История успеха - <b>{{ detailsWindow.year }}</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-justify" v-html="detailsWindow.details">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MainHistoryProduct from '../../../../public/img/templates/main-history/main-history-product.png'

    export default {
        name: "PortalHistory",

        data() {
            return {
                MainHistoryProduct,
                entries: [
                    {
                        year: 2014,
                        description: 'Оборудование собственной серверной',
                        details: '<p class="portal-text-indent"><b>2014 год</b> — оборудовали собственную серверную, что позволило организовать защищенное хранение данных видео архивов и их просмотр по защищенным каналам связи по территории Свердловской области. Это помогло построить систему эффективной работы с многоквартирными домами, офисами, производственными предприятиями.</p>' +
                            '<b>Что получил пользователь:</b>' +
                            '<ul><li>Экономию на установке серверного оборудования</li>' +
                            '<li>Свобода от необходимости следить за хранением данных, проводить ТО и мониторинг оборудования</li>' +
                            '<li>Нет затрат на ремонт и восстановление изношенного оборудования</li>' +
                            '<li>Постоянное обновление сервера и программного обеспечения</li></ul>'
                    },
                    {
                        year: 2016,
                        description: 'Объединение всех обслуживаемых серверов в общую сеть',
                        details: '<p class="portal-text-indent"><b>2016 год</b> — объединение всех обслуживаемых серверов в общую сеть.</p>' +
                            '<b>Полученный результат:</b>' +
                            '<ul><li>Оптимальное распределение нагрузки каналов связи между серверами</li>' +
                            '<li>К любой камере, подключенной к серверам, появилась возможность использовать модули видеоаналитики.</li></ul>'
                    },
                    {
                        year: 2017,
                        description: 'Разработка собственного ПО для работы с картографическими модулями',
                        details: '<p class="portal-text-indent"><b>2017 год</b> — разработали программное обеспечение, интегрированное с картографическими модулями и функционалом работы с определяемыми автомобильными номерами. Это позволило наглядно отобразить местонахождение камер видеонаблюдения, видеопоток, направление и зону покрытия каждой камеры.</p>' +
                            '<p class="portal-text-indent">Так мы получили функциональное программное обеспечение, необходимое для полноценной работы ЕДДС и полиции.</p>' +
                            '<b>Дополнительные функции данного ПО:</b>' +
                            '<ul><li>Выведение всех камер в одно окно, где все отображается в понятном виде вне зависимости от количества.</li>' +
                            '<li>Получение наглядной информации о месторасположении каждой камеры.</li>' +
                            '<li>•\tУдобная система поиска автомобиля по базе данных по гос. номеру.</li></ul>'
                    },
                    {
                        year: 2017,
                        description: 'Разработка для ГО и ЧС мобильных и автономных комплексов для работы с PTZ камерами, металл-детекторами, камерами с детекцией лиц',
                        details: '<p class="portal-text-indent"><b>2017 год</b> — в рамках задачи по требованию увеличения безопасности в местах массового скопления людей, разработали для ГО и ЧС мобильные и автономные комплексы для работы с PTZ камерами, металл-детекторами, камерами с детекцией лиц с возможностью дальнейшей передачи данных в ситуационный центр.</p>' +
                            '<b>Особенности комплекса:</b>' +
                            '<ul><li>Моноблочная система с возможностью подключение IP видеокамер, системы речевого оповещения, металл-детектора</li>' +
                            '<li>Способен в течении 8 - 24 часов вести автономную работу, передавая данные через VPN защищенный канал связи оператору</li>' +
                            '<li>Быстро заряжается от сети 220 или автомобильного генератора</li></ul>'
                    },
                    {
                        year: 2018,
                        description: 'Организация сложной установки камер видеонаблюдения для ГО и ЧС Белоярского Района в местах с трудным доступом',
                        details: '<p class="portal-text-indent"><b>2018 год</b> — организовали сложную установку камер видеонаблюдения для ГО и ЧС Белоярского Района в местах с трудным доступом к электропитанию и отсутствием стабильных каналов связи. С помощью видеокамер был направлен непрерывный видеопоток на нестабильном канале связи, используя кэширование данных на локальном оборудовании.</p>' +
                            '<b>Полученный результат:</b>' +
                            '<ul><li>Постепенная выгрузка видео-архива за счет использования Onvif-протокола, даже при нестабильном соединении.</li>' +
                            '<li>Увеличили ширину канала связи в 1,5-2 раза для одновременной online-трансляции и выгрузкой пропущенного архива.</li></ul>'
                    },
                    {
                        year: 2019,
                        description: 'Выпуск собственных контроллеров СКУД',
                        details: '<p class="portal-text-indent"><b>2019 год</b> — выпуск контроллеров СКУД с интерфейсами RS485, Ethernet и возможностью управления сетевыми источниками бесперебойного питания.</p>' +
                            '<b>Особенности оборудования:</b>' +
                            '<ul><li>Оптимально подходят для работы с пожарной сигнализацией, системой контроля и управления доступом (турникетов, домофонов, шлагбаумов и т.д.)</li>' +
                            '<li>Надежно поддерживают функционирование температурных датчиков, датчиков состояния дверей, шлагбаума, прохода турникета.</li>' +
                            '<li>Бесперебойно работают в единой кросс-платформенной среде, без дополнительного ПО.</li></ul>'
                    }
                ],
                detailsWindow: {
                    year: 0,
                    description: '',
                    details: ''
                }
            }
        },

        methods: {
            showDetails(entry) {
                this.detailsWindow = entry
                $('#historyDetails').modal('show')
            }
        },

        mounted() {
            $('#historyDetails').modal({
                show: false
            })
            $(document).on('hidden.bs.modal', '#historyDetails', function () {
                this.detailsWindow = {year: 0, description: '', details: ''}
            })
        }
    }
</script>

<style scoped>

</style>
