let photos = [];

//загрузка фото
window.handleFiles = function (files) {
    let formData = new FormData();
    formData.append(`file`, files[0]);
    axios({
        method: `post`,
        url: `photos/save`,
        data: formData,
        config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
        .then(function (response) {
            let data = response.data;
            if (data) {
                if (data.error === ``) {
                    document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/' + data.id + '.jpg)';
                    photos.unshift(data.id);
                    document.getElementById(`photo`).hidden = true;
                    document.getElementById(`photo_del`).hidden = false;
                    document.getElementById(`photo_del`).onclick = deletePhoto;
                } else {
                    document.getElementById(`photo`).value = null;
                    alert(data.error);
                }
            } else {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

//удаление фото
window.deletePhoto = function () {
    if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
        return;
    }
    axios.post(`photos/delete`, {
        photo_id: photos.shift()
    })
        .then(function (response) {
            if (response.data) {
                document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
                document.getElementById(`photo_del`).hidden = true;
                document.getElementById(`photo_del`).onclick = function () {
                    return false;
                };
                document.getElementById(`photo`).hidden = false;
                document.getElementById(`photo`).value = null;
            } else {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}
