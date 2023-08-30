<template>
  <div class="wrap" v-cloak>
    <div class="main">

      <div class="question">

        <div class="question-head">
          <div class="logo"><img src="/assets-vuex/img/logo.svg" alt="logo"></div>
          <label>
            Мы получили вашу оплату! В ближайшее время вам придут смс и письмо на почту от Capsula с доступом в ваш личный кабинет.
            В нем вы можете просмотреть заполненную анкету и связаться с вашим менеджером, если хотите внести дополнения к анкете 🙂
          </label>

        </div>

        <div class="answers">
          <div class="answers__header"></div>
          <div class="answers__body">


          </div>
        </div>


      </div>

    </div>
  </div>


</template>

<script lang="ts">

import {Component, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";
import {eventBus} from "../../../bus";
const StoreAnketa = namespace('StoreAnketa')
import Pinned from "../../../components/anketa/common/Pinned.vue";
import QuestionHead from "../../../components/anketa/common/QuestionHead.vue";
import {IQuestion} from "../../../types/anketa";
import axios from "axios";
import {ICloudPaymentResponse} from "../../../types/payments";

@Component({
  components: {
    Pinned, QuestionHead
  },
})

export default class AnketaSuccess extends Vue {


  $attrs!: {
    backend
  }

  public q: boolean = false

  public successPayment: boolean = false
  public paymentMessage: string = 'paymentMessage'

  @StoreAnketa.State('questions') questions!: []
  @StoreAnketa.State('currentQuestion') currentQuestion!: IQuestion
  @StoreAnketa.Action('fetchQuestions') fetchQuestions!: (payload: string) => void
  @StoreAnketa.Mutation('setCurrentQuestion') setCurrentQuestion!: () => void
  @StoreAnketa.Mutation('setQueuePaused') setQueuePaused!: (payload: boolean) => void
  @StoreAnketa.Mutation('setAnketaSlug') setAnketaSlug!: (variantId: string) => void
  @StoreAnketa.Mutation('setUuid') setUuid!: (variantId: string) => void
  // @StoreAnketa.Mutation('setCapsulaTest') setCapsulaTest!: (payload: boolean) => void

  @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void

// => QUEUE
  @StoreAnketa.Mutation('setQueue') setQueue!: (payload: number) => void

  get queue() {
    return this.$store.state.StoreAnketa.queue
  }

  set queue(payload: number) {
    this.setQueue(payload)
  }

  get queuePaused() {
    return this.$store.state.StoreAnketa.queuePaused
  }

  set queuePaused(payload: boolean) {
    this.setQueuePaused(payload)
  }

  get currentQuestionType() {
    return this.currentQuestion.type_id
  }

  async queueNext() {
    this.setQueue(this.queue + 1)
  }

  questionsLength() {
    let qty = []
    this.questions.map((i: IQuestion) => {
      if (!i.is_sub) {
        // @ts-ignore
        qty.push(i.id)
      }
    })
    return qty.length
  }



  payFor() {
    let res = 'stylist'
    let pay_for = this.$route.query.MerchantName ?? null;
    if(pay_for){
      // @ts-ignore
      let pay_for_match = (pay_for).match(/\((.*)\)/)
      if(pay_for_match) {
        res = pay_for_match.pop()
      }
    }

    return res

  }

  beforeSave(){
    let payment = {
      lead_id: this.$route.query.OrderId.slice(2),
      amount: Number(this.$route.query.Amount) / 100,
      paid_at: this.convertDate(),
      pay_for: this.payFor(),
      payment_id: this.paymentId(),
      source: 'tinkoff',
      payload: JSON.stringify(this.$route.query)

    }
    console.log(payment);
  }

  leadId() {
    return this.$route.query.OrderId.slice(2)
  }


  convertDate() {
    const TranDate: string = this.$route.query.TranDate ? this.$route.query.TranDate.toString() :  ''
    if(TranDate.length > 0) {
      let raw = TranDate.split(' ');
      const [day, month, year ] = raw[0].split('.')
      return `${year}-${month}-${day} ${raw[1]}`
    } else {
      return new Date().toISOString().slice(0,-5).replace(/T/g,' ' )
    }
  }

  paymentId() {
    return this.$route.query.PaymentId ? Number(this.$route.query.PaymentId) :  null
  }

  clearLocalStorage(){
    let ls = localStorage.getItem('anketa')
    if(ls) {
      let lsData = JSON.parse(ls)
      let current = lsData.find(i => i.uuid === this.leadId())


      if(current) {
        lsData.splice(current,1)
        localStorage.setItem('anketa', JSON.stringify(lsData))
      }
    } else  {
      localStorage.removeItem('anketa')
    }

  }


  metr(ya : any, fb: any) {
    setTimeout(() => {
      // @ts-ignore
      this.$metrika.reachGoal(ya)
      let paymentType =  this.$route.query.OrderId.toString().substring(0,2)
      if (paymentType === 'fa' ) {
        // @ts-ignore
        fbq('track', 'Subscribe', {currency: "RUB", value: this.$route.query.Amount}, {eventID: this.$route.query.OrderId.toString()});
      }
    }, 500)
  }


  async created(){

    this.clearLocalStorage()

    let paymentType =  this.$route.query.OrderId.toString().substring(0,2)

    if (paymentType !== 'fb' ) {
      await this.metr('Purchase', 'Subscribe' )
    }

  }

/*  Success=true&ErrorCode=0&Message=None&Details=&Amount=100&MerchantEmail=clients%40thecapsula.ru&MerchantName=Capsula+(stylist)&OrderId=fad7ae887c-d25d-486d-8ee1-4ecc4519322b&PaymentId=887669900&TranDate=02.12.2021+15%3A20%3A18&BackUrl=http%3A%2F%2Fpay.thecapsula.ru&CompanyName=ООО+"КАПСУЛА"&EmailReq=clients%40thecapsula.ru&PhonesReq=9295037354*/

}
</script>
