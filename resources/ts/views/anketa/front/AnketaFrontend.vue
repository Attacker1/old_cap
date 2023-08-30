<template>
  <div class="wrap" v-cloak>
    <div class="main">


      <Pinned :queue="queue" :queueLength="questionsLengthAlternative()"/>

      <!-- no standart layouts-->
      <Coupon v-if="currentQuestionType === 14"
              :cloudPaymentPublicId="$attrs.backend.payment.cloudPaymentPublicId"
              :cloudPaymentResultPage="$attrs.backend.payment.cloudPaymentResultPage"
              :tinkoffTerminalKey="$attrs.backend.payment.tinkoffTerminalKey"
      />
<!--      <CouponCloudPayment v-if="currentQuestionType === 14"-->
<!--              :cloudPaymentPublicId="$attrs.backend.payment.cloudPaymentPublicId"-->
<!--              :cloudPaymentResultPage="$attrs.backend.payment.cloudPaymentResultPage"-->
<!--              :tinkoffTerminalKey="$attrs.backend.payment.tinkoffTerminalKey"-->
<!--      />-->
      <Photos v-else-if="currentQuestionType === 15"/>
      <!-- standart layout-->
      <div class="question" v-else>

        <QuestionHead/>

        <ColumnList v-if="currentQuestionType === 2"/>
        <PicturesGrid v-if="currentQuestionType === 3"/>
        <PickStyle v-if="currentQuestionType === 4"/>
        <Palletes v-if="currentQuestionType === 5"/>
        <StringList v-if="currentQuestionType === 6"/>
        <Emoji v-if="currentQuestionType === 7"/>
        <ColumnListThin v-if="currentQuestionType === 8"/>
        <CustomForm v-if="currentQuestionType === 9"/>
        <Colors v-if="currentQuestionType === 10"/>
        <Tables v-if="currentQuestionType === 11"/>
        <Delivery v-if="currentQuestionType === 13"/>
        <DeliveryTypes v-if="currentQuestionType === 16"/>
        <DeliveryDate v-if="currentQuestionType === 17"/>
        <BoxberryCity v-if="currentQuestionType === 19"/>
        <DeliveryTime v-if="currentQuestionType === 20"/>
        <BoxberryPoint v-if="currentQuestionType === 21"/>

      </div>

      <Alert/>
      <Loader/>

    </div>
  </div>


</template>

<script lang="ts">

import {Component, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";

const StoreAnketa = namespace('StoreAnketa')


import Pinned from "../../../components/anketa/common/Pinned.vue";

import QuestionHead from "../../../components/anketa/common/QuestionHead.vue";
// questions
import Emoji from "../../../components/anketa/questions/Emoji.vue";
import ColumnList from "../../../components/anketa/questions/ColumnList.vue";
// import CheckboxWidePlus from "../questions/CheckboxWidePlus.vue";
import PicturesGrid from "../../../components/anketa/questions/PicturesGrid.vue";
import ColumnListThin from "../../../components/anketa/questions/ColumnListThin.vue";
import PickStyle from "../../../components/anketa/questions/PickStyle.vue";
import Palletes from "../../../components/anketa/questions/Palletes.vue";
import CustomForm from "../../../components/anketa/questions/CustomForm.vue";
import StringList from "../../../components/anketa/questions/StringList.vue";
// import SetQueue from "../common/SetQueue.vue";
import Colors from "../../../components/anketa/questions/Colors.vue";
import Tables from "../../../components/anketa/questions/Tables.vue";
import Alert from "../../../components/anketa/common/Alert.vue";
import Delivery from "../../../components/anketa/questions/Delivery.vue";
import Coupon from "../../../components/anketa/questions/Coupon.vue";
// import ClientPhotos from "../questions/ClientPhotos.vue";
import DeliveryTypes from "../../../components/anketa/subQuestions/DeliveryTypes.vue";
import DeliveryDate from "../../../components/anketa/subQuestions/DeliveryDate.vue";
import DeliveryTime from "../../../components/anketa/subQuestions/DeliveryTime.vue";
import BoxberryCity from "../../../components/anketa/subQuestions/BoxberryCity.vue";
import BoxberryPoint from "../../../components/anketa/subQuestions/BoxberryPoint.vue";
import Photos from "../../../components/anketa/subQuestions/Photos.vue";

import {IQuestion} from "../../../types/anketa";
import CouponCloud from "../../../components/anketa/questions/CouponCloud.vue";
import CouponCloudPayment from "../../../components/anketa/questions/CouponCloudPayment.vue";
import Loader from "../../../components/anketa/common/Loader.vue";

@Component({
  components: {
    Loader,
    CouponCloudPayment,
    CouponCloud,
    Photos,
    BoxberryPoint,
    BoxberryCity,
    DeliveryTime,
    DeliveryDate,
    DeliveryTypes,
    // ClientPhotos,
    Coupon,
    Delivery,
    Alert,
    Tables,
    Colors,
    // SetQueue,
    StringList,
    CustomForm,
    Palletes,
    PickStyle,
    ColumnListThin,
    PicturesGrid,
    ColumnList,
    Pinned, QuestionHead,
    Emoji,
    // CheckboxWidePlus
  },
})

export default class AnketaFrontend extends Vue {
  $attrs!: {
    backend
  }

  public q: boolean = false

  @StoreAnketa.State('questions') questions!: []
  @StoreAnketa.State('currentQuestion') currentQuestion!: IQuestion
  @StoreAnketa.Action('fetchQuestions') fetchQuestions!: (payload: string) => void
  @StoreAnketa.Mutation('setCurrentQuestion') setCurrentQuestion!: () => void
  @StoreAnketa.Mutation('setQueuePaused') setQueuePaused!: (payload: boolean) => void
  @StoreAnketa.Mutation('setAnketaSlug') setAnketaSlug!: (variantId: string) => void
  @StoreAnketa.Mutation('setUuid') setUuid!: (variantId: string) => void
  // @StoreAnketa.Mutation('setCapsulaTest') setCapsulaTest!: (payload: boolean) => void

  @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void

  @StoreAnketa.State('anketaSlug') anketaSlug!: string
  // @StoreAnketa.Action('guessAnketaSlug') guessAnketaSlug!: () => void

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

  questionsLengthAlternative() {
    let len = 0;
    for (let i = 0; i < this.questions.length; i++) {
      len++
      if (this.questions[i]['slug'] === 'coupon') {
        break
      }
    }
    return len
  }

  // async localData() {
  //
  //   if (this.$route.params.hasOwnProperty('anketaSlug')) {
  //     await this.setAnketaSlug(this.$route.params.anketaSlug)
  //     await this.fetchQuestions(this.$route.params.anketaSlug)
  //   }
  // }


  async created() {
/*    if (!this.$route.params.anketaSlug) {
      // @ts-ignore
      console.log(this.$route.query.t);
      await this.guessAnketaSlug()
      // await this.$router.push({name: 'frontend.anketa', params: {anketaSlug: this.anketaSlug}})
    }*/


  }

    async mounted() {
      if(this.$route.query.hasOwnProperty('t')){
        // @ts-ignore
        // await this.setAnketaSlug(this.$route.query.t)
        // @ts-ignore
        await this.fetchQuestions(this.$route.query.t)
      }
      await this.setCurrentQuestion()

      this.questionsLengthAlternative()


      setTimeout(() => {
        // @ts-ignore
        // this.$metrika.reachGoal('AddToCart')
        // @ts-ignore
        fbq('track', 'PageView')
      }, 1000)
    }
}
</script>
