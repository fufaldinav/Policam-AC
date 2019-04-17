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

    for (let k in data) {
        if (this.hasOwnProperty(k)) {
            this[k] = data[k];
        }
    }

    this.cards = [];
    this.photos = [];

    if (data.cards !== undefined) {
        for (let card of data.cards) {
            this.cards.push(new Card(card));
        }
    }

    if (data.photos !== undefined) {
        for (let photo of data.photos) {
            this.photos.push(new Photo(photo));
        }
    }
}
