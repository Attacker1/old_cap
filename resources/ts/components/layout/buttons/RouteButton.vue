<template>
  <router-link :class="`btn-route ${icon} h-btn h-btn-${size}`" :to="{name:link}">
    <span>{{title}}</span>
  </router-link>
</template>
<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'

    @Component
    export default class RouteButton extends Vue {

      @Prop({default:''}) link!: string
      @Prop({default:'Список'}) title!: string
      @Prop({default:'icon-menu'}) icon!: string
      @Prop({default:'s'}) size!: string


    }
</script>
