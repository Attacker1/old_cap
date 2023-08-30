<template>
  <div class="answers">

    <div class="answers__header"></div>

    <div class="answers__body answers__body_radio answers__body_radio_column">
      <label v-for="(option,index) in question.options" class="label__radio"
             :class="{on : checkedClass(option.id)}" :key="index" v-if="option.type === 'cities'">
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

export default class Delivery extends Mixins(questionMixin) {


  @StoreAnketa.Mutation('setPrewQuestion') setPrewQuestion!: (payload: number) => void

  get prewQuestion() {
    return this.$store.state.StoreAnketa.prewQuestion
  }

  set prewQuestion(payload) {
    this.setPrewQuestion(payload)
  }


  @StoreAnketa.Mutation('setBoxberryCity') setBoxberryCity!: (str: String) => void

  get boxberryCity() {
    return this.$store.state.StoreAnketa.boxberryCity
  }

  set boxberryCity(payload) {
    this.setAnswer({options: this.options, own: this.own, forms: payload})
  }

  // METHODS

  setCity() {
    let city = this.question.options.find((i: IQuestionOption) => i.id === this.options)
    if (city?.emoji) {
      this.setBoxberryCity(city?.emoji)
    }
  }

  nextQuestionSave() {
    this.setCity()

    if (this.options.length === 0) {
      eventBus.$emit('openAlert', 'Нам необходимо знать в какой город доставить капсулу')
      return false
    }

    this.save()

  }

  async mounted() {
    let boxberry = document.createElement('script')
    boxberry.setAttribute('id','boxberry')
    boxberry.setAttribute('src', 'https://points.boxberry.de/js/boxberry.js')
    let same = document.getElementById("boxberry")
    if(!same) {
      document.head.appendChild(boxberry)
    }
  }

}
</script>

