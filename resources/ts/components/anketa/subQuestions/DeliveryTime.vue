<template>
  <div class="answers">

    <div class="answers__header"></div>

    <div class="answers__body answers__body answers__body_radio answers__body_radio_column">

      <label v-for="(option,index) in question.options"
             :class="{on : checkedClass(option.id)}" class="label__radio" :key="index" v-if="option.type === 'deliveryTime' && option.active">
        <input type="radio" v-model="options" :value="option.id">
        <span v-html="option.text"></span>
      </label>


      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>


    </div>
    <div class="answers__footer">
      <button class="next-question" @click="nextQuestionSave()">Дальше</button>
    </div>

  </div>

</template>

<script lang="ts">

import {Component, Vue, Mixins} from 'vue-property-decorator'
import questionMixin from "../../../mixins/anketa/questionMixin";

import {IAnswer, IQuestion, IQuestionOption} from "../../../types/anketa"
import {namespace} from "vuex-class"
import {eventBus} from "../../../bus";

const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class DeliveryTime extends Mixins(questionMixin) {



  @StoreAnketa.Mutation('setPrewQuestion') setPrewQuestion!: (payload: number) => void

  get prewQuestion() {
    return this.$store.state.StoreAnketa.prewQuestion
  }

  set prewQuestion(payload) {
    this.setPrewQuestion(payload)
  }

  @StoreAnketa.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void

  @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void


  nextQuestionSave() {
    if (this.options.length === 0) {
      eventBus.$emit('openAlert', 'Выберите удобное время для встречи курьера')
      return false
    }

    const currentOption: IQuestionOption | undefined = this.question.options.find((i: IQuestionOption) => i.next_question !== null)
    if (currentOption && currentOption.next_question) {
      this.swapQuestions(currentOption.next_question)
      this.saveAnswers()
      this.queue++
    } else {
      this.saveAnswers()
      this.queue++
    }
  }

  checkedItem() {
    this.question.options.map((i: IQuestionOption) => {
      if (this.options){
        return false
      }
      if (i.checked) {
        this.options = i.id
      }
    })
  }

  async mounted() {
    this.checkedItem()
  }
}
</script>
