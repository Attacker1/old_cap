<template>

  <FormItem :label="item.title" :prop="item.key" :key="item.key"
            :required="required(item)" :showLabel="true">

    <template v-slot:label>
      {{ item.title }}:
      <i v-if="isTooltip(item)" class="h-icon-help-solid"
         v-tooltip placement="right" :content="item.tooltip"/>
    </template>

    <TreePicker :option="param" :useConfirm="true" :disabled="false" ref="treepicker" className="tiny"
                v-model="model" @change="change" :placeholder="placeholder"/>
    <span class="h-tag treepecker h-tag-yellow">{{placeholder}}</span>
  </FormItem>

</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {IFormItem} from "../../../../types/settings";
import formItemsMixin from "../../../../mixins/layout/formItemsMixin";

@Component
export default class ItemSlugPicker extends Mixins(formItemsMixin) {

  $refs!: {
    treepicker: HTMLFormElement
  }


  public placeholder = 'ddd ddd ddd'
  public pickerValue = ''

  @Watch('placeholder') onModelChange(o,n) {
    console.log('watch', o,n);
  }

  change(data) {
    this.model = data.key
    this.placeholder = data.key
    // this.pickerValue = data.key
    // console.log('change',data, this.$refs.treepicker.getElementsByClassName('.h-treepicker-value-single'));
    // let elem = this.$refs.treepicker.$el//.querySelector('.h-treepicker-value-single')
    // console.log('sdsdsd', elem);
    // this.$refs.treepicker.innerText = data.key //
  }
  param = {
    keyName: 'key',
    parentName: 'parent',
    titleName: 'title',
    dataMode: 'list',
    datas: [],
    selectable(data, level) {
      console.log(data, level);
      return data // !data.children || data.children.length == 0;
    },
    checkable(data, level) {
      console.log(data, level);
      return data //!data.children || data.children.length == 0;
    },
    getFullData(data, level){
      console.log(data, level);
      return data
    }
  }

  public gg = []

  mapObject(ob,parent = '') {
    // @ts-ignore
    Object.keys(ob)?.map(i => {
      let obj
      if(parent){
        obj = {
          key:parent+'.'+i,
          title: i,
          parent: parent
        }
        // @ts-ignore
        this.param.datas.push(obj)
      } else {
        obj = {
          key:i,
          title: i
        }
        // @ts-ignore
        this.param.datas.push(obj)
      }
      if(typeof ob[i] === "object" && ob[i]){
        this.mapObject(ob[i],i)
      } else {

      }

    })
  }

  created(){
    this.mapObject(this.item.input_list)
    this.placeholder = this.model
    // this.$refs.treepicker
  }


}
</script>

<style>
.h-treepicker.h-treepicker-input-border.h-treepicker-no-autosize {
  display: inline-block!important;
  /*margin-right: 16px;*/
}
.h-treepicker-value-single {
  display: none;
}
.h-tag.treepecker {
  height: 30px;
  display: inline-flex;
  align-items: center;
}
</style>
