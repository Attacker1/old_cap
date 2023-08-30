<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q6 extends Mixins(converterMixin) {




  public slug
  public value

  public slug1 = 'choosingStyle6'
  public arKeys1 = [
    150,
    27,
    28,
    29,
    30,
    31,
    151
  ]
  public slug2 = 'choosingStyle6_1'
  public arKeys2 = [
    613,
    612,
    615,
    616,
    617,
    614
  ]

  public image = "img/anketa/question_6.png"


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


