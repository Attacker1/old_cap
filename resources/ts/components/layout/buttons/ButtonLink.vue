<template>
  <router-link :to="{name:link, params}">
    <span :class="`h-tag h-tag-${color} small link`">{{ title }}</span>
  </router-link>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'

@Component
export default class ButtonLink extends Vue {

  @Prop({default: () => ''}) link!: string
  @Prop({ default: () => {}}) params!: {}
  @Prop({default: 'ссылка'}) title!: string
  @Prop({default: 'icon-menu'}) icon!: string
  @Prop({default: 'blue'}) color!: string
  @Prop({default: 'xs'}) size!: string
  @Prop({default: false}) transparent!: boolean

  public to: {} = {}

  setParams() {
    this.to['name'] = this.link
    if (Object.keys(this.params).length !== 0) {
      this.to['params'] = {...this.params}
    }
  }

  mounted() {
    this.setParams()
  }

}
</script>
