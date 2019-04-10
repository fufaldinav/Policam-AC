window.Division = function (data) {
    let persons = [];
    this.id = 0;
    this.name = null;
    this.organization_id = null;
    this.type = 1;

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k];
        }
        if (k === 'persons') {
            persons = data[k];
        }
    }

    this.persons = function (id) {
        if (id !== undefined) {
            let i = persons.indexOf(id);
            return persons[i];
        }
        return persons;
    }

    this.addPerson = function (id) {
        if (persons.indexOf(id) === -1) {
            persons.push(id);
        }
    }

    this.deletePerson = function (id) {
        let index = persons.indexOf(id);
        persons.splice(index, 1);
    }
}
