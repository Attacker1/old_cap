import HeyUI from "heyui";
import axios from "axios";
import {IEmitResponse} from "../../types/settings";

export const emitSuccessSave = (response) => {
    HeyUI.$Loading.close()
    HeyUI.$Notice['success']('Сохранено 😋');
    return response.data
}

export const emitSuccessLoad = (response) => {
    HeyUI.$Loading.close()
    HeyUI.$Message({
        timeout: 1500,
        type: 'success',
        text:'Загружено'
    });
    return response.data
}
export const emitError = (error) => {
    if (error.response) {
        HeyUI.$Notice({
            type: 'error',
            icon: 'h-icon-bell',
            title: 'Ошибка ответа',
            content: error.response.status // status, headers
        });
    } else if (error.request) {
        HeyUI.$Notice({
            icon: 'h-icon-bell',
            title: 'Ошибка запроса',
            content: error.request
        });
    } else {
        HeyUI.$Notice({
            icon: 'h-icon-bell',
            title: 'Неизвестная ошибка',
            content: error.message
        });
    }
}

export const emitResponseSave = async (data: IEmitResponse) => {
    try {
        const response = await axios.post(data.url, data.payload);
        if (await response) {
            return emitSuccessSave(await response)
        }
    } catch (error) {
        return emitError(error)
    }

}

export const emitResponseLoad = async (data: IEmitResponse) => {
    try {
        HeyUI.$Loading('Загружаем')
        const response = await axios.post(data.url, data.payload);
        if (await response) {
            return emitSuccessLoad(await response)
        }
    } catch (error) {
        return emitError(error)
    }

}

