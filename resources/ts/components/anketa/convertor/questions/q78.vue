<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q78 extends Mixins(converterMixin) {
  public slug = 'whatHelp'
  public value
  public arKeys = [
    131,
    132,
    133,
    134
  ]

   guessVariant() { // if not array
    if (this.question.answer && !!this.question.answer) {
      this.value = [this.arKeys[this.question.answer]]

      // if (!Array.isArray(this.question.answer) && this.question.answer) {
      //   this.question.answer = [this.question.answer]
      // }
      // if (Array.isArray(this.question.answer) && this.question.answer) {
      //   await this.question.answer.map(i => {
      //     this.value.push(this.arKeys[i])
      //   })
      // }

      // console.log('in '+ this.uuid,this.value);

    }
  }


  async setAnketa() {
    if (this.question.hasOwnProperty('answer') && this.question.answer && this.question.answer && this.question.answer.length ) {  // && this.question.answer && this.question.answer.length
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


