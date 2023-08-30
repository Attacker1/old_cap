<template>
  <Form
      ref="form"
      :mode="mode"
      :labelWidth="labelWidth"
      :validOnChange="true"
      :rules="formRules"
      :model="formModel"
  >
    <template v-for="item in items">

      <ItemLegend  v-if="item.hasOwnProperty('legend')" :legend="item.legend"/>
      <ItemText v-if="item.input_type === 'text'" :item="item"/>
      <ItemSelect v-if="item.input_type === 'select'" :item="item"/>
      <ItemCheckboxGroup v-if="item.input_type === 'checkboxGroup'" :item="item"/>
      <ItemCheckbox v-if="item.input_type === 'checkbox'" :item="item"/>
      <ItemNumber v-if="item.input_type === 'number'" :item="item"/>
      <ItemTextarea v-if="item.input_type === 'textarea'" :item="item"/>
      <ItemSlugPicker v-if="item.input_type === 'SlugPicker'" :item="item"/>

    </template>


    <FormItem >
      <Button color="primary" @click="submit()">Сохранить</Button>
      <Button @click="$emit('cancel')">Отменить</Button>
    </FormItem>
  </Form>
</template>

<script lang="ts">
import {Component, Vue, Prop, Emit, Mixins} from 'vue-property-decorator'
import {IFormItem, IFormRules} from "../../../types/settings";
import ItemText from "./formItems/ItemText.vue";
import ItemSelect from "./formItems/ItemSelect.vue";
import ItemCheckboxGroup from "./formItems/ItemCheckboxGroup.vue";
import ItemCheckbox from "./formItems/ItemCheckbox.vue";
import ItemNumber from "./formItems/ItemNumber.vue";
import ItemTextarea from "./formItems/ItemTextarea.vue";
import ItemLegend from "./formItems/ItemLegend.vue";
import ItemSlugPicker from "./formItems/ItemSlugPicker.vue";
@Component({
  components: {
    ItemSlugPicker,
    ItemLegend, ItemTextarea,ItemNumber, ItemCheckbox, ItemCheckboxGroup, ItemSelect, ItemText}
})
export default class QuickForm extends Vue {

  @Prop() items!: IFormItem[]
  @Prop({default:130}) labelWidth!: number
  @Prop({default:'single'}) mode!: string
  @Prop({default:'single'}) formWidth!: string
  @Prop({default:[]}) exclude!: string[]

  public formModel = {}

  public formRules: IFormRules = {
    required: [],
    rules: {},
  }

  $refs!: {
    form: HTMLFormElement
  }

  checkbox(item) {
    this.formModel[item.key] = item.input_value
  }

  makeFormModel() {
    this.items.map(i => {
      return this.formModel[i.key] = i.input_value
    })
  }

  makeFormRules() {

    this.items.map((i) => {

      if (i.hasOwnProperty('input_rules')) {

        if (i.input_rules?.required) {
          this.formRules?.required.push(i.key)
        }

        if (i.input_rules?.custom) {
          console.log(i.key);
          this.formRules.rules[i.key] = i.input_rules.custom
        }

      }

    })
  }

  @Emit('submit')  submit() {
    return {
      valid: this.$refs.form.valid(),
      data: this.formModel
    }
  }

  async created() {
    await this.makeFormModel()
    await this.makeFormRules()
  }

}
</script>
