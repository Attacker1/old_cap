<template>
  <FormItem :label="item.title" :prop="item.key" :key="item.key" :required="required(item)" :showLabel="true">
    <template v-slot:label>
      {{ item.title }}:
      <i v-if="isTooltip(item)" class="h-icon-help-solid"
         v-tooltip placement="right" :content="item.tooltip"/>
    </template>

    <div class="h-form-item-wrap">
      <div class="h-checkbox">
        <label :class="active ? 'h-checkbox-checked' : ''" :for="item.key">
          <span v-if="active" class="h-checkbox-native" checked="checked"></span>
          <span v-else class="h-checkbox-native"></span>
        </label>
        <input style="display:none;" type="checkbox" v-model="model" :disabled="inputDisabled(item)" :id="item.key" @click="active = !active">
      </div>
    </div>

  </FormItem>
</template>

<script lang="ts">
import {Component, Mixins} from 'vue-property-decorator'
import formItemsMixin from "../../../../mixins/layout/formItemsMixin";

@Component
export default class ItemCheckbox extends Mixins(formItemsMixin) {

}
</script>

