<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q51 extends Mixins(converterMixin) {
  public slug = 'printsDislike'
  public value
  public arKeys = [
    314,
    315,
    316,
    313,
    317,
    318,
    319,
    320,
    325,
    326,
    327,
    328,
    329,
    330,
    331,
    332,
    333,
    334,
    335,
    336,
    337,
    338,
    339,
    340,
    341,
    342,
    343,
    344
  ]

  async guessVariant() { // if not array

    if (this.question.answer) {
      this.value = []
      if (!Array.isArray(this.question.answer)) {
        this.question.answer = [this.question.answer]
      }
      if (Array.isArray(this.question.answer) && this.question.answer.length) {
        await this.question.answer.map(i => {
          this.value.push(this.arKeys[i])
        })
      }

    }
  }


  async setAnketa() {
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


