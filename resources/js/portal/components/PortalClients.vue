<template>
    <div class="section">
        <div class="main-clients" id="mainClients">
            <h2 class="wow fadeInUp" data-wow-duration="1s">НАШИ КЛИЕНТЫ</h2>
            <div class="title-line"></div>
            <div class="container">
                <div class="main-clients-content owl-carousel owl-theme owl-loaded">
                    <div
                        v-for="client of clients"
                        class="main-clients-item"
                    >
                        <img :src="client.img" alt="client.name">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BaesAuto from '../../../../public/img/clients/baes-auto.png'
    import GoChs from '../../../../public/img/clients/gochs.png'
    import ZarAdm from '../../../../public/img/clients/zar.png'
    import BelAdm from '../../../../public/img/clients/bel.png'
    import Mvd from '../../../../public/img/clients/mvd.png'
    import UralActive from '../../../../public/img/clients/uralactive.png'
    import Dez from '../../../../public/img/clients/dez.png'
    import Termoxid from '../../../../public/img/clients/termoxid.png'

    export default {
        name: "PortalClients",

        data() {
            return {
                clients: [
                    {name: 'Управление ГО и ЧС', img: GoChs},
                    {name: 'Администрация ГО Заречный', img: ZarAdm},
                    {name: 'БАЭС-Авто', img: BaesAuto},
                    {name: 'Минестерство внутренних дел', img: Mvd},
                    {name: 'УралАктив', img: UralActive},
                    {name: 'Администрация Белоярского района', img: BelAdm},
                    {name: 'ДЕЗ', img: Dez},
                    {name: 'Термоксид', img: Termoxid}
                ]
            }
        }
    }
</script>

<style scoped>

</style>
