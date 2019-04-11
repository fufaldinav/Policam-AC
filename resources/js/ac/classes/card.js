export function Card(data) {
    this.id = 0;
    this.wiegand = null;
    this.last_conn = null;
    this.controller_id = null;
    this.person_id = 0;

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k];
        }
    }
}
