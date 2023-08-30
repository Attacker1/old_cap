<template>

  <div class="answers" ref="answers">
    <div class="answers__header"></div>

    <div class="answers__body color-grid color-grid_negative scroll-block">

      <label v-for="(option,index) in question.options"
             v-if="option.active"
             class="color-grid__item"
             :style="{'background-color': option.pallete}"
             :class="{on : options.indexOf(option.id) !== -1}" :key="index">
        <input type="checkbox" v-model="options" :value="option.id">
        <span class="color-grid__item-name">{{ option.text }}</span>
      </label>

      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>

    </div>

    <div class="scroll">
      <div class="scroll-bar">
        <div class="scroll-thumb"></div>
      </div>
    </div>
    <div class="answers__footer">
      <button class="next-question" @click="save()">Дальше</button>
    </div>


  </div>

</template>

<script lang="ts">
import {Component, Vue, Mixins} from 'vue-property-decorator'
import questionMixin from "../../../mixins/anketa/questionMixin";
@Component({})

export default class Colors extends Mixins(questionMixin) {
  $refs!: {
    answers: HTMLFormElement,
  }

  sortOptions(){
    // @ts-ignore
    return this.question.options.sort((prev, next) => prev.sort - next.sort)
  }

 async mounted() {
    // @ts-ignore
    this.$scrollBlocks(this.$refs.answers)

  }

}
</script>


