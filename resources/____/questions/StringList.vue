<template>
    <div class="answers">

        <div class="answers__header"></div>

        <div class="answers__body answers__body_radio">

            <label v-for="(option,index) in question.options" class="label__radio"
                   :class="{on : checkedClass(option.id)}" :key="index" v-if="option.type === 'checkbox'">

                <template v-if="question.multiple">
                    <input type="checkbox" v-model="options" :value="option.id" @change="selectUnselect(option.id)">
                </template>
                <template v-else>
                    <input type="radio" v-model="options" :value="option.id" @change="selectUnselect(option.id)">
                </template>

                <span v-html="option.text"></span>
            </label>


            <label v-for="(option,index) in question.options" v-if="option.type !== 'checkbox'"
                   :class="{'on': options.indexOf(option.id) !== -1}">
                <span>{{ option.text }}</span>
                <input type="checkbox" :rel="option.type" v-model="options" :value="option.id"
                       @change="selectUnselect(option.id)">
            </label>

            <div class="answers__body_radio">
                <textarea v-if="question.own_answer" v-model="own" :placeholder="question.own_placeholder"
                          class="text-checkbox"></textarea>
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
import {IAnswer, IQuestion, IQuestionOption} from "@/types/anketa";
import {namespace} from "vuex-class";

const SQuestion = namespace('SQuestion')
@Component({})

export default class StringList extends Vue {

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

    public classes: any = '';

    // METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options && this.options.indexOf(id) !== -1
            : this.options === id
    }

    changeClass() {
        let k = this.question.options.findIndex((i: IQuestionOption) => i.type === 'selectAll' || i.type === 'unselectAll')
        return k !== -1 ? 'answers-container_last' : 'answers-container_small'
    }


    selectUnselect(id: Number) {
        let select = this.question.options.find((i: IQuestionOption) => i.type === 'selectAll')
        let unselect = this.question.options.find((i: IQuestionOption) => i.type === 'unselectAll')

        if (select !== undefined && id === select.id) { // save all exept unselectAll

            this.options = []
            this.question.options.map((i: IQuestionOption) => {
                if (i.type !== 'unselectAll') {
                    this.options.push(i.id)
                }
            })

        } else if (unselect !== undefined && id === unselect.id) { // save only unselectAll

            this.options = []
            this.options.push(id)

        } else { // save all exept unselectAll and selectId

            if (select && this.options.indexOf(select.id) !== -1) {
                this.options.splice(this.options.indexOf(select?.id), 1)
            }
            if (unselect && this.options.indexOf(unselect.id) !== -1) {
                this.options.splice(this.options.indexOf(unselect?.id), 1)
            }

        }
    }


    created() {
        this.setSlug(this.question.slug)
    }

}
</script>


