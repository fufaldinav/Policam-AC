export function Event(data) {
    this.id = null
    this.controller_id = null
    this.event = null
    this.flag = null
    this.time = null
    this.card_id = null
    this.person_id = null;

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k]
        }
    }
}
