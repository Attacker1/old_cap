<template>
  <div class="answers" ref="answers">
    <div class="answers__header"></div>

    <div ref="scrollBlock" class="answers__body answers__body_grid scroll-block ">
      <div class="thumb-grid" :class="answerClass()">


        <label class="label-thumb" v-for="(option, key) in question.options" :class="{on : checkedClass(option.id)}"
               :key="key">
          <p v-if="option.text_thumb" class="thumb-another_text" v-html="option.text"></p>
          <template v-else>
            <span v-if="thumbs" v-html="option.text"></span>
            <template v-else>{{ option.text }}</template>
          </template>

          <div v-if="option.text_thumb" class="thumb-variation"></div>
          <div v-else :class="thumbs ? 'thumb-variation' : 'thumb'"
               :style="{'background-image': `url(/storage/${option.image})`}"></div>

          <input v-if="question.multiple" type="checkbox" v-model="options" :value="option.id">
          <input v-else type="radio" v-model="options" :value="option.id">

        </label>
      </div>

      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>

    </div>

    <div class="scroll" ref="scroll">
      <div class="scroll-bar" ref="scrollBar">
        <div class="scroll-thumb" ref="scrollThumb"></div>
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
import {IAnswer, IQuestion, IQuestionOption} from "../../../types/anketa";

@Component({})
export default class PicturesGrid extends Mixins(questionMixin) {

  $refs!: {
    answers: HTMLFormElement,
    scrollBlock: HTMLFormElement,
  }

  private thumbs: number = 0

  // => METHODS
  answerClass() {
    let images: number = 0
    let thumbs: number = 0
    let classes: string[] = []
    this.question.options.map((i: IQuestionOption) => {
      if (i.text_thumb === 1) {
        thumbs++
      } else {
        images++
      }
    })


    if (this.question.slug.includes('Not')) {
      classes.push('thumb-grid_negative')
    }


    if (thumbs) {
      classes.push('thumb-grid_under')
    }

    if (images <= 3) {
      classes.push('thumb-grid_three')
    }
    if (images === 5) {
      classes.push('thumb-grid_five')
    }
    if (images > 5) {
      classes.push('thumb-grid_max thumb-grid_max_four ')
    }
    this.thumbs = thumbs


    return classes.join(' ')

  }

  public updatedBlock = {
    slug: '',
    state: false
  }

  controlUpdatedBlock() {
    if(this.updatedBlock.slug !== this.question.slug) {
      this.$refs.scrollBlock.scrollLeft = 0
      this.updatedBlock.slug = this.question.slug
    }
  }

  async updated() {
    this.controlUpdatedBlock()
  }

 async mounted() {
   this.controlUpdatedBlock()
    // @ts-ignore
    this.$scrollBlocks(this.$refs.answers)
  }
}
</script>


