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


        <form class="pay-method">
          <h3>Выберите удобный способ оплаты:</h3>

          <label class="pay-method__radio" v-for='(opt, idx) in payMethod.option'
                 :class="{on: payMethod.value === opt.type}" >

            <div class="pay-method__radio-wrap">
              <input type='radio' v-model='payMethod.value' :value='opt.type'>
              <span v-html="opt.title"></span>
            </div>

            <form id="paymentFormSample" action="javascript:" autocomplete="off" v-if="payMethod.value === 'card'">
              <div class="card-block">
                <div class="card-block__field card-block__field_number">
                  <input type="text" v-card placeholder="0000 0000 0000 0000" data-cp="cardNumber"
                         v-model="cryptogramValues.cardNumber">
                </div>
                <div class="card-block__field card-block__field_date" ref="date_col">
                  <input type="text" v-cardDate placeholder="MM/YY" v-model="cardDateFiller">
                  <input type="text"
                         placeholder="MM"
                         v-card-date data-cp="expDateMonth"
                         v-model="cryptogramValues.expDateMonth"
                         ref="expDateMonth"
                         style="display: none!important;"
                  ><input type="text"
                          placeholder="/YY"
                          v-card-date
                          data-cp="expDateYear"
                          v-model="cryptogramValues.expDateYear"
                          ref="expDateYear"
                          style="display: none!important;"
                >
                </div>
                <div class="card-block__field card-block__field_cvc">
                  <input type="text" v-card-cvc placeholder="CVC/CVV" data-cp="cvv" v-model="cryptogramValues.cvv">
                </div>
                <div class="card-block__field card-block__field_name">
                  <input type="text" placeholder="JOHN DOE" data-cp="name" v-model="cryptogramValues.name">
                </div>

              </div>
            </form>


          </label>
        </form>

        <div class='disclaimer' v-if="question.disclaimer">
          <div v-html="question.disclaimer"></div>
        </div>

      </div>

      <div class="answers__footer answers__footer_column">
        <TotalPrice/>

        <button class="next-question next-question_paid" @click="makePayment()">Перейти к оплате
        </button>

        <h3 v-if="$store.state.StoreAnketa.price === 0" class='paid'>Спасибо, по вашему промокоду услуги стилиста
          бесплатны. Мы приступили к работе над вашей подборкой 🙂</h3>
      </div>
    </div>

  </div>

</template>

<script lang="ts">

import {Component, Vue, Mixins, Prop} from 'vue-property-decorator'
import questionMixin from "../../../mixins/anketa/questionMixin";

import {IAnswer, IBonus, IQuestion, IQuestionOption} from "../../../types/anketa";
import {namespace} from "vuex-class";
import {Watch} from "vue-property-decorator";
import TotalPrice from "../subQuestions/TotalPrice.vue";
import QuestionHead from "../common/QuestionHead.vue";
import axios from "axios";
import {ICloudPaymentResponse, IPaymentRow} from "../../../types/payments";
import {eventBus} from "../../../bus";


const StoreAnketa = namespace('StoreAnketa')
const StoreSettings = namespace('StoreSettings')
@Component({
  components: {QuestionHead, TotalPrice}
})

export default class CouponCloudPayment extends Mixins(questionMixin) {

  @Prop() cloudPaymentPublicId!: string
  @Prop() cloudPaymentResultPage!: string

  public payMethod = {
    option: [
      {type: 'card', title: 'Оплата картой'},
      {type: 'googlePay', title: 'Google Pay'},
      // {type: 'applePay', title: 'Apple Pay'},
    ],
    value: null
  }


  $refs!: {
    fields: HTMLFormElement,
    date_col: HTMLFormElement,
    expDateMonth: HTMLFormElement,
    expDateYear: HTMLFormElement,
    TinkoffPayForm: HTMLFormElement
  }

  // => VARS

  public mark: string = 'PRICE_DO_NOT_DELETE'
  public couponContent!: IQuestionOption
  public couponInstantInfo: String = ''
  public couponCode: String = ''
  public success = false
  public fail = false
  public empty = false
  public showBonusInput = true

  public arrText: string[] = []

  public couponPrice: number = 0


  @StoreAnketa.State('uuid') uuid !: String
  @StoreAnketa.State('clientIp') clientIp !: string


  @StoreAnketa.Action('checkCoupon') checkCoupon !: (payload: Number | String) => void

  @StoreAnketa.Action('fetchClientIp') fetchClientIp!: () => void

  @StoreAnketa.Mutation('setPrice') setPrice!: (payload: any) => void

  @StoreAnketa.Action('fetchLeadUuid') fetchLeadUuid!: () => void
  @StoreAnketa.Mutation('setleadUuid') setleadUuid !: (payload: any) => void

