import {Component, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import {eventBus} from "../../bus";
@Component
export default class serviceMixin extends Vue {


    notice(type,message = '',timeout = 2000) {
        let text = {
            info: 'На заметку',
            success: 'Успешно',
            warn: 'Внимание',
            error: 'Ошибка'
        }[type];
        // @ts-ignore
        this.$Notice[type]( message,timeout);
    }
}
