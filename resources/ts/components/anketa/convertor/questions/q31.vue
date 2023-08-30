<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q31 extends Mixins(converterMixin) {

  public slug = 'sizeBottom'
  public value
  public arKeys = [
    200,
    201,
    202,
    203,
    204,
    205,
    206,
    207,
    208,
    209
  ]

  public arKeysAlternate = [
    201,
    202,
    203,
    204,
    205
  ]

  async guessVariant() { // if not array

    if (this.question.answer) {
      this.value = []
      if (!Array.isArray(this.question.answer)) {
        this.question.answer = [this.question.answer]
      }
      if (Array.isArray(this.question.answer) && this.question.answer.length) {
        if(this.question.hasOwnProperty('option') && this.question.option.length){
          await this.question.answer.map(i => { // arKeysAlternate
            this.value.push(this.arKeysAlternate[i])
          })
        } else {
          await this.question.answer.map(i => { // arKeys
            this.value.push(this.arKeys[i])
          })
        }

      }



    }
  }


  async setAnketa() {
    if (this.question.hasOwnProperty('answer') && this.question.answer  && this.value.length  ) {
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


