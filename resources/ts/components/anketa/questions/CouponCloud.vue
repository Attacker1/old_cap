<template>

    <div class="question">
      <QuestionHead>
        <TotalPrice/>
      </QuestionHead>

      <div class="answers">

        <div class="answers__header"></div>
        <div class="answers__body answers__body_payment">
          <div>
            <div class="paid-container">
              <div class="input-wrap input-wrap_coupon">
                <input :disabled="success" :class="{applied: success}" type="text"
                       :placeholder="couponContent.placeholder" v-model="couponCode">
                <button :disabled="success" :class="{applied: success}" class="coupon-check error" @click="sendCode()">
                  Проверить
                </button>

                <div v-if="fail" class="input-wrap__message">купон недействителен</div>
                <div v-if="empty" class="input-wrap__message">введите купон</div>
                <div v-if="success" class="input-wrap__message success">Скидка применена</div>

              </div>

            </div>
          </div>


          <label class="pay-method__radio on">


            <div class="card-block">
              <form id="paymentFormSample" action="javascript:" autocomplete="off">
              <div class="card-block__field card-block__field_number">
                <input type="text" v-card placeholder="0000 0000 0000 0000" data-cp="cardNumber"
                       v-model="cryptogramValues.cardNumber">
              </div>
              <div class="card-block__field card-block__field_date">
                <input type="text" placeholder="MM" v-card-date data-cp="expDateMonth"
                       v-model="cryptogramValues.expDateMonth"><input type="text" placeholder="YY" v-card-date
                                                                      data-cp="expDateYear"
                                                                      v-model="cryptogramValues.expDateYear">
              </div>
              <div class="card-block__field card-block__field_cvc">
                <input type="text" v-card-cvc placeholder="CVC/CVV" data-cp="cvv" v-model="cryptogramValues.cvv">
              </div>
              <div class="card-block__field card-block__field_name">
                <input type="text" placeholder="JOHN DOE" data-cp="name" v-model="cryptogramValues.name">
              </div>
              </form>
            </div>

          </label>


        </div>


        <div class="answers__footer answers__footer_column">
          <TotalPrice/>
          <div class="paid__form">
            <button class="next-question next-question_paid" @click="makeE()">Перейти к оплате</button>
          </div>
        </div>

      </div>


    </div>

</template>

<script lang="ts">

import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion, IQuestionOption} from "../../../types/anketa";
import {namespace} from "vuex-class";
import {Watch} from "vue-property-decorator";
// import Question from "@/components/common/Question.vue";
import TotalPrice from "../subQuestions/TotalPrice.vue";
import QuestionHead from "../common/QuestionHead.vue";
import {createServer} from 'http';
import {ClientService, TaxationSystem, ResponseCodes} from "cloudpayments";
import axios from "axios";

const StoreAnketa = namespace('StoreAnketa')
const StoreSettings = namespace('StoreSettings')
@Component({
  components: {QuestionHead, TotalPrice}
})

export default class CouponCloud extends Vue {


  receipt() {
    let ret = {
      "Email": 'test@test.com',
      "Phone": '79999999999',
      "EmailCompany": "ps@thecapsula.ru",
      "Taxation": "usn_income_outcome",
      "Items": [{
        "Name": "Оплата услуг стилиста",
        "Price": 490 * 100,
        "Quantity": 1,
        "Amount": 490 * 100,
        "PaymentMethod": "full_payment",
        "PaymentObject": "service",
        "Tax": "none"
      }]
    };

    return JSON.stringify(ret);
  }

  receiptDEMO() {
    let ret = {
      "Email": 'test@test.com',
      "Phone": '79999999999',
      "EmailCompany": "ps@thecapsula.ru",
      "Taxation": "usn_income_outcome",
      "Items": [{
        "Name": "Оплата услуг стилиста",
        "Price": 490 * 100,
        "Quantity": 1,
        "Amount": 490 * 100,
        "PaymentMethod": "full_payment",
        "PaymentObject": "service",
        "Tax": "none"
      }]
    };

    return JSON.stringify(ret);
  }


  terminalDemo() {
    return {
      terminalkey: '1581515926868DEMO',
      frame: 'false',
      language: 'ru',
      amount: 2,
      order: 'Оплата услуг стилиста',
      name: 'test testov',
      email: 'test@test.test',
      phone: '(111) 111-11-11',
    }
  }


  $refs!: {
    fields: HTMLFormElement
  }

  // => VARS

  public mark: string = 'PRICE_DO_NOT_DELETE'
  public couponContent!: IQuestionOption
  public couponInstantInfo: String = ''
  public couponCode: String = ''
  public success = false
  public fail = false
  public empty = false

  public arrText: string[] = []

  public couponPrice: number = 0


  get question(): IQuestion {
    return this.$store.state.StoreAnketa.currentQuestion
  }

  @StoreAnketa.State('uuid') uuid !: String
  @StoreAnketa.State('clientIp') clientIp !: String

  @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
  @StoreAnketa.Mutation('setSlug') setSlug !: (payload: string) => void
  @StoreAnketa.Mutation('setQueue') setQueue !: (payload: number) => void

  @StoreAnketa.Action('checkCoupon') checkCoupon !: (payload: Number | String) => void

  @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void
  @StoreAnketa.Action('fetchClientIp') fetchClientIp!: () => void
  // @StoreAnketa.Action('fetchCloudpaymentResponce') fetchCloudpaymentResponce!: (payload: any) => void


