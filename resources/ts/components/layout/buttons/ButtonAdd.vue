<template>
    <Button :class="icon"
            :size="size"
            :text-color="color"
            :transparent="transparent"
            v-html="title"
            @click="$emit('click', event)"
    >
    </Button>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'

    @Component
    export default class ButtonAdd extends Vue {

      @Prop({default:''}) link!: string
      @Prop({default:'Новый'}) title!: string
      @Prop({default:'icon-plus'}) icon!: string
      @Prop({default:'green'}) color!: string
      @Prop({default:'s'}) size!: string
      @Prop({default: false}) transparent!: boolean

    }
</script>
