window.div = {
    'name': null,
    'organization_id': null
};

//удалить из базы
window.deleteDivision = function (div_id) {
    if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
        return;
    }
    axios.post(process.env.MIX_APP_URL + `/divisions/delete`, {
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
    axios.post(process.env.MIX_APP_URL + `/divisions/save`, {
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
