<template>
    <div class="answers">

        <div class="answers__header"></div>

        <div class="answers__body">

            <boxberry :city="boxberryCity"></boxberry>

            <div>
                <div id="pvz_city"></div>
                <div id="pvz_js-pricedelivery"></div>
                <div id="pvz_code"></div>
                <div id="pvz_name"></div>
                <div id="pvz_address"></div>
                <div id="pvz_workschedule"></div>
                <div id="pvz_phone"></div>
                <div id="delivery_point_id"></div>
                <div id="delivery_adress"></div>
            </div>

            <input type="hidden" v-model="forms['boxberryPoint']">

        </div>

        <div class="answers__footer">
            <button class="next-question" @click="nextQuestion()">Дальше</button>
        </div>

    </div>

</template>

<script lang="ts">

import {Component, Vue, Watch} from "vue-property-decorator"
import {IAnswer, IQuestion, IQuestionOption} from "../../../types/anketa"
import {namespace} from "vuex-class"
import {eventBus} from "../../../bus";

const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class BoxberryPoint extends Vue {
    $refs!: {
        fields: HTMLFormElement
    }
    // => VARS
    @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void

    get question(): IQuestion {
        return this.$store.state.StoreAnketa.currentQuestion
    }

    get boxberryCity(): IQuestion {
        return this.$store.state.StoreAnketa.boxberryCity
    }

    @StoreAnketa.Mutation('setQueue') setQueue !: (payload: number) => void
    get queue() {return this.$store.state.StoreAnketa.queue}
    set queue(payload: number) {this.setQueue(payload)}

    @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    get options() {return this.$store.state.StoreAnketa.options}
    set options(payload) {this.setAnswer({options: payload, own: this.own, forms: this.forms})}

    @StoreAnketa.Mutation('setPrewQuestion') setPrewQuestion!: (payload: number) => void
    get prewQuestion() { return this.$store.state.StoreAnketa.prewQuestion }
    set prewQuestion(payload) { this.setPrewQuestion(payload)}

    get own () {return this.$store.state.StoreAnketa.own}
    set own (payload) {this.setAnswer({options: this.options, own: payload, forms: this.forms})}

    get forms () {return this.$store.state.StoreAnketa.forms}
    set forms (payload) {this.setAnswer({options: this.options, own: this.own, forms: payload})}

    @StoreAnketa.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void

    @StoreAnketa.Action('fetchSities') fetchSities!: (payload: string) => void
    @StoreAnketa.Mutation('setSities') setSities!: (payload: any[]) => void
    get cities() {return this.$store.state.StoreAnketa.cities}
    set cities(payload) {this.setSities(payload)}


    // METHODS

    makeForms() {
        this.forms = this.forms
        // this.forms = Array.isArray(this.$refs.fields)
        //     ?  this.$refs.fields.map((i: HTMLFormElement) => i.value)
        //     : [this.$refs.fields.value]
    }

    save() {
        if (this.question.save) {
            this.saveAnswers()
        }
    }

    // checkPointSelected() {
    //   if(!this.forms['pvz_id']) {
    //     eventBus.$emit('openAlert','Необходимо выбрать отделение')
    //     return false
    //   }
    // }

    nextQuestion() {
      if(!this.forms['pvz_id']) {
        eventBus.$emit('openAlert','Необходимо выбрать отделение')
        return false
      }
        this.makeForms()
        const currentOption: IQuestionOption | undefined = this.question.options.find((i: IQuestionOption) => i.next_question !== null)
        if(currentOption && currentOption.next_question) {
            this.swapQuestions(currentOption.next_question)
        }
        this.save();
        this.queue++
    }

    created() {

        eventBus.$on('boxberry',(payload: any) => {
            this.forms['boxberryPoint'] = payload.id
            this.forms['pvz_id'] = payload.id
            this.forms['pvz_address'] = payload.address
            this.forms['pvz_name'] = payload.name
            this.forms['pvz_price'] = payload.price
        })
    }
}

Vue.component('boxberry', {
    template: `
        <div id="boxberry_map" style="width: 100%">{{ this.frame }}</div>
    `,

    props: ['city'],

    data: function () {
        return {
            frame: "",
        }
    },


    methods: {
        // @ts-ignore
        callback_function(result) {

            // @ts-ignore
            document.getElementById('pvz_city').innerHTML = 'Город: ' + result.name
            // @ts-ignore
            document.getElementById('pvz_js-pricedelivery').innerHTML = 'Цена: ' + result.price
            // @ts-ignore
            document.getElementById('pvz_code').innerHTML = 'Код ПВЗ: ' + result.id
            // @ts-ignore
            document.getElementById('delivery_point_id').innerHTML = result.id
            //result.name = encodeURIComponent(result.name) // Что бы избежать проблемы с кириллическими символами, на страницах отличными от UTF8, вы можете использовать функцию encodeURIComponent()
            // @ts-ignore
            document.getElementById('pvz_name').innerHTML = 'Название: ' + result.name
            // @ts-ignore
            document.getElementById('pvz_address').innerHTML = 'Адрес: ' + result.address
            // @ts-ignore
            document.getElementById('delivery_adress').innerHTML = result.address
            // @ts-ignore
            document.getElementById('pvz_workschedule').innerHTML = 'Режим работы: ' + result.workschedule
            // @ts-ignore
            document.getElementById('pvz_phone').innerHTML = 'Телефон: ' + result.phone

            if (result.prepaid == '1') {
                alert('Отделение работает только по предоплате!')
            }
            eventBus.$emit('boxberry', result)
        }

    },

    mounted() {
        // @ts-ignore
        boxberry.displaySettings({top: 10})
        // @ts-ignore
        boxberry.openOnPage('boxberry_map')
        // @ts-ignore
        boxberry.open(this.callback_function, "1$NOI0m7gUHsQhre5X7Wgl0NAMRA_KmI1S", this.$props.city, '', 574, 5, 0, 200, 200, 200)
    }

})
</script>
