window.Person = function (data) {
    let cards = [];
    let divisions = [];
    let photos = [];
    this.id = 0;
    this.f = null;
    this.i = null;
    this.o = null;
    this.type = 1;
    this.birthday = null;
    this.address = null;
    this.phone = null;

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k];
        }
        if (k === 'cards') {
            for (let l in data[k]) {
                cards[l] = new Card(data[k][l]);
            }
        }
        if (k === 'divisions') {
            divisions = data[k];
        }
        if (k === 'photos') {
            for (let l in data[k]) {
                photos[l] = new Photo(data[k][l]);
            }
        }
    }

    this.cards = function (id) {
        if (id !== undefined) {
            let i = cards.indexOf(id);
            return cards[i];
        }
        return cards;
    }

    this.divisions = function (id) {
        if (id !== undefined) {
            let i = divisions.indexOf(id);
            return divisions[i];
        }
        return divisions;
    }

    this.photos = function (id) {
        if (id !== undefined) {
            let i = photos.indexOf(id);
            return photos[i];
        }
        return photos;
    }

    this.save = function () {
        axios.post(`/api/persons`, {
            person: this,
            divisions: this.divisions(),
            cards: this.cards(),
            photos: this.photos()
        })
            .then(function (response) {
                for (let k in response.data) {
                    if (this.hasOwnProperty(k)) {
                        this[k] = data[k];
                    }
                }
            })
            .catch(function (error) {
                console.log(error);
            })
    }

    this.update = function () {
        axios.put(`/api/persons/${this.id}`, {
            person: this,
            divisions: this.divisions(),
            cards: this.cards(),
            photos: this.photos()
        })
            .then(function (response) {
                for (let k in response.data) {
                    if (this.hasOwnProperty(k)) {
                        this[k] = data[k];
                    }
                }
            })
            .catch(function (error) {
                console.log(error);
            })
    }

    this.delete = function () {
        axios.delete(`/api/persons/${this.id}`)
            .then(function (response) {
                delete this;
            })
            .catch(function (error) {
                console.log(error);
            })
    }
}
