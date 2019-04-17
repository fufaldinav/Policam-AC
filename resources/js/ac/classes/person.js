import {Card, Photo} from './';

export function Person(data) {
    this.id = null;
    this.f = null;
    this.i = null;
    this.o = null;
    this.type = 1;
    this.birthday = null;
    this.address = null;
    this.phone = null;
    this.divisions = [];

    this.cards = [];
    this.photos = [];

    if (data.cards !== undefined) {
        for (let card of data.cards) {
            this.cards.push(new Card(card));
        }
        delete data.cards;
    }

    if (data.photos !== undefined) {
        for (let photo of data.photos) {
            this.photos.push(new Photo(photo));
        }
        delete data.photos;
    }

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k];
        }
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
