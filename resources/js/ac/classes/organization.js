export function Organization(data) {
    this.id = 0
    this.name = null
    this.type = 1

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k]
        }
    }

    this.controllers = []

    if (data.controllers !== undefined) {
        for (let controller of data.controllers) {
            this.controllers.push(controller.id)
        }
    }
}
