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
                            <input :disabled="success" :class="{applied: success}" type="text" :placeholder="couponContent.placeholder" v-model="couponCode">
                            <button :disabled="success" :class="{applied: success}" class="coupon-check error" @click="sendCode()">Проверить</button>

                            <div v-if="fail" class="input-wrap__message">купон недействителен</div>
                            <div v-if="empty" class="input-wrap__message">введите купон</div>
                            <div v-if="success" class="input-wrap__message success">Скидка применена</div>

                        </div>

                    </div>
                </div>

            </div>

            <!--            <div><span v-if="!success" class="coupon-text">{{couponContent.text}}</span>-->
            <!--   -->
            <!--            </div>-->


            <div class="answers__footer answers__footer_column">
                <TotalPrice/>
                <div class="paid__form">
                    <form name="TinkoffPayForm" ref="TinkoffPayForm" onsubmit="pay(this); return false;">
                        <input class="tinkoffPayRow" type="hidden" name="terminalkey"
                               value="<?php echo TINKOFF_TERMINAL_KEY; ?>">
                        <input class="tinkoffPayRow" type="hidden" name="frame" value="false">
                        <input class="tinkoffPayRow" type="hidden" name="language" value="ru">
                        <!--                        <input class="tinkoffPayRow" type="hidden" placeholder="Сумма заказа" name="amount" required-->
                        <!--                               :value='anketa.amount'>-->
                        <!--                        <input class="tinkoffPayRow" type="hidden" placeholder="Номер заказа" name="order"-->
                        <!--                               :value='"fa" + anketa_uuid'>-->
                        <input class="tinkoffPayRow" type="hidden" placeholder="Описание заказа" name="description"
                               value='Оплата услуг стилиста'>
                        <!--                        <input class="tinkoffPayRow" type="hidden" placeholder="ФИО плательщика" name="name"-->
                        <!--                               :value='anketa.question[0].answer + " " + anketa.question[67].answer'>-->
                        <!--                        <input class="tinkoffPayRow" type="hidden" placeholder="E-mail" name="email"-->
                        <!--                               :value='anketa.question[14].answer'>-->
                        <!--                        <input class="tinkoffPayRow" type="hidden" placeholder="Контактный телефон" name="phone"-->
                        <!--                               :value='anketa.question[15].answer'>-->
                        <input class="tinkoffPayRow" type="hidden" name="receipt" :value="receipt">
                        <button class="next-question next-question_paid">Перейти к оплате</button>
                    </form>
                </div>
            </div>

            <!--        <form name="TinkoffPayForm" onsubmit="pay(this); return false;">
                        <input class="tinkoffPayRow" type="hidden" name="terminalkey" value="TinkoffBankTest">
                        <input class="tinkoffPayRow" type="hidden" name="frame" value="true">
                        <input class="tinkoffPayRow" type="hidden" name="language" value="ru">
                        <input class="tinkoffPayRow" type="text" placeholder="Сумма заказа" value="2" name="amount" required>
                        <input class="tinkoffPayRow" type="text" placeholder="Номер заказа" value="54654654654654" name="order">
                        <input class="tinkoffPayRow" type="text" placeholder="Описание заказа" name="description">
                        <input class="tinkoffPayRow" type="text" placeholder="ФИО плательщика" value="test" name="name">
                        <input class="tinkoffPayRow" type="text" placeholder="E-mail" name="email" value="test@email.com">
                        <input class="tinkoffPayRow" type="text" placeholder="Контактный телефон" name="phone" value="89999999999">
                        <input class="tinkoffPayRow" type="submit" value="Оплатить">
                    </form>-->
        </div>


    </div>

</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion, IQuestionOption} from "@/types/anketa";
import {namespace} from "vuex-class";
import {Watch} from "vue-property-decorator";
import Question from "@/components/common/Question.vue";
import TotalPrice from "@/components/subQuestions/TotalPrice.vue";
import QuestionHead from "@/components/common/QuestionHead.vue";

const SQuestion = namespace('SQuestion')
@Component({
    components: {QuestionHead, TotalPrice}
})

export default class Coupon extends Vue {

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

    public couponPrice:number = 0


    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }

    @SQuestion.State('uuid') uuid !: String

    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void
    @SQuestion.Mutation('setQueue') setQueue !: (payload: number) => void

    @SQuestion.Action('checkCoupon') checkCoupon !: (payload: Number | String) => void

    @SQuestion.Action('saveAnswers') saveAnswers!: () => void


    get queue() {
        return this.$store.state.SQuestion.queue
    }

    set queue(payload: number) {
        this.setQueue(payload)
    }

    get options() {
        return this.$store.state.SQuestion.options
    }

    set options(payload) {
        this.setAnswer({options: payload, own: this.own, forms: this.forms})
    }

    get own() {
        return this.$store.state.SQuestion.own
    }

    set own(payload) {
        this.setAnswer({options: this.options, own: payload, forms: this.forms})
    }

    get forms() {
        return this.$store.state.SQuestion.forms
    }

    set forms(payload) {
        this.setAnswer({options: this.options, own: this.own, forms: payload})
    }

    @SQuestion.Mutation('setPrice') setPrice!: (payload: any) => void

    get price() {
        return this.$store.state.SQuestion.price
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


    async created() {

        this.setSlug(this.question.slug)

        this.arrText = this.question.question.split(this.mark)

        this.hintHidden()


        // @ts-ignore
        this.couponContent = this.question.options.find((i: IQuestionOption) => i.type === 'coupon')
        await this.setPrice(this.couponContent.text_thumb)
        this.couponInstantInfo = this.question.question
        this.question.question = this.question.question.replace(this.mark, this.price)
    }

}
</script>

<style>
.applied {
    opacity: .5;
}
.success {
    color: #00E3A7!important;
}
/*.answers__body_payment div {*/
/*    display: flex;*/
/*    align-items: center;*/
/*    height: 100%;*/

/*}*/
/*@media (max-width:1024px) {*/
/*    .answers__body_payment div{*/
/*        justify-content: center;*/
/*    }*/
/*}*/
</style>
