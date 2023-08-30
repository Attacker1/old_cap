<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q20 extends Mixins(converterMixin) {



  public slug
  public value

  public slug1 = 'choosingStyle20'
  public arKeys1 = [
    164,
    100,
    101,
    102,
    103,
    104,
    165
  ]
  public slug2 = 'choosingStyle20_1'
  public arKeys2 = [
    653,
    652,
    655,
    656,
    657,
    654
  ]

  public image = "img/anketa/question_20.png"


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


