<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class Properties extends Mixins(converterMixin) {

  @Prop() dataRow
  @Prop() uuid
  @StoreAnketaConverter.Mutation('setConvertedAnswers') setConvertedAnswers!: (data) => void


  @Watch('uuid') onUuidChanged(n,o) {
    this.guessProps()
  }

  async guessProps() {
    // this.uuid = this.dataRow.uuid

    let coupon = null
    let rf = false
    let amount = 0

    if(this.dataRow.data.hasOwnProperty('coupon') && this.dataRow.data.coupon){
      coupon = this.dataRow.data.coupon
      // console.log('coupon', coupon);
    }

    if(
        this.dataRow.hasOwnProperty('data')
        && this.dataRow.data.hasOwnProperty('anketa')
        && this.dataRow.data.anketa.hasOwnProperty('amount')
    ){
      amount = this.dataRow.data.anketa.amount
      // console.log('amount', amount);
    }

    if(this.dataRow.data.hasOwnProperty('rf') && this.dataRow.data.rf){
      rf = this.dataRow.data.rf
      // console.log('rf', rf);
    }


    if(
        this.dataRow.hasOwnProperty('data')
        && this.dataRow.data.hasOwnProperty('anketa')
        && this.dataRow.data.anketa.hasOwnProperty('question')
        && this.dataRow.data.anketa.question.hasOwnProperty('amount')
        && this.dataRow.data.anketa.question.amount
    ){
      amount = this.dataRow.data.anketa.question.amount
      // console.log('amount___', amount);
    }

    if(
        this.dataRow.hasOwnProperty('data')
        && this.dataRow.data.hasOwnProperty('anketa')
        && this.dataRow.data.anketa.hasOwnProperty('question')
        && this.dataRow.data.anketa.question.hasOwnProperty('coupon')
        && this.dataRow.data.anketa.question.coupon
    ){
      coupon = this.dataRow.data.anketa.question.coupon
      // console.log('coupon___', coupon);
    }

    if(
        this.dataRow.hasOwnProperty('data')
        && this.dataRow.data.hasOwnProperty('anketa')
        && this.dataRow.data.anketa.hasOwnProperty('question')
        && this.dataRow.data.anketa.question.hasOwnProperty('rf')
        && this.dataRow.data.anketa.question.rf
    ){
      rf = this.dataRow.data.anketa.question.rf
      // console.log('coupon___', rf);
    }

    // let resp = {
    //   coupon,rf,amount
    // }

    // console.log('hh ' + this.uuid, resp);


    await this.setConvertedAnswers({
      uuid: this.uuid,
      slug: 'coupon',
      value: coupon
    })

    await this.setConvertedAnswers({
      uuid: this.uuid,
      slug: 'rf',
      value: rf
    })

    await this.setConvertedAnswers({
      uuid: this.uuid,
      slug: 'amount',
      value: amount
    })


  }

 async mounted() {
   await this.guessProps()
  }
  updated() {
    // this.guessProps()
  }
}
</script>


