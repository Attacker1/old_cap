<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q50 extends Mixins(converterMixin) {
  public slug = 'noColor'
  public value
  public arKeys = [
    288,
    287,
    301,
    291,
    302,
    296,
    303,
    295,
    304,
    305,
    300,
    306,
    307,
    308,
    309,
    310,
    311,
    297,
    285,
    286,
    312,
    290,
    299,
    298,
    292
  ]

  async guessVariant() { // if not array

    if (this.question.answer) {
      this.value = []
      if (!Array.isArray(this.question.answer) && this.question.answer) {
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


