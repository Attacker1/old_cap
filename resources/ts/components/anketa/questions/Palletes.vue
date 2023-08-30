<template>

  <div class="answers" ref="answers">
    <div class="answers__header"></div>

    <div class="answers__body scroll-block">
      <div class="color-grid">

        <label class="label-image" v-for="(option,index) in question.options"
               :class="{on : checkedClass(option.id)}" :key="index">
          <div class="color-box">
            <div class="color-box__items">
              <div v-for="(color, key) in getColors(option.pallete)" :key="key"
                   :style="{'background-color': color}" v-if="color"></div>
            </div>

          </div>

          <span class="color-box__name">{{ option.text }}</span>

          <template v-if="question.multiple">
            <input type="checkbox" v-model="options" :value="option.id">
          </template>
          <template v-else>
            <input type="radio" v-model="options" :value="option.id">
          </template>
        </label>

      </div>

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
      <button class="next-question" @click="save">Дальше</button>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue, Mixins} from 'vue-property-decorator'
import questionMixin from "../../../mixins/anketa/questionMixin";

@Component({})
export default class Palletes extends Mixins(questionMixin) {
  $refs!: {
    answers: HTMLFormElement,
  }

  getColors(str: String) {
    return str.split(',')
  }

 async mounted() {
    // @ts-ignore
    this.$scrollBlocks(this.$refs.answers)
  }
}
</script>


