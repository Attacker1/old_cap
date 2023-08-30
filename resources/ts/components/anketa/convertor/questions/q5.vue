<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q5 extends Mixins(converterMixin) {



  public slug
  public value

  public slug1 = 'choosingStyle5'
  public arKeys1 = [
    148,
    22,
    23,
    24,
    25,
    26,
    149
  ]
  public slug2 = 'choosingStyle5_1'
  public arKeys2 = [
    606,
    605,
    608,
    609,
    610,
    611,
    607
  ]

  public image = "img/anketa/question_5.png"


  async guessVariant() { // if not array
    let arKeys

    if(this.question.hasOwnProperty('type') && this.question.type === "converted") {
      arKeys = this.arKeys1
      this.slug = this.slug1
    } else if(this.question.hasOwnProperty('image') && this.question.image == this.image) {
      this.slug = this.slug2
      arKeys = this.arKeys2
    } else {
      this.slug = this.slug1
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


