<template>
    <div class="section">
        <div class="main-clients" id="mainPartners">
            <h2 class="wow fadeInUp" data-wow-duration="1s">НАШИ ПАРТНЁРЫ</h2>
            <div class="title-line"></div>
            <div class="container">
                <div class="main-clients-content owl-carousel owl-theme owl-loaded">
                    <div
                        v-for="partner of partners"
                        class="main-clients-item"
                    >
                        <img :src="partner.img" alt="partner.name">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Dahua from '../../../../public/img/partners/dahua.jpg'
    import Devline from '../../../../public/img/partners/devline.jpg'
    import Hikvision from '../../../../public/img/partners/hikvision.png'
    import IronLogic from '../../../../public/img/partners/ironlogic.jpg'
    import Macroscop from '../../../../public/img/partners/macroscop.png'
    import Sigur from '../../../../public/img/partners/sigur.jpg'
    import Trassir from '../../../../public/img/partners/trassir.jpg'

    export default {
        name: "PortalPartners",

        data() {
            return {
                partners: [
                    {name: 'Dahua', img: Dahua},
                    {name: 'Devline', img: Devline},
                    {name: 'Hikvision', img: Hikvision},
                    {name: 'IronLogic', img: IronLogic},
                    {name: 'Macroscop', img: Macroscop},
                    {name: 'Sigur', img: Sigur},
                    {name: 'Trassir', img: Trassir}
                ]
            }
        }
    }
</script>

<style scoped>

</style>
