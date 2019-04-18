<template>
    <div class="ac-person-photo-container">
        <img
            class="img-fluid rounded shadow mx-auto d-block mx-2 ac-person-photo"
            :src="url"
            alt=""
        >
        <div
            v-if="photo !== 0"
            class="ac-person-photo-remove"
        >
            <button
                type="button"
                class="btn btn-sm btn-danger"
            >
                {{ $t('ac.delete') }}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AcFormPersonPhoto",
        computed: {
            photo() {
                let person = this.$store.state.persons.selected;

                if (person.photos.length > 0) {
                    return person.photos[0].hash;
                }

                return 0;
            },
            url() {
                return '/photos/' + this.photo + '.jpg';
            }
        }
    }
</script>

<style scoped>
    .ac-person-photo-container {
        position: relative;
    }

    .ac-person-photo {
        max-height: 320px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position-x: 50%;
        background-position-y: 50%;
    }

    .ac-person-photo-remove {
        position: absolute;
        left: 50%;
        bottom: -10px;
        margin-right: -50%;
        transform: translate(-50%, -50%)
    }
</style>
