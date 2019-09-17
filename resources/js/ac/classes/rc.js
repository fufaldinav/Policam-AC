export function ReferralCode(data) {
    this.id = null
    this.code = null
    this.user_id = null
    this.organization_id = 0
    this.activated = 0

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k]
        }
    }
}
