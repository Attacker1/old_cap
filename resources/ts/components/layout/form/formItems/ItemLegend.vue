<template>
  <FormItem single class="legend">
    <div></div>
    <template v-slot:label>
      {{ legend }}
    </template>


  </FormItem>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {IFormItem} from "../../../../types/settings";

@Component
export default class ItemLegend extends Vue {

  @Prop() legend!: IFormItem
}
</script>

<style>
.legend label{

  font-weight: bold;
  color: #45b984!important;
}
.legend > div {

  border-bottom: 1px dashed #45b984;
  /*background: yellow;*/
  height: 17px;
}
</style>
