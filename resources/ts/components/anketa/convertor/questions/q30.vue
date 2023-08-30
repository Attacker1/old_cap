<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q30 extends Mixins(converterMixin) {

  public slug = 'sizeTop'
  public value
  public arKeys = [
    190,
    191,
    192,
    193,
    194,
    195,
    196,
    197,
    198,
    199
  ]

  public arKeysAlternate = [
    191,
    192,
    193,
    194,
    195
  ]

  async guessVariant() { // if not array

    if (this.question.answer) {
      this.value = []
      if (!Array.isArray(this.question.answer)) {
        this.question.answer = [this.question.answer]
      }
      if (Array.isArray(this.question.answer) && this.question.answer) {
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


