<template>

    <FormItem :label="item.title" :prop="item.key" :key="item.key"
              :required="required(item)" :showLabel="true">

      <template v-slot:label>
        {{ item.title }}:
        <i v-if="isTooltip(item)" class="h-icon-help-solid"
           v-tooltip placement="right" :content="item.tooltip"/>
      </template>

      <textarea v-model="model" :disabled="inputDisabled(item)"/>

    </FormItem>

</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {IFormItem} from "../../../../types/settings";
import formItemsMixin from "../../../../mixins/layout/formItemsMixin";

@Component
export default class ItemTextarea extends Mixins(formItemsMixin) {}
</script>
