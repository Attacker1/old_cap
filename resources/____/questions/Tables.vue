<template>

    <div ref="answers" class="answers answers_prints answers-prints">

        <div class="answers__header"></div>

        <div class="answers__body color-grid color-grid_prints color-grid_negative scroll-block">
            <div class="color-grid__table">
                <div class="color-grid__table-column" v-for="(table, key) in question.tables">

                    <div class="color-grid__table-title" v-html="table.title"></div>
                    <label class="color-grid__item" v-for="option in table.options_prints"
                           :class="{'on': options.indexOf(option.id) !== -1}"
                           :style="{'background-image': `url(${server}/storage/${option.image})`}">
                        <input type="checkbox" v-model="options" :value="option.id">
                        <span class="color-grid__item-name">{{ option.text }}</span>
                    </label>

                </div>
            </div>
        </div>

        <div class="scroll">
            <div class="scroll-bar">
                <div class="scroll-thumb"></div>
            </div>
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

export default class Tables extends Vue {
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

    // => METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options.indexOf(id) !== -1
            : this.options === id
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


