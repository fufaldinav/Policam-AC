import {Card, Photo} from './';

export function Person(data) {
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
        return axios.post(`/api/persons`, {
            person: this,
            divisions: this.divisions(),
            cards: this.cards(),
            photos: this.photos()
        })
            .then(function (response) {
                return response.data;
            })
            .catch(function (error) {
                Ac.alert(error, `danger`);
                return null;
            })
    }

    this.update = function () {
        return axios.put(`/api/persons/${this.id}`, {
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
                return response.data;
            })
            .catch(function (error) {
                Ac.alert(error, `danger`);
                return null;
            })
    }

    this.delete = function () {
        return axios.delete(`/api/persons/${this.id}`)
            .then(function (response) {
                return response.data;
            })
            .catch(function (error) {
                Ac.alert(error, `danger`);
                return null;
            })
    }
}
