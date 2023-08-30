<template>
  <div class="answers">

    <div class="answers__header"></div>
    <div class="answers__body answers__body_flex">

      <div class="dadata_city vue-dadata">

        <div class="vue-dadata__container">

          <div class="vue-dadata__search">
            <input type="text" placeholder="Город" class="vue-dadata__input" v-model="forms[0]"
                    ref="fields" @keyup="fetchSities(forms[0])" @focusout="cleanCityInput()">
          </div>
          <div class="vue-dadata__suggestions">
            <div class="vue-dadata__suggestions-item" v-for="(city,key) in cities"
                 @click="cityChosen(city.name)" v-if="key <=8">
              {{ city.name }}
            </div>
          </div>
        </div>

      </div>

      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>

    </div>
    <div class="answers__footer">
      <button class="next-question" @click="saveCity()">Дальше</button>
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

export default class BoxberryCity extends Mixins(questionMixin) {
  $refs!: {
    fields: HTMLFormElement
  }

  @StoreAnketa.Mutation('setPrewQuestion') setPrewQuestion!: (payload: number) => void


  get prewQuestion() {
    return this.$store.state.StoreAnketa.prewQuestion
  }

  set prewQuestion(payload) {
    this.setPrewQuestion(payload)
  }


  @StoreAnketa.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void
  @StoreAnketa.Mutation('setBoxberryCity') setBoxberryCity!: (payload: String) => void
  @StoreAnketa.State('boxberryCity') boxberryCity!: String

  @StoreAnketa.Action('fetchSities') fetchSities!: (payload: string) => void
  @StoreAnketa.Mutation('setSities') setSities!: (payload: any[]) => void

  get cities() {
    return this.$store.state.StoreAnketa.cities
  }

  set cities(payload) {
    this.setSities(payload)
  }

  // METHODS


  cleanCityInput() {
    if(!this.forms[0]){
      this.setBoxberryCity('')
    }
  }


  cityChosen(city: string) {
    this.forms[0] = city
    this.setBoxberryCity(city)
    this.cities = []
  }

  makeForms() {
    this.forms = Array.isArray(this.$refs.fields)
        ? this.$refs.fields.map((i: HTMLFormElement) => i.value)
        : [this.$refs.fields.value]
  }

  refreshCity() {
    if(this.forms[0]) {
      this.setBoxberryCity(this.forms[0])
    }
  }

  saveCity() {
    this.forms = this.forms
    if(this.boxberryCity){
      this.save()
    } else {
      eventBus.$emit('openAlert','Вы должны выбрать город из выпадающего списка')
    }

  }

  async mounted() {
    this.refreshCity()
  }


}
</script>
