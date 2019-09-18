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
    this.divisions = []
    this.divisionsToDelete = []
    this.organizations = {basic: null, additional: []}

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k]
        }
    }

    if (data.referral_code !== undefined && data.referral_code !== null) {
        this.referral_code = new ReferralCode(data.referral_code)
    } else {
        this.referral_code = new ReferralCode({})
    }

    this.photos = []
    this.photosToDelete = []
    this.users = []
    this.usersToDelete = []

    if (data.photos !== undefined) {
        for (let photo of data.photos) {
            this.photos.push(new Photo(photo))
        }
    }

    if (data.users !== undefined) {
        for (let user of data.users) {
            this.users.push(user.id)
        }
    }
}
