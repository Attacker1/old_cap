<template>
  <div class="wrap" v-cloak>
    <div class="main">


      <Pinned :queue="queue" :queueLength="questionsLength()"/>

      <!-- no standart layouts-->
      <!--        <Coupon v-if="currentQuestionType === 14"/>-->
      <!--        <Photos v-else-if="currentQuestionType === 15"/>-->
      <!-- standart layout-->
      <div class="question" v-else>

        <QuestionHead/>

        <!--            <ColumnList v-if="currentQuestionType === 2"/>-->
        <!--            <PicturesGrid v-if="currentQuestionType === 3"/>-->
        <!--            <PickStyle v-if="currentQuestionType === 4"/>-->
        <!--            <Palletes v-if="currentQuestionType === 5"/>-->
        <!--            <StringList v-if="currentQuestionType === 6"/>-->
        <!--            <Emoji v-if="currentQuestionType === 7"/>-->
        <!--            <ColumnListThin v-if="currentQuestionType === 8"/>-->
        <!--            <CustomForm v-if="currentQuestionType === 9"/>-->
        <!--            <Colors v-if="currentQuestionType === 10"/>-->
        <!--            <Tables v-if="currentQuestionType === 11"/>  <== -->
        <!--            <Delivery v-if="currentQuestionType === 13"/>-->
        <!--            <DeliveryTypes v-if="currentQuestionType === 16"/>-->
        <!--            <DeliveryDate v-if="currentQuestionType === 17"/>-->
        <!--            -->
        <!--            <BoxberryCity v-if="currentQuestionType === 19"/>-->
        <!--            <DeliveryTime v-if="currentQuestionType === 20"/>-->
        <!--            <BoxberryPoint v-if="currentQuestionType === 21"/>-->

      </div>

      <Alert/>

    </div>
  </div>


</template>

<script lang="ts">

import {Component, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";
import {eventBus} from "../../../bus";
const SQuestion = namespace('SQuestion')



import Pinned from "./Pinned.vue";
import QuestionHead from "./QuestionHead.vue";
// questions
// import Emoji from "../questions/Emoji.vue";
// import ColumnList from "../questions/ColumnList.vue";
// import CheckboxWidePlus from "../questions/CheckboxWidePlus.vue";
// import PicturesGrid from "../questions/PicturesGrid.vue";
// import ColumnListThin from "../questions/ColumnListThin.vue";
// import PickStyle from "../questions/PickStyle.vue";
// import Palletes from "../questions/Palletes.vue";
// import CustomForm from "../questions/CustomForm.vue";
// import StringList from "../questions/StringList.vue";
// import SetQueue from "../common/SetQueue.vue";
// import Colors from "../questions/Colors.vue";
// import Tables from "../questions/Tables.vue";
import Alert from "../common/Alert.vue";
// import Delivery from "../questions/Delivery.vue";
// import Coupon from "../questions/Coupon.vue";
// import ClientPhotos from "../questions/ClientPhotos.vue";
// import DeliveryTypes from "../subQuestions/DeliveryTypes.vue";
// import DeliveryDate from "../subQuestions/DeliveryDate.vue";
// import DeliveryTime from "../subQuestions/DeliveryTime.vue";
// import BoxberryCity from "../subQuestions/BoxberryCity.vue";
// import BoxberryPoint from "../subQuestions/BoxberryPoint.vue";
// import Photos from "../subQuestions/Photos.vue";

import {IQuestion} from "../../../types/anketa";

@Component({
    components: {
        // Photos,
        // BoxberryPoint,
        // BoxberryCity,
        // DeliveryTime,
        // DeliveryDate,
        // DeliveryTypes,
        // ClientPhotos,
        // Coupon,
        // Delivery,
        Alert,
        // Tables,
        // Colors,
        // SetQueue,
        // StringList,
        // CustomForm,
        // Palletes,
        // PickStyle,
        // ColumnListThin,
        // PicturesGrid,
        // ColumnList,
        Pinned, QuestionHead,
        // Emoji, CheckboxWidePlus
    },
})

export default class Question extends Vue {


    public q: boolean = false

    @SQuestion.State('questions') questions!: []
    @SQuestion.State('currentQuestion') currentQuestion!: IQuestion
  @SQuestion.Action('fetchQuestions') fetchQuestions!: () => void
    @SQuestion.Mutation('setCurrentQuestion') setCurrentQuestion!: () => void
    @SQuestion.Mutation('setQueuePaused') setQueuePaused!: (payload: boolean) => void

    @SQuestion.Action('saveAnswers') saveAnswers!: () => void

// => QUEUE
    @SQuestion.Mutation('setQueue') setQueue!: (payload: number) => void

    get queue() {
        return this.$store.state.SQuestion.queue
    }

    set queue(payload: number) {
        this.setQueue(payload)
    }

    get queuePaused() {
        return this.$store.state.SQuestion.queuePaused
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

    // dataMissed(){
    //     eventBus.$emit('data_missed')
    // }

    questionsLength() {
        let qty = []
        this.questions.map((i: IQuestion) => {
            if (!i.is_sub) {
                qty.push(i.id)
            }
        })
        return qty.length
    }

    mounted() {
      await this.fetchQuestions()
      await this.setCurrentQuestion()


        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        if (params.get("q")) {
            this.q = true

        }
    }
}
</script>