  get queue() {
    return this.$store.state.StoreAnketa.queue
  }

  set queue(payload: number) {
    this.setQueue(payload)
  }

  get options() {
    return this.$store.state.StoreAnketa.options
  }

  set options(payload) {
    this.setAnswer({options: payload, own: this.own, forms: this.forms})
  }

  get own() {
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
  }

  @StoreAnketa.Mutation('setPrice') setPrice!: (payload: any) => void

  get price() {
    return this.$store.state.StoreAnketa.price
  }

  set price(payload) {
    this.setPrice(payload)
  }

  @Watch('price') onPriceChanged(price: any, oldPrice: any) {
    console.log(price, oldPrice);
    if (price < oldPrice || price && oldPrice === undefined) {
      this.question.question = this.arrText.join(price)
      this.hintSuccess()
    }
    if (price === undefined) {
      this.hintFail()
    }

  }

  // METHODS
  checkedClass(id: Number) {
    return this.question.multiple
        ? this.options && this.options.indexOf(id) !== -1
        : this.options === id
  }

  makeForms() {
    this.forms = this.$refs.fields.map((i: HTMLFormElement) => i.value)
  }

  hintHidden() {
    this.empty = false
    this.fail = false
    this.success = false
  }

  hintSuccess() {
    this.empty = false
    this.fail = false
    this.success = true
  }

  hintFail() {
    this.empty = false
    this.fail = true
    this.success = false
  }

  hintEmpty() {
    this.empty = true
    this.fail = false
    this.success = false
  }

  sendCode() {
    this.couponCode
        ? this.checkCoupon(this.couponCode)
        : this.hintEmpty()
  }

  pay() {
    this.saveAnswers()
  }

  public createCryptogram!: any
  public checkout!: any

  public cryptogramValues = {
    cardNumber: '4242424242424242',
    expDateMonth: 10,
    expDateYear: 24,
    cvv: 123,
    name: 'test testov'
  }

 async createCryptogramFn() {
    var result = this.checkout.createCryptogramPacket();
    console.log('result', result);
    if (result.success) {
      // сформирована криптограмма
      /*
      this.fetchCloudpaymentResponce({
        "Amount": 10,
        // "Currency":"RUB",
        "IpAddress": this.clientIp,
        "CardCryptogramPacket": result.packet,
        // "Name":"CARDHOLDER NAME",
        // "PaymentUrl" : 'Необязательный Адрес сайта, с которого совершается вызов скрипта checkout',
        // "InvoiceId":"1234567",
        // "Description":"Оплата товаров в example.com",
        // "AccountId":"user_x",
        //
        //
        // "Payer":
        //     {
        //       "FirstName":"Тест",
        //       "LastName":"Тестов",
        //       "MiddleName":"Тестович",
        //       "Birth":"1955-02-24",
        //       "Address":"тестовый проезд дом тест",
        //       "Street":"Lenina",
        //       "City":"MO",
        //       "Country":"RU",
        //       "Phone":"123",
        //       "Postcode":"345"
        //     }
      })
      */
      // alert(result.packet);

      let payload = {
        "Amount": 10,
        "IpAddress": this.clientIp,
        "CardCryptogramPacket": result.packet,
      }


      const response = await axios.post('https://api.cloudpayments.ru/payments/cards/charge', payload, {
        headers: {
          'Access-Control-Allow-Origin': '*',
          'Access-Control-Allow-Methods:': 'GET, PUT, POST, DELETE, OPTIONS',
          'Content-Type': 'application/json',
        },
        auth: {
          username: 'pk_2a66cefadc9e423bec930d7a5b3d6',
          password: 'e8c54197412ca0ac506b5d0f5661c659'
        }
      })
      console.log('cloudpaymentResponce', await response);



    } else {
      // найдены ошибки в введённых данных, объект `result.messages` формата:
      // { name: "В имени держателя карты слишком много символов", cardNumber: "Неправильный номер карты" }
      // где `name`, `cardNumber` соответствуют значениям атрибутов `<input ... data-cp="cardNumber">`
      for (var msgName in result.messages) {
        alert(result.messages[msgName]);
      }
    }
  }



  makeE() {

    const client = new ClientService({
      endpoint: 'https://api.cloudpayments.ru/payments/cards/charge',
      privateKey: 'e8c54197412ca0ac506b5d0f5661c659',
      publicId: 'pk_2a66cefadc9e423bec930d7a5b3d6'
    })


    // @ts-ignore
    this.checkout = new cp.Checkout(
        // public id из личного кабинета
        "pk_2a66cefadc9e423bec930d7a5b3d6",
        // тег, содержащий поля данных карты
        document.getElementById("paymentFormSample"));

    console.log('checkout', this.checkout);

    this.createCryptogramFn()


    console.log('createCryptogram', this.createCryptogram);


  }


  async created() {

    this.setSlug(this.question.slug)

    this.arrText = this.question.question.split(this.mark)

    this.hintHidden()


    // @ts-ignore
    this.couponContent = this.question.options.find((i: IQuestionOption) => i.type === 'coupon')
    await this.setPrice(this.couponContent.text_thumb)
    this.couponInstantInfo = this.question.question
    this.question.question = this.question.question.replace(this.mark, this.price)


    this.fetchClientIp()


  }


}
</script>

<style>

</style>
