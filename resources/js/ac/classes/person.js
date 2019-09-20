import {Photo, ReferralCode} from './'

export function Person(data) {
    this.id = null
    this.f = null
    this.i = null
    this.o = null
    this.gender = null
    this.type = 1
    this.birthday = null
    this.address = null
    this.phone = null
    this.division = null
    this.organization_id = 0

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k]
        }
    }

    if (data.photos !== undefined && data.referral_code !== null) {
        this.referral_code = new ReferralCode(data.referral_code)
    } else {
        this.referral_code = new ReferralCode({})
    }

    this.photos = []
    this.photosToDelete = []

    if (data.photos !== undefined) {
        for (let photo of data.photos) {
            this.photos.push(new Photo(photo))
        }
    }
}
