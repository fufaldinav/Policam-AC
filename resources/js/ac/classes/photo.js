export function Photo(data) {
    this.id = 0;
    this.hash = null;
    this.person_id = 0;

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k];
        }
    }
}
