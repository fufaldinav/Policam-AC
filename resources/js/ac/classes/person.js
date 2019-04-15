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

    this.getCards = function (id) {
        if (id !== undefined) {
            let i = cards.indexOf(id);
            return cards[i];
        }
        return cards;
    }

    this.getDivisions = function (id) {
        if (id !== undefined) {
            let i = divisions.indexOf(id);
            return divisions[i];
        }
        return divisions;
    }

    this.getPhotos = function (id) {
        if (id !== undefined) {
            let i = photos.indexOf(id);
            return photos[i];
        }
        return photos;
    }

    this.addDivision = function(id) {
        divisions.push(parseInt(id));
    }

    this.save = function () {
        let self = this;
        return axios.post(`/api/persons`, {
            person: self,
            divisions: divisions,
            cards: cards,
            photos: photos
        })
            .then(function (response) {
                let data = response.data;
                for (let k in data) {
                    if (self.hasOwnProperty(k)) {
                        self[k] = data[k];
                    }
                }
                return self;
            })
            .catch(function (error) {
                Ac.alert(error, `danger`);
                return null;
            })
    }

    this.update = function () {
        let self = this;
        return axios.put(`/api/persons/${self.id}`, {
            person: self,
            divisions: divisions,
            cards: cards,
            photos: photos
        })
            .then(function (response) {
                let data = response.data;
                for (let k in data) {
                    if (self.hasOwnProperty(k)) {
                        self[k] = data[k];
                    }
                }
                return self;
            })
            .catch(function (error) {
                Ac.alert(error, `danger`);
                return null;
            })
    }

    this.delete = function () {
        let self = this;
        return axios.delete(`/api/persons/${self.id}`)
            .then(function (response) {
                return response.data;
            })
            .catch(function (error) {
                Ac.alert(error, `danger`);
                return null;
            })
    }
}
