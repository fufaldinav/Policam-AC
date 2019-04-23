export function Division(data) {
    this.id = 0
    this.name = null
    this.organization_id = null
    this.type = 1

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k]
        }
    }

    this.persons = []

    if (data.persons !== undefined) {
        for (let person of data.persons) {
            this.persons.push(person.id)
        }
    }
}
