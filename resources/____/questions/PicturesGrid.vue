<template>
    <div class="answers" ref="answers">
        <div class="answers__header"></div>

        <div ref="scrollBlock" class="answers__body answers__body_grid scroll-block ">
            <div class="thumb-grid" :class="answerClass()">


                <label  class="label-thumb"  v-for="(option, key) in question.options" :class="{on : checkedClass(option.id)}" :key="key">
                    <p v-if="option.text_thumb" class="thumb-another_text" v-html="option.text"></p>
                    <template v-else>
                        <span v-if="thumbs" v-html="option.text"></span>
                        <template v-else>{{option.text}}</template>
                    </template>

                    <div v-if="option.text_thumb" class="thumb-variation"></div>
                    <div v-else :class="thumbs ? 'thumb-variation' : 'thumb'" :style="{'background-image': `url(${server}/storage/${option.image})`}"></div>

                    <input v-if="question.multiple" type="checkbox" v-model="options" :value="option.id" >
                    <input v-else type="radio" v-model="options" :value="option.id">

                </label>
            </div>
        </div>

        <div class="scroll" ref="scroll">
            <div class="scroll-bar" ref="scrollBar">
                <div class="scroll-thumb" ref="scrollThumb"></div>
            </div>
        </div>

        <div class="answers__footer">
            <button class="next-question"  @click="queue++">Дальше</button>
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

export default class PicturesGrid extends Vue {

    $refs!: {
        answers: HTMLFormElement,
    }

    // => VARS
    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }
    @SQuestion.State('server') server!: string
    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void


    @SQuestion.Mutation('setQueue') setQueue !: (payload: number) => void
    get queue() {return this.$store.state.SQuestion.queue}
    set queue(payload: number) {this.setQueue(payload)}

    // => COMPUTED
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

    // private images: number = 0
    private thumbs: number = 0
    // private classes: any[] = []

    // => METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options.indexOf(id) !== -1
            : this.options === id
    }

    answerClass(){
        let images:number = 0
        let thumbs: number = 0
        let classes = []
        this.question.options.map((i:IQuestionOption) => {
            if(i.text_thumb === 1){
                thumbs++
            } else  {
                images++
            }
        })


        if(thumbs) {classes.push('thumb-grid_under')}

        if(images <= 3) {classes.push('thumb-grid_three')}
        if(images === 5) {classes.push('thumb-grid_five')}
        if(images > 5) {classes.push('thumb-grid_max thumb-grid_max_four ')}
        this.thumbs = thumbs
        //thumb-grid_negative

        return classes.join(' ')

    }

    created() {
        this.setSlug(this.question.slug)
    }

    mounted(){
        // @ts-ignore
        this.$scrollBlocks(this.$refs.answers)
    }
}
</script>


