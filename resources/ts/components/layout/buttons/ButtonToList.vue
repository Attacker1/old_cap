<template>
  <router-link :to="{name:link}">
    <Button :class="icon" :size="size" :text-color="color" :transparent="transparent" v-html="title"/>
  </router-link>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'

    @Component
    export default class ButtonToList extends Vue {

      @Prop({default:''}) link!: string
      @Prop({default:'Список'}) title!: string
      @Prop({default:'icon-menu'}) icon!: string
      @Prop({default:'blue'}) color!: string
      @Prop({default:'s'}) size!: string
      @Prop({default: false}) transparent!: boolean

    }
</script>
