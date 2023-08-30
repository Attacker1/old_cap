import {Component, Mixins, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'

const StoreSettings = namespace('StoreSettings')
import {eventBus} from "../../bus";
import {IFormItem} from "../../types/settings";
import serviceMixin from "../settings/serviceMixin";

@Component
export default class formItemsMixin extends Mixins(serviceMixin) {

    @Prop() item!: IFormItem
    @Prop({default: 1}) trueValue!: number
    @Prop({default: 0}) falseValue!: number
    @Prop({default: 'id'}) keyName!: string
    @Prop({default: 'name'}) titleName!: string

    public active: boolean = false

    get model() {
        return this.$parent.$props['model'][this.item.key]
    }

    set model(payload) {

        this.$parent.$props['model'][this.item.key] = payload
        this.active = payload
    }

    checkbox(item) {
        this.$parent.$props['model'][item.key] = item.input_value
    }

    // common methods

    required(item:IFormItem) : boolean {
        return item.hasOwnProperty('input_rules')  && item.input_rules?.hasOwnProperty('required') && item.input_rules.required
            ? item.input_rules.required
            : false
    }

    inputDisabled(item:IFormItem) : boolean {
        return item.hasOwnProperty('input_disabled') && item.input_disabled
            ? item.input_disabled
            : false
    }

    isTooltip(item:IFormItem) : boolean {
        return item.hasOwnProperty('tooltip')
    }

    isInputList(item: IFormItem){
        if(item.hasOwnProperty('input_list') && item.input_list?.length) {
            return true;
        } else  {
            this.notice('warn',`Необходимо добавить список <br/> поле ${item.title}(${item.key})`,10000)
            return false
        }
    }

    mounted() {
        this.active = this.model
    }
}
