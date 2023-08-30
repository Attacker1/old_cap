<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q28 extends Mixins(converterMixin) {

  public slug = 'occupation'
  public value
  public arKeys = [
    168,
    169,
    170,
    171,
    172,
    173,
    174,
    175,
    176,
    177,
    178,
    179,
    180,
    181,
    182,
    183,
    184,
    519,
    520,
    521
  ]

  async guessVariant() { // if not array

    if (this.question.answer) {
      this.value = []
      if (!Array.isArray(this.question.answer)) {
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


