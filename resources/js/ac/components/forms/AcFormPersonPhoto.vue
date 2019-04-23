<template>
    <div class="position-relative h-auto w-auto d-inline-block">
        <img
            class="img-fluid rounded shadow ac-person-photo"
            :src="url"
            alt=""
        >
        <div
            v-if="selectedPerson.id !== null && photo.id === 0"
            class="h-100 w-100 d-flex align-items-center ac-person-photo-content"
        >
            <div class="w-100">
                <p class="h1 text-center"><strong>{{ $t('ac.press_') }}</strong></p>
                <p class="text-center"><strong>{{ $t('ac._to_upload') }}</strong></p>
            </div>

        </div>
        <input
            v-if="selectedPerson.id !== null && photo.id === 0"
            type="file"
            class="h-100 w-100 ac-person-photo-content ac-person-photo-upload"
            accept="image/jpeg,image/png"
            title=""
            @change="uploadPhoto($event.target.files)"
        >
        <div
            v-if="photo.id > 0"
            class="h-100 w-100 d-flex justify-content-end align-items-start p-1 ac-person-photo-content"
        >
            <button
                type="button"
                class="btn btn-sm btn-danger"
                @click="removeUploadedPhoto"
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
            selectedPerson() {
                return this.$store.state.persons.selected
            },

            photo() {
                let person = this.$store.state.persons.selected

                if (person.photos.length > 0) {
                    return person.photos[0]
                }

                return {id: 0, hash: 0}
            },

            url() {
                return '/photos/thumbnails/' + this.photo.hash + '.jpg'
            }
        },

        methods: {
            uploadPhoto(files) {
                let formData = new FormData()

                if (files.length === 0) return

                formData.append('file', files[0])

                window.axios({
                    method: 'post',
                    url: '/api/photos',
                    data: formData,
                    config: {headers: {'Content-Type': 'multipart/form-data'}}
                }).then(response => {
                    this.$store.commit('persons/addPhoto', response.data)
                }).catch(error => {
                    if (this.$store.state.debug) console.log(error)
                    this.$root.alert(error, 'danger')
                })
            },

            removeUploadedPhoto() {
                if (this.photo.person_id === null) {
                    window.axios.delete('/api/photos/' + this.photo.id).then(response => {
                        if (response.data > 0) {
                            this.$store.commit('persons/removePhoto', this.photo)
                        } else {
                            this.$root.alert('Unknown error', 'danger')
                        }
                    }).catch(error => {
                        if (this.$store.state.debug) console.log(error)
                        this.$root.alert(error, 'danger')
                    })
                } else {
                    this.$store.commit('persons/removePhoto', this.photo)
                }
            }
        }
    }
</script>

<style scoped>
    .ac-person-photo {
        max-height: 320px;
    }

    .ac-person-photo-upload {
        opacity: 0;
    }

    .ac-person-photo-content {
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
