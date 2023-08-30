<template>
  <FormItem :label="item.title" :prop="item.key" :key="item.key" :required="required(item)" :showLabel="true">
    <template v-slot:label>
      {{ item.title }}:
      <i v-if="isTooltip(item)" class="h-icon-help-solid"
         v-tooltip placement="right" :content="item.tooltip"/>
    </template>
    <Select v-if="isInputList(item)" v-model="model" :disabled="item.input_disabled"
            :datas="item.input_list" :null-option="false"
            :keyName="keyName" :titleName="titleName"
    />
  </FormItem>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue} from 'vue-property-decorator'
import {IFormItem} from "../../../../types/settings";
import formItemsMixin from "../../../../mixins/layout/formItemsMixin";

@Component
export default class ItemSelect extends Mixins(formItemsMixin) {

}
</script>

