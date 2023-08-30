<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q66 extends Mixins(converterMixin) {
  public slug = 'deliveryBackTime'
  public value
  public arKeys = [
    425,
    426,
    427,
    508,
    509
  ]

  isNumber(n) { return /^-?[\d.]+(?:e-?\d+)?$/.test(n); }

  async guessVariant() { // if not array

    if (this.question.answer || this.isNumber(this.question.answer)) {
      this.value = []
      if (!Array.isArray(this.question.answer) ) { // && this.isNumber(this.question.answer)
        this.question.answer = [this.question.answer]
      }
      if (Array.isArray(this.question.answer) && this.question.answer) {
        await this.question.answer.map(i => {
          this.value.push(this.arKeys[i])
        })
      }

    }
  }


  async setAnketa() {
    if(this.value.length > 0)
      if (this.question.hasOwnProperty('answer') && this.question.answer && this.question.answer && this.question.answer.length ) {
        await this.setConvertedAnswers({
          uuid: this.uuid,
          slug: this.slug,
          value: this.value
        })
      }

  }

  async mounted() {
    await this.guessVariant()
    await this.setAnketa()
  }

  async updated() {
    await this.guessVariant()
    await this.setAnketa()
  }

}
</script>


