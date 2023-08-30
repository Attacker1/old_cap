<template>
    <div class="answers">
        <div class="answers__header"></div>
        <div class="answers__body answers__body_flex">
            <label v-for="(option,index) in question.options" class="checkbox-input"
                   :class="{on : checkedClass(option.id)}">

                <template v-if="question.multiple">
                    <input type="checkbox" v-model="options" :value="option.id">
                </template>
                <template v-else>
                    <input type="radio" v-model="options" :value="option.id">
                </template>

                <span v-html="option.text"></span>
            </label>

            <div class="answers__body_flex" v-if="question.own_answer">
                <textarea  v-model="own" :placeholder="question.own_placeholder"/>
            </div>

        </div>

        <div class="answers__footer">
            <button class="next-question" @click="setQueue(queue+1)">Дальше</button>
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

export default class ColumnList extends Vue {

    // => VARS
    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }

    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void

    @SQuestion.Mutation('setQueue') setQueue !: (payload: number) => void

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

    // METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options && this.options.indexOf(id) !== -1
            : this.options === id
    }

    created() {
        this.setSlug(this.question.slug)
    }

}
</script>


