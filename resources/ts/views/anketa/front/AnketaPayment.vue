<template>
  <div class="wrap" v-cloak>
    <div class="main">

      <div class="question">

        <QuestionHead/>

        <div class="answers">
          <div class="answers__header"></div>
          <div class="answers__body">

            <label v-if="successPayment">
              Мы получили вашу оплату! В ближайшее время вам придут смс и письмо на почту от Capsula с доступом в ваш личный кабинет.
              В нем вы можете просмотреть заполненную анкету и связаться с вашим менеджером, если хотите внести дополнения к анкете 🙂
            </label>

            <label v-else> {{paymentMessage}} </label>
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

export default class AnketaPayment extends Vue {


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



  async threeDSecure() {
    await axios.post('/questions', {
      func: 'payment3d',
      // payment values
      MD: this.$attrs.backend.payment.MD,
      PaRes: this.$attrs.backend.payment.PaRes,
      leadUuid: this.$route.params.leadUuid

    }).then(response => {
      this.paymentInteraction(response.data)
    }).catch(err => {
      this.axiosErrorHandler(err)
    })
  }


  axiosErrorHandler(err) {
    console.log('err', err);
  }

  paymentInteraction(CPResponse: ICloudPaymentResponse){
    if(CPResponse.hasOwnProperty('id')){
      this.successPayment = true
    } else {
      this.paymentMessage = CPResponse.Model.CardHolderMessage
    }
  }

  async created(){
    //  @TODO-uretral:  Обнуление localstorage(anketa)

    console.log(this.$attrs.backend);
    if(this.$route.params.leadUuid === 'success'){
      this.successPayment = true
    } else {
      await this.threeDSecure();
    }
  }

}
</script>
<!--
Success=true&ErrorCode=0&Message=None&Details=&Amount=100&MerchantEmail=clients%40thecapsula.ru&MerchantName=Capsula+(stylist)&OrderId=fad7ae887c-d25d-486d-8ee1-4ecc4519322b&PaymentId=887669900&TranDate=02.12.2021+15%3A20%3A18&BackUrl=http%3A%2F%2Fpay.thecapsula.ru&CompanyName=ООО+"КАПСУЛА"&EmailReq=clients%40thecapsula.ru&PhonesReq=9295037354
-->
