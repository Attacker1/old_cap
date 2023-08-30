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
      @Prop({default:'Фильтр'}) title!: string
      @Prop({default:'icon-search'}) icon!: string
      @Prop({default:'yellow'}) color!: string
      @Prop({default:'s'}) size!: string
      @Prop({default: false}) transparent!: boolean

    }
</script>
