<template>
  <FormItem :label="item.title" :prop="item.key" :key="item.key" :required="required(item)" :showLabel="true">
    <template v-slot:label>
      {{ item.title }}:
      <i v-if="isTooltip(item)" class="h-icon-help-solid"
         v-tooltip placement="right" :content="item.tooltip"/>
    </template>

    <Checkbox v-if="isInputList(item)" v-model="model" :datas="item.input_list"
              :keyName="keyName" :titleName="titleName"
              :disabled="item.input_disabled">
    </Checkbox>

  </FormItem>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue} from 'vue-property-decorator'
import formItemsMixin from "../../../../mixins/layout/formItemsMixin";

@Component
export default class ItemCheckboxGroup extends Mixins(formItemsMixin) {}
</script>

