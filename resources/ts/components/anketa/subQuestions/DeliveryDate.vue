<template>
  <div class="answers">
    <div class="answers__header"></div>
    <div class="answers__body answers__body_flex">
      <DropdDatePicker
          :date="forms['deliveryDate']"
          placeholder="Выберите день доставки"
          :valid="valid"
          @dateChanged="dateChanged"
      />

      <div class="datepicker">
        <div class="datepicker__text">Выберите день доставки</div>
        <div class="datepicker__inner">
          <!--                    <dropdown-datepicker-->
          <!--                        year-label="Год"-->
          <!--                        month-label="Месяц"-->
          <!--                        day-label="День"-->
          <!--                        :monthLongValues="monthes"-->
          <!--                        submitFormat="yyyy-mm-dd"-->
          <!--                        v-model="forms['deliveryDate']"-->
          <!--                        :minDate="setMinDate2()"-->
          <!--                        :defaultDate="forms['deliveryDate']"-->
          <!--                        @input="checkDate()"-->
          <!--                        :allowPast="false"-->
          <!--                        :daySuffixes="false"-->
          <!--                    >-->
          <!--                    </dropdown-datepicker>-->
        </div>
        <div class="datepicker__message"></div>
      </div>


      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>

    </div>

    <div class="answers__footer">
      <button class="next-question" @click="nextQuestion()">Дальше</button>
    </div>

  </div>

</template>

<script lang="ts">

import {Component, Mixins, Vue, Watch} from "vue-property-decorator"
import {IAnswer, IQuestion, IQuestionOption} from "../../../types/anketa"
import {namespace} from "vuex-class"
import {eventBus} from "../../../bus";

// import DropdownDatepicker from "vue-dropdown-datepicker/src/dropdown-datepicker.vue"
import DropdDatePicker from "../inputs/DropDatePicker.vue";
import questionMixin from "../../../mixins/anketa/questionMixin";

const StoreAnketa = namespace('StoreAnketa')
@Component({components: {DropdDatePicker}})

export default class DeliveryDate extends Mixins(questionMixin) {
  $refs!: {
    fields: HTMLFormElement
  }

  public valid!: String


  public monthes = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
  public daySuffixValues = ['', '', '', '']

  // => VARS
/*
  get question(): IQuestion {
    return this.$store.state.StoreAnketa.currentQuestion
  }

  @StoreAnketa.Mutation('setQueue') setQueue !: (payload: number) => void

  get queue() {
    return this.$store.state.StoreAnketa.queue
  }

  set queue(payload: number) {
    this.setQueue(payload)
  }

  @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void

  get options() {
    return this.$store.state.StoreAnketa.options
  }

  set options(payload) {
    this.setAnswer({options: payload, own: this.own, forms: this.forms})
  }
*/

  @StoreAnketa.Mutation('setPrewQuestion') setPrewQuestion!: (payload: number) => void

  get prewQuestion() {
    return this.$store.state.StoreAnketa.prewQuestion
  }

  set prewQuestion(payload) {
    this.setPrewQuestion(payload)
  }

/*  get own() {
    return this.$store.state.StoreAnketa.own
  }

  set own(payload) {
    this.setAnswer({options: this.options, own: payload, forms: this.forms})
  }

  get forms() {
    return this.$store.state.StoreAnketa.forms
  }

  set forms(payload) {
    this.setAnswer({options: this.options, own: this.own, forms: payload})
  }*/

  // @StoreAnketa.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void
  // @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void

  makeForms() {
    // this.forms = Array.isArray(this.$refs.fields)
    //     ?  this.$refs.fields.map((i: HTMLFormElement) => i.value)
    //     : [this.$refs.fields.value]
    this.forms = this.forms
  }


  setYear() {
  }

  setMonth() {
  }


  public now = new Date()

  setMinDate2() {
    let date = new Date()
    date.setDate(date.getDate() + 6);
    return date.toISOString().substr(0, 10)
  }

  // METHODS
  setMinDate() {
    let date = new Date()
    date.setDate(date.getDate() + 6);
    this.forms['deliveryDate'] = date.toISOString().substr(0, 10)
  }

  checkDate() {
    let date1 = new Date()
    let date2 = new Date(this.forms['deliveryDate']);
    let daysLag = Math.ceil((date2.getTime() - date1.getTime()) / (1000 * 3600 * 24))
    if (daysLag < 6) {
      this.setMinDate()
      eventBus.$emit('openAlert', 'Минимальное время для сбора капсулы 6 дней. Дата доставки автоматически установлена на ближайшую возможную')
    }
  }

  nextQuestion() {
    this.save()
/*    const currentOption: IQuestionOption | undefined = this.question.options.find((i: IQuestionOption) => i.next_question !== null)
    if (currentOption && currentOption.next_question) {
      this.makeForms()
      this.swapQuestions(currentOption.next_question)
      this.saveAnswers()
      this.queue++
    } else {
      this.saveAnswers()
      this.queue++
    }*/
  }


  dateChanged(ev: any) {
    this.checkDate()
  }

 async mounted() {
    this.setMinDate()
    // eventBus.$emit('paused',true)
    // eventBus.$on('innerQueue', () => {
    //     eventBus.$emit('paused', false)
    // })
  }
}
</script>
