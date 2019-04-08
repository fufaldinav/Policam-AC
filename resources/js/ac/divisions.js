let division = {
    'name': null,
    'organization_id': null
};

let divisions = [];

window.openDivision = function() {

}

window.getDivisions = function () {
    axios.get(`/divisions/get_list`)
        .then(function (response) {
            let data = response.data;
            if (data.length > 0) {
                let divisions = ``;
                data.forEach(function (div) {
                    divisions += `<div id="div${div.id}" class="menu-item" onclick="getPersons(${div.id});">${div.name}</div>`;
                });
                let menu = document.getElementById(`menu`);
                menu.innerHTML = divisions;
            } else {
                alert(`Пустой ответ от сервера`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

//сохранить в базу
window.saveDivision = function (org_id) {
    let number = document.getElementById(`number`).value;
    let letter = document.getElementById(`letter`).value;
    if (!number || !letter) {
        alert(`Введены не все данные`); //TODO перевод
        return;
    }
    window.div.name = `${number} "${letter}"`;
    window.div.organization_id = org_id;
    axios.post(`/divisions/save`, {
        div: JSON.stringify(window.div)
    })
        .then(function (response) {
            alert(`Класс ${response.data.name} успешно сохранен`); //TODO перевод
            location.reload();
        })
        .catch(function (error) {
            console.log(error);
        });
}

//удалить из базы
window.deleteDivision = function (div_id) {
    if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
        return;
    }
    axios.post(`/divisions/delete`, {
        'div_id': div_id
    })
        .then(function (response) {
            if (response.data > 0) {
                alert(`Успешное удаление`); //TODO перевод
                location.reload();
            } else {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

window.setDiv = function (id) {
    let index = window.divs.indexOf(id);
    if (index === -1) {
        window.divs.push(id);
        document.getElementById(`div${id}`).classList.add(`checked`);
    } else {
        window.divs.splice(index, 1);
        document.getElementById(`div${id}`).classList.remove(`checked`);
    }
}
