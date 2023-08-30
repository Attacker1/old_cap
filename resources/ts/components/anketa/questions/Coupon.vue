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

        <!--<form class="pay-method">

          <label class="pay-method__radio on">

            <form id="paymentFormSample" action="javascript:" autocomplete="off">
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
        </form>-->

        <div class='disclaimer' v-if="question.disclaimer">
          <div v-html="question.disclaimer"></div>
        </div>

      </div>

      <div class="answers__footer answers__footer_column">
        <TotalPrice/>
        <div class="paid__form" v-if="$store.state.StoreAnketa.price!=0">

          <!--name="TinkoffPayForm" onsubmit="pay(this); return false;"-->
          <form action="javascript:" name="TinkoffPayForm" ref="tinkoffForm">
            <input type="hidden" name="terminalkey" :value="tinkoffTerminalKey" class="tinkoffPayRow">
            <input type="hidden" name="frame" value="false" class="tinkoffPayRow">
            <input type="hidden" name="language" value="ru" class="tinkoffPayRow">
            <input type="hidden" placeholder="Сумма заказа" name="amount" required="required" class="tinkoffPayRow" :value='$store.state.StoreAnketa.price'>
            <input type="hidden" placeholder="Номер заказа" name="order" class="tinkoffPayRow" :value='"fa" + uuid'>
            <input type="hidden" placeholder="Описание заказа" name="description" value="Оплата услуг стилиста" class="tinkoffPayRow">
            <input type="hidden" placeholder="ФИО плательщика" name="name" class="tinkoffPayRow" :value='$store.state.StoreAnketa.answers.fio.forms.bioSurname + " " + $store.state.StoreAnketa.answers.fio.forms.bioName'>
            <input type="hidden" placeholder="E-mail" name="email" class="tinkoffPayRow" :value='$store.state.StoreAnketa.answers.contacts.forms.email'>
            <input type="hidden" placeholder="Контактный телефон" name="phone" class="tinkoffPayRow" :value='$store.state.StoreAnketa.answers.contacts.forms.phone'>
            <input type="hidden" name="receipt" class="tinkoffPayRow" :value="receipt()">
            <!--<input type="hidden" name="receipt" class="tinkoffPayRow"
                   :value="'{&quot;Email&quot;:&quot;'+$store.state.StoreAnketa.answers.contacts.forms.email+'&quot;,&quot;Phone&quot;:&quot;'+'$store.state.StoreAnketa.answers.contacts.forms.phone'+'&quot;,&quot;EmailCompany&quot;:&quot;ps@thecapsula.ru&quot;,&quot;Taxation&quot;:&quot;usn_income_outcome&quot;,&quot;Items&quot;:[{&quot;Name&quot;:&quot;Оплата услуг стилиста&quot;,&quot;Price&quot;:'+$store.state.StoreAnketa.price*100+',&quot;Quantity&quot;:1,&quot;Amount&quot;:'+$store.state.StoreAnketa.price*100+',&quot;PaymentMethod&quot;:&quot;full_payment&quot;,&quot;PaymentObject&quot;:&quot;service&quot;,&quot;Tax&quot;:&quot;none&quot;}]}'"> -->
            <button class="next-question next-question_paid" @click="tinkoffSubmit()">Перейти к оплате</button></form>

          <form action="" method="post" ref="redtracker_form">
            <input type="hidden" name="redtracker" value="{clickid}">
          </form>

        </div>
        <h3 v-else class='paid'>Спасибо, по вашему промокоду услуги стилиста бесплатны. Мы приступили к работе над вашей подборкой 🙂</h3>
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

  export default class Coupon extends Mixins(questionMixin) {

    @Prop() cloudPaymentPublicId!: string
    @Prop() cloudPaymentResultPage!: string
    @Prop() tinkoffTerminalKey!: string

    $refs!: {
      fields: HTMLFormElement,
      date_col: HTMLFormElement,
      expDateMonth: HTMLFormElement,
      expDateYear: HTMLFormElement,
      TinkoffPayForm: HTMLFormElement,
      tinkoffForm: HTMLFormElement,
      redtracker_form: HTMLFormElement
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

    receipt() {

      let ret!: any
      ret = {
        "Email": this.$store.state.StoreAnketa.answers.contacts.forms.email,
        "Phone": this.$store.state.StoreAnketa.answers.contacts.forms.phone,
        "EmailCompany": "ps@thecapsula.ru",
        "Taxation": "usn_income_outcome",
        "Items": [{
          "Name": "Оплата услуг стилиста",
          "Price": this.$store.state.StoreAnketa.price * 100,
          "Quantity": 1,
          "Amount": this.$store.state.StoreAnketa.price * 100,
          "PaymentMethod": "full_payment",
          "PaymentObject": "service",
          "Tax": "none"
        }]
      }
      // console.log(JSON.stringify(ret), 'ret')
      return JSON.stringify(ret)
      //return '{"Email":"'+this.$store.state.StoreAnketa.answers.contacts.forms.email+'","Phone":"'+this.$store.state.StoreAnketa.answers.contacts.forms.phone+'","EmailCompany":"ps@thecapsula.ru","Taxation":"usn_income_outcome","Items":[{"Name":"Оплата услуг стилиста","Price":'+this.$store.state.StoreAnketa.price+'00'+',"Quantity":1,"Amount":'+this.$store.state.StoreAnketa.price+'00'+',"PaymentMethod":"full_payment","PaymentObject":"service","Tax":"none"}]}'
    }

    tinkoffSubmit() {
      localStorage.setItem('redtracker', this.$refs.redtracker_form.redtracker.value)

      if(!this.price) {
        console.log('zero');
        return false
      }
      this.metrika('go_to_pay', 'AddToCart')
      setTimeout(() => {
        // @ts-ignore
        pay(this.$refs.tinkoffForm);
      }, 1000)
      return false;
    }

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

    @Watch('price') onPriceChanged(price: any, oldPrice: any) {
      if (price < oldPrice || price && oldPrice === undefined) {
        this.question.question = this.arrText.join(price)
        this.hintSuccess()
      }
      if (price === undefined) {
        this.hintFail()
      }

    }


    // @Watch('cryptogramValues.expDateMonth') onExpDateMonthCanged(n,o) {
    //   n.length === 2
    //       ? this.$refs.date_col.classList.add('active')
    //       : this.$refs.date_col.classList.remove('active')
    //
    // }

    @Watch('cardDateFiller') onCardDateFillerCanged(n, o) {
      let digits = n.split('/')
      this.cryptogramValues.expDateMonth = digits[0]
      this.cryptogramValues.expDateYear = digits[1]
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

    async sendCode() {
      this.couponCode
              ? this.checkCoupon(this.couponCode)
              : this.hintEmpty()

      this.metrika('check_coupon', '')

      await this.saveAnswers();


    }

    pay() {
      this.saveAnswers()
    }

    public createCryptogram!: any
    public checkout!: any
    public packet: string = ''
    public cardDateFiller: string = ''

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

    async makePayment() {

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
        eventBus.$emit('openAlert',CPResponse.Model.CardHolderMessage);

      }

      return CPResponse

    }


    successPayment() {
      this.$router.push({name: "frontend.anketa.payment", params: {leadUuid: 'success'}})
    }

    async checkPromocode() {
      if(this.$route.query.rf) {
        // @ts-ignore
        await this.checkBonus(this.$route.query.rf)

        console.log('bon',this.$route.query.rf);


        if(await this.bonus) {

          this.price = this.price - 300
        }
      }
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

    tinkoff() {
      let tinkoff = document.createElement('script')
      tinkoff.setAttribute('id','tinkoff')
      tinkoff.setAttribute('src', 'https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js')
      document.head.appendChild(tinkoff)
    }



    async mounted() {
      await this.tinkoff();

    }

  }
</script>

<style>

  input:disabled, button:disabled {
    cursor: unset;
    opacity: .3;
  }

</style>
