
/*
*isRedyStart() - Проверяет доступность полей формы (DOMContentLoaded  не работает)
*getId('dealBitr') - получает ID сделки/клиента
*getds() собирает поля в масив  arrFil
*getBtrInfo(btrDealId, arrFil) - отправляет запрос btrDealId - id сделки/клиента arrFil -массив с полями
*
*/




let countRedy = 0;//количество попыток соединения

isRedyStart()

function isRedyStart() {

    let name = document.querySelector("input[formControlName='name']")
    if (name) {
        console.log(document.readyState)
        getds()
    } else if (!name && countRedy < 5) {//пробую не больше 5 раз
        countRedy++;
        console.log('timeout');
        console.log(document.readyState)
        setTimeout(isRedyStart, 1000);//пробую снова
    };
}




function getds() {//собирает поля для вставки

    const btrDealId = getId('dealBitr'); // получаю ID сделки/клиента из GET параметра  dealBitr

    if (btrDealId) {

        const name = document.querySelector("input[formControlName='name']");
        const surname = document.querySelector("input[formControlName='surname']");
        const nip = document.querySelector("input[formControlName='nip']");//инн
        const identitySeries = document.querySelector("input[formControlName='identitySeries']");//серия паспорта
        const identityNumber = document.querySelector("input[formControlName='identityNumber']");//номер паспорта
        const cellPhone = document.querySelector("input[formControlName='cellPhone']");//телефон
        const dateOfBirth = document.querySelector("input[formControlName='dateOfBirth']");//дата рождения (формат 10.4.2020)
        const placeOfBirth = document.querySelector("input[formControlName='placeOfBirth']");//место рождения
        const fathersName = document.querySelector("input[formControlName='fathersName']");//имя отца
        const mothersName = document.querySelector("input[formControlName='mothersName']");//имя отца
        const height = document.querySelector("input[formControlName='height']");//рост
        const shirtSize = document.querySelector("input[formControlName='shirtSize']");//размер рубашки
        const shoeSize = document.querySelector("input[formControlName='shoeSize']");//размер обуви
        const contactPhone = document.querySelector("input[formControlName='contactPhone']");//контактный номер



        arrFil = {
            'name': name,
            'surname': surname,
            'nip': nip,
            'identitySeries': identitySeries,
            'identityNumber': identityNumber,
            'cellPhone': cellPhone,
            'dateOfBirth': dateOfBirth,
            'placeOfBirth': placeOfBirth,
            'fathersName': fathersName,
            'mothersName': mothersName,
            'height': height,
            'shirtSize': shirtSize,
            'shoeSize': shoeSize,
            'contactPhone': contactPhone
        };

        getBtrInfo(btrDealId, arrFil);
    }
}

function getBtrInfo(btrDealId, arrFil) { //btrDealId - id сделки/клиента arrFil -массив с полями


    fetch('https://bonus.fenek.fun/test/getBitrData.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: JSON.stringify({ 'btrDealId': btrDealId })
    })
        .then((response) => {

            return response.json();
        })
        .then((answJson) => {
            for (const key in arrFil) {
                arrFil[key].value = answJson[key];
            }

        }, (error) => {
            console.log(error);
        })
}






function getId(name) {
    let s = window.location.search;
    s = s.match(new RegExp(name + '=([^&=]+)'));
    return s ? s[1] : false;
}