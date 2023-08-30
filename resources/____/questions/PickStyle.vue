<template>

    <div class="lifestyle lifestyle_five lifestyle_five__second">

        <div class="answers">
            <div class="answers__header"></div>
            <div class="answers__body answers__body_flex">

                <div class="image" :style="{'background-image': `url(${server}/storage/${question.style_image})`}">
                    <template v-for="(option, key) in question.options" v-if="option.type === 'likeAll'">
                        <label :for="option.id" :key="option.id" v-html="option.text" :class="checkboxOn(option.id)"
                               @click="likeAll()">
                            <input type="checkbox" :rel="option.type" :id="option.id" v-model="options"
                                   :value="option.id">
                        </label>

                    </template>

                    <template v-for="(option, key) in question.options" v-if="option.type === 'checkbox'">

                        <label :for="option.id" :key="option.id" :rel="option.option_key" :class="checkboxOn(option.id)"
                               :style="{'top': option.position_top + '%', 'left' : option.position_left + '%'}">
                            <input :type="option.type" :id="option.id" v-model="options" :value="option.id"
                                   @change="customChoise()">
                        </label>
                    </template>

                    <template v-for="(option, key) in question.options" v-if="option.type === 'unlikeAll'">
                        <label :for="option.id" :key="option.id" v-html="option.text" :class="checkboxOn(option.id)"
                               @click="options = [option.id]">
                            <input type="checkbox" :rel="option.type" :id="option.id" v-model="options"
                                   :value="option.id">
                        </label>
                    </template>

                </div>
            </div>
            <div class="answers__footer">
                <button class="next-question" @click="setQueue(queue+1)">Дальше</button>
            </div>
        </div>


    </div>

</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion, IQuestionOption} from "@/types/anketa";
import {namespace} from "vuex-class";

const urii = process.env.NODE_ENV === 'production' ? '' : 'http://localhost:8000'
const SQuestion = namespace('SQuestion')
@Component({})

export default class PickStyle extends Vue {

    $refs!: {
        hearts: HTMLFormElement
    }


    // => VARS
    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }

    @SQuestion.State('server') server!: string

    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void

    @SQuestion.Mutation('setQueue') setQueue !: (payload: number) => void

    get queue() {
        return this.$store.state.SQuestion.queue
    }

    set queue(payload: number) {
        this.setQueue(payload)
    }

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

    // => METHODS

    checkboxOn(id: number) {
        return this.options.indexOf(id) !== -1 ? 'on' : ''
    }

    likeAll() {
        this.options = []
        console.log('jjjjj');
        this.question.options.forEach((i: IQuestionOption) => {
            if (i.type !== 'unlikeAll') {
                return this.options.push(i.id)
            }
        })
        this.forms = this.forms
    }

    customChoise() {
        let unlikeAll: IQuestionOption | undefined = this.question.options.find(i => i.type === 'unlikeAll')


        if (unlikeAll !== undefined && this.options.indexOf(unlikeAll.id) !== -1) {
            this.options = this.options.reduce((acc: Number[], i: Number) => {
                if (unlikeAll !== undefined && i !== unlikeAll.id) {
                    acc.push(i)
                }
                return acc
            }, [])

        }

        let likeAllId: Number = 0;
        let likeAll = this.question.options.find(i => i.type === 'likeAll')
        if (likeAll && this.options.indexOf(likeAll.id) !== -1) {
            let index = this.options.indexOf(likeAll.id)
            this.options.splice(index, 1)
        }

        if (likeAll) {
            likeAllId = likeAll.id
        }

        let a: Number[] = this.question.options.reduce((acc: Number[], i: IQuestionOption) => {
            if (i.type === 'checkbox') {
                acc.push(i.id)
            }
            return acc;
        }, [])

        if (this.diff(a, this.options).length === 0) {
            this.options.push(likeAllId)
        }

    }

    diff(a: Number[], b: Number[]) {
        return a.filter(i => !b.includes(i)).concat(b.filter(i => !a.includes(i)))
    }

    created() {
        this.setSlug(this.question.slug)
    }
}
</script>
