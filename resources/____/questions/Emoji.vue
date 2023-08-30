<template>
    <div class="answers answers-emoji">
        <div class="answers__header"></div>
        <div class="answers__body answers__body_flex emoji">

            <label v-for="option in question.options"  :class="{on : checkedClass(option.id)}">

                <input v-if="question.multiple" type="checkbox" v-model="options" :value="option.id">
                <input v-else type="radio" v-model="options" :value="option.id">

                <span :rel="option.emoji">{{option.text}}</span>
            </label>

            <textarea v-if="question.own_answer"
                      v-model="own"
                      :placeholder="question.own_placeholder"
                      class="text-checkbox">
        </textarea>

        </div>


        <div class="answers__footer">
            <button class="next-question"  @click="setQueue(queue+1)">Дальше</button>
        </div>


    </div>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion} from "@/types/anketa";
import {namespace} from "vuex-class";
const SQuestion = namespace('SQuestion')
@Component({})

export default class Emoji extends Vue {

    // => VARS
    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }

    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void

    // => COMPUTED


    @SQuestion.Mutation('setQueue') setQueue !: (payload: number) => void
    get queue() {return this.$store.state.SQuestion.queue}
    set queue(payload: number) {this.setQueue(payload)}


    get options () {return this.$store.state.SQuestion.options}
    set options (payload) {this.setAnswer({options: payload, own: this.own, forms: this.forms})}

    get own () {return this.$store.state.SQuestion.own}
    set own (payload) {this.setAnswer({options: this.options, own: payload, forms: this.forms})}

    get forms () {return this.$store.state.SQuestion.forms}
    set forms (payload) {this.setAnswer({options: this.options, own: this.own, forms: payload})}

    // => METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options.indexOf(id) !== -1
            : this.options === id
    }

    created(){
        this.setSlug(this.question.slug)
    }
}
</script>

<style>
.answers__body.emoji span:before {
    content: attr(rel)!important;
    background: none!important;
    font-size: 20px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}

</style>