  // Bonus
  @StoreAnketa.State('bonus') bonus !: IBonus
  @StoreAnketa.Action('checkBonus') checkBonus!: (payload: string) => void

  get leadUuid() {
    return this.$store.state.StoreAnketa.leadUuid
  }

  set leadUuid(payload) {
    this.setleadUuid(payload)
  }


  get price() {
    return this.$store.state.StoreAnketa.price
  }

  set price(payload) {
    this.setPrice(payload)
  }

  // BONUS

  // boinus part => hints
  hintHidden() {
    this.empty = false
    this.fail = false
    this.success = false
  }

  hintSuccess() {
    this.empty = false
    this.fail = false
    this.success = true
    this.saveAnswers()

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

  // boinus part => price watcher
  @Watch('price') onPriceChanged(price: any, oldPrice: any) {
    if (price < oldPrice || price && oldPrice === undefined) {
      this.question.question = this.arrText.join(price)
      this.hintSuccess()
    }
    if (price === undefined) {
      this.hintFail()
    }

  }



  async sendCode() {
    this.couponCode
        ? this.checkCoupon(this.couponCode)
        : this.hintEmpty()

    this.metrika('check_coupon', '')

    await this.saveAnswers();


  }

  // EOF BONUS

  // CLOUD
  public cryptogramValues = {
    cardNumber: '4242 4242 4242 4242', // Карта Visa с 3-D Secure 	 	Успешный результат 	Успешный результат
    // cardNumber: '5555 5555 5555 4444', //  Карта Mastercard с 3-D Secure 	 	Успешный результат 	Успешный результат
    // cardNumber: '4012 8888 8888 1881', // Карта Visa с 3-D Secure 	 	Недостаточно средств на карте 	—
    // cardNumber: '5105 1051 0510 5100', // Карта Mastercard с 3-D Secure 	 	Недостаточно средств на карте 	—
    // cardNumber: '4111 1111 1111 1111', // Карта Visa без 3-D Secure 	 	Успешный результат 	Недостаточно средств на карте
    // cardNumber: '5200 8282 8282 8210', //  Карта Mastercard без 3-D Secure 	 	Успешный результат 	Недостаточно средств на карте
    // cardNumber: '4000 0566 5566 5556', // Карта Visa без 3-D Secure 	 	Недостаточно средств на карте 	—
    // cardNumber: '5404 0000 0000 0043', // Карта Mastercard без 3-D Secure 	 	Недостаточно средств на карте 	—
    expDateMonth: '',
    expDateYear: '',
    cvv: '123',
    name: 'JOHN DOE'
  }

  // слитный ввод месяца и года
  @Watch('cardDateFiller') onCardDateFillerCanged(n, o) {
    let digits = n.split('/')
    this.cryptogramValues.expDateMonth = digits[0]
    this.cryptogramValues.expDateYear = digits[1]
  }



  pay() {
    this.saveAnswers()
  }

  public createCryptogram!: any
  public checkout!: any
  public packet: string = ''
  public cardDateFiller: string = ''

  async runCryptogram() {
    let result = this.checkout.createCryptogramPacket();
    if (result.success) {
      this.packet = result.packet
    } else {
      for (let msgName in result.messages) {
        alert(result.messages[msgName]);
      }
    }
  }



  // PAYMENT TYPE SWITCHER



  @Watch('payMethod.value') onPayMethodChanged(newValue:string,oldValue:string) {
    if( newValue == 'card') {
       this.cloudpaymentsCheckoutScript()
    }
    if(newValue == 'googlePay') {
       this.cloudpaymentsWidgetScript()
    }
    if(newValue == 'applePay') {
      this.cloudpaymentsWidgetScript()
    }
  }



  async makePayment() {
    this.metrika('go_to_pay', 'AddToCart')
    if(this.payMethod.value == 'card') {
      await this.paymentTypeCheckout()
    }
    if(this.payMethod.value == 'googlePay') {
      await this.paymentTypeWidget()
    }
  }

  async paymentTypeCheckout() {
    // @ts-ignore
    this.checkout = new cp.Checkout(
        this.cloudPaymentPublicId,
        document.getElementById("paymentFormSample"));
    await this.runCryptogram()


    await axios.post('/questions', {
      func: 'payment',
      // payment values
      Amount: this.price,
      Currency: 'RUB',
      Name: this.cryptogramValues.name,
      CardCryptogramPacket: this.packet,
      // required values
      leadUuid: this.leadUuid,

    }).then(response => {
      this.paymentInteraction(response.data)
    }).catch(err => {
      this.axiosErrorHandler(err)
    })
  }

  async paymentTypeWidget() {
    // @ts-ignore
    let widget = new cp.CloudPayments();
    widget.pay('auth', // или 'charge'
        { //options
          publicId: this.cloudPaymentPublicId, //id из личного кабинета
          description: 'Оплата услуг стилиста в thecapsula.ru', //назначение
          amount: this.$store.state.StoreAnketa.price, //сумма
          currency: 'RUB', //валюта
          accountId: this.$store.state.StoreAnketa.answers.contacts.forms.email, //идентификатор плательщика (необязательно)
          invoiceId: this.leadUuid, //номер заказа  (необязательно)
          email: this.$store.state.StoreAnketa.answers.contacts.forms.email, //email плательщика (необязательно)
          skin: "mini", //дизайн виджета (необязательно)
          data: {
            anketaUuid: this.uuid
          }
        },
        {
          onSuccess: (options) => { // success
            console.log('onSuccess options', options);
          },
          onFail:  (reason, options) => { // fail
            console.log('onFail options', reason, options);
          },
          onComplete:  (paymentResult, options) => { //Вызывается как только виджет получает от api.cloudpayments ответ с результатом транзакции.
            //например вызов вашей аналитики Facebook Pixel
            console.log('onComplete options', paymentResult, options);

            axios.post('/questions', {
              func: 'googlePayWidget',
              leadUuid: options.invoiceId,
              options
            }).then(response => {
              // console.log('response.data',response.data);
              // this.paymentInteraction(response.data)
              this.$router.push({name: "frontend.anketa.cloud.success", params: {leadUuid: 'success'}, query: {
                  Amount: this.price
                }})
            }).catch(err => {
              this.axiosErrorHandler(err)
            })

          }
        }
    )
  }

  axiosErrorHandler(err) {
    console.log('err', err);

  }

  // cloud payment response
  paymentInteraction(CPResponse: ICloudPaymentResponse) {
    if (CPResponse.hasOwnProperty('id')) { // Платеж успешно завершен
      this.successPayment();
      return false;
    }

    if (CPResponse.hasOwnProperty('Model') && CPResponse.Model.hasOwnProperty('AcsUrl')) { // 3Dsecure
      let form = document.createElement('form');
      document.body.appendChild(form);
      form.method = 'post';
      form.action = CPResponse.Model.AcsUrl;
      let data = [
        {name: 'PaReq', value: CPResponse.Model.PaReq},
        {name: 'MD', value: CPResponse.Model.TransactionId.toString()},
        {name: 'TermUrl', value: this.cloudPaymentResultPage + this.leadUuid}, // 'http://stage-anketa.thecapsula.ru/payment/'
      ]
      data.map((i) => {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = i.name;
        input.value = i.value;
        form.appendChild(input);
      })

      form.submit();
    }

    if (!CPResponse.Success && CPResponse.Model.ReasonCode) {
      eventBus.$emit('openAlert', CPResponse.Model.CardHolderMessage);

    }

    return CPResponse

  }


  successPayment() {
    this.$router.push({name: "frontend.anketa.cloud.success", params: {leadUuid: 'success'}, query: {
        Amount: this.price
      }})
  }

  async checkPromocode() {
    if (this.$route.query.rf) {
      // @ts-ignore
      await this.checkBonus(this.$route.query.rf)
      if (await this.bonus) {
        this.price = this.price - 500
      }
    }
  }

  cloudpaymentsCheckoutScript() {

    let elem = document.getElementById("widget")

    if (elem) {
      // @ts-ignore
      elem.parentNode.removeChild(elem)
    }

    let checkout = document.createElement('script')
    checkout.setAttribute('id','checkout')
    checkout.setAttribute('src', 'https://checkout.cloudpayments.ru/checkout.js')
    let same = document.getElementById("checkout")
    if(!same) {
      document.head.appendChild(checkout)
    }

  }

  cloudpaymentsWidgetScript() {

    let elem = document.getElementById("checkout")

    if (elem) {
      // @ts-ignore
      elem.parentNode.removeChild(elem)
    }

    let widget = document.createElement('script')
    widget.setAttribute('id','widget')
    widget.setAttribute('src', 'https://widget.cloudpayments.ru/bundles/cloudpayments')
    let same = document.getElementById("widget")
    if(!same) {
      document.head.appendChild(widget)
    }
  }

  isMacintosh() {
    return navigator.platform.indexOf('Mac') > -1
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

    this.fetchLeadUuid()
    await this.checkPromocode()


  }

}
</script>

<style>

input:disabled, button:disabled {
  cursor: unset;
  opacity: .3;
}

</style>
