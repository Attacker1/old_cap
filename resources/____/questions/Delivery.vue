<template>
    <div class="answers">

        <div class="answers__header"></div>

        <div class="answers__body answers__body_radio answers__body_radio_column">
            <label v-for="(option,index) in question.options" class="label__radio"
                   :class="{on : option.id === options}" :key="index" v-if="option.type === 'cities'">
                <input type="radio" v-model="options" :value="option.id">
                <span v-html="option.text"></span>
            </label>
        </div>

        <div class="answers__footer">
            <button class="next-question" @click="nextQuestion()">Дальше</button>
        </div>

    </div>
</template>

<script lang="ts">

import {Component, Vue, Watch} from "vue-property-decorator"
import {IAnswer, IQuestion, IDadataAddress, IDadataAddressItem, IBoxberry, IQuestionOption} from "@/types/anketa"
import {namespace} from "vuex-class"
import {eventBus} from "@/bus"

// import boxberry from '@/assets/js/boxberry.js'

const SQuestion = namespace('SQuestion')
@Component({})

export default class Delivery extends Vue {

    // => VARS
    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }

    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void
    @SQuestion.Mutation('setQueue') setQueue !: (payload: number) => void
    get queue() {return this.$store.state.SQuestion.queue}
    set queue(payload: number) {this.setQueue(payload)}

    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    get options() {return this.$store.state.SQuestion.options}
    set options(payload) {this.setAnswer({options: payload, own: this.own, forms: this.forms})}

    @SQuestion.Mutation('setPrewQuestion') setPrewQuestion!: (payload: number) => void
    get prewQuestion() { return this.$store.state.SQuestion.prewQuestion }
    set prewQuestion(payload) { this.setPrewQuestion(payload)}

    get own () {return this.$store.state.SQuestion.own}
    set own (payload) {this.setAnswer({options: this.options, own: payload, forms: this.forms})}

    get forms () {return this.$store.state.SQuestion.forms}
    set forms (payload) {this.setAnswer({options: this.options, own: this.own, forms: payload})}

    @SQuestion.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void

    @SQuestion.Mutation('setBoxberryCity') setBoxberryCity!: (str: String) => void
    get boxberryCity () {return this.$store.state.SQuestion.boxberryCity}
    set boxberryCity (payload) {this.setAnswer({options: this.options, own: this.own, forms: payload})}

    // METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options && this.options.indexOf(id) !== -1
            : this.options === id
    }

    setCity() {
        let city = this.question.options.find((i:IQuestionOption) => i.id === this.options)
        if(city?.emoji) {
            this.setBoxberryCity(city?.emoji)
        }
        console.log('this.options', this.options, city?.emoji);
    }

    nextQuestion() {
        this.setCity()

        if (this.options.length === 0) {
            eventBus.$emit('openAlert', 'Нам необходимо знать в какой город доставить капсулу')
            return false
        }
        const currentOption: IQuestionOption | undefined = this.question.options.find((i: IQuestionOption) => i.id === this.options)
        if(currentOption && currentOption.next_question) {
            this.swapQuestions(currentOption.next_question)
        }
        this.queue++

    }

    created() {
        this.setSlug(this.question.slug)
    }
}


</script>


<!--
43593c726a2211b49aa8f2a1a31ce8d757f53636
-->
