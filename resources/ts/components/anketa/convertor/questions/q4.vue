<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q4 extends Mixins(converterMixin) {

  public slug
  public value
  public arKeys1 = [
    146, 12, 13, 14, 15, 16, 147
  ]

  public arKeys2 = [
    599, 598, 601, 602, 603, 604, 600,
  ]

  async guessVariant() { // if not array
    let arKeys

    if(this.question.hasOwnProperty('type') && this.question.type === "converted") {
      arKeys = this.arKeys1
      this.slug = 'choosingStyle4'
    } else if(this.question.hasOwnProperty('image') && this.question.image == "img/anketa/question_4.png") {
      this.slug = 'choosingStyle4_1'
      arKeys = this.arKeys2
    } else {
      this.slug = 'choosingStyle4'
      arKeys = this.arKeys1
    }


    if (this.question.answer) {
      this.value = []
      if (!Array.isArray(this.question.answer)) {
        this.question.answer = [this.question.answer]
      }
      if (Array.isArray(this.question.answer) && this.question.answer) {
        await this.question.answer.map(i => {
          this.value.push(arKeys[i])
        })
      }

    }
  }


  async setAnketa() {
    if (this.question.hasOwnProperty('answer') && this.question.answer && Array.isArray(this.question.answer) && this.question.answer.length ) {
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


