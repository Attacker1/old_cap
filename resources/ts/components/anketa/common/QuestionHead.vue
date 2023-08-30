<template>
    <div class="question-head">
        <div class="logo"><img src="/assets-vuex/img/logo.svg" alt="logo"></div>
        <label v-html="currentQuestion.question"></label>

        <div class="disclaimer" v-if="currentQuestion.disclaimer">
            <div><p class="font-size-lg" v-html="currentQuestion.disclaimer"></p></div>
        </div>

        <button v-if="queue && currentQuestion.slug !== 'coupon'" class="prev-question" @click="moveBack()"></button>

        <slot></slot>

    </div>
</template>

<script lang="ts">

import {Component, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";
import {IQuestion} from "../../../types/anketa";
import {eventBus} from "../../../bus";

const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class QuestionHead extends Vue {
    // => CURRENT QUESTION
    @StoreAnketa.State('currentQuestion') currentQuestion!: IQuestion
    // => QUEUE
    public paused: boolean = false
    @StoreAnketa.Mutation('swapQuestionsBack') swapQuestionsBack!: (questionId: Number) => void
    @StoreAnketa.Mutation('setQueue') setQueue!: (payload: number) => void
    @StoreAnketa.Mutation('setQueuePaused') setQueuePaused !: (payload: boolean) => void

  @StoreAnketa.State('questions') questions!: IQuestion[]
    get queue() {
        return this.$store.state.StoreAnketa.queue
    }

    set queue(payload: number) {
        this.setQueue(payload)
    }

   async moveBack(){
        if(this.currentQuestion.is_sub) {

           await this.swapQuestionsBack(this.currentQuestion.id)
        }
        this.setQueue(this.queue - 1)
     // let tt = this.questions.map(i => {
     //   return `${i.slug} ${i.is_sub ? '--' : ''}`
     // })
     // console.log('back', tt);

    }
    makeInnerAction() {
        eventBus.$emit('innerQueue')
    }
    created() {
        eventBus.$on('paused',(payload: boolean) => this.paused = payload)
    }

}
</script>
