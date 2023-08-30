<template>
    <div class="answers">

        <template v-if="step === 'CHOISE'">

            <label v-for="(option,index) in question.options" :class="{on : checkedClass(option.id)}" :key="index">

                <template v-if="question.multiple">
                    <input type="checkbox" v-model="options" :value="option.id" >
                </template>
                <template v-else>
                    <input type="radio" v-model="options" :value="option.id">
                </template>

                <span v-html="option.text"></span>
            </label>

            <button class="next-question"  @click="checkPhotos()">Дальше</button>

        </template>

        <template v-if="step === 'PHOTO'">

            <div class="images-upload">
                <div class="images-upload-nest" ref="img_1"
                     :style="img_1 ? {'background': `url(${img_1}) no-repeat center`, 'background-size': 'cover'} : ''">
                    <input type="file" accept="image/*" id="img_1" @change="encodeImageFileAsURL">
                </div>
                <div class="images-upload-nest" ref="img_2"
                     :style="img_2 ? {'background': `url(${img_2}) no-repeat center`, 'background-size': 'cover'}: ''">
                    <input type="file" accept="image/*" id="img_2" @change="encodeImageFileAsURL">
                </div>
                <div  class="images-upload-nest" ref="img_3"
                      :style="img_3 ? {'background': `url(${img_3}) no-repeat center`, 'background-size': 'cover'} : ''">
                    <input type="file" accept="image/*" id="img_3" @change="encodeImageFileAsURL">
                </div>
            </div>

            <button class="next-question"  @click="saveClientPhotos()">Дальше</button>

        </template>

    </div>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion, IQuestionOption} from "@/types/anketa";
import {namespace} from "vuex-class";
import {eventBus} from "@/bus";
const SQuestion = namespace('SQuestion')
@Component({})

export default class ClientPhotos extends Vue {

    public step = 'CHOISE'

    // => VARS
    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }

    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void

    @SQuestion.Mutation('setQueue') setQueue !: (payload: number) => void
    get queue() {return this.$store.state.SQuestion.queue}
    set queue(payload: number) {this.setQueue(payload)}

    get options () {return this.$store.state.SQuestion.options}
    set options (payload) {this.setAnswer({options: payload, own: this.own, forms: this.forms})}

    get own () {return this.$store.state.SQuestion.own}
    set own (payload) {this.setAnswer({options: this.options, own: payload, forms: this.forms})}

    get forms () {return this.$store.state.SQuestion.forms}
    set forms (payload) {this.setAnswer({options: this.options, own: this.own, forms: payload})}

    checkPhotos(){
        let pointer: IQuestionOption | undefined = this.question.options.find((i:IQuestionOption) => i.id === this.options)
        if(pointer !== undefined && pointer.placeholder === "make-photo") {
            this.step = 'PHOTO'
            eventBus.$emit('paused', true)
        } else  {
            this.queue++
        }

    }

    // METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options && this.options.indexOf(id) !== -1
            : this.options === id
    }

    @SQuestion.Mutation('setClientImage_1') setClientImage_1!: (payload:any) => void
    get img_1() {return this.$store.state.SQuestion.img_1 }
    set img_1(payload:any) {this.setClientImage_1(payload)}
    @SQuestion.Mutation('setClientImage_2') setClientImage_2!: (payload:any) => void
    get img_2() {return this.$store.state.SQuestion.img_2 }
    set img_2(payload:any) {this.setClientImage_2(payload)}
    @SQuestion.Mutation('setClientImage_3') setClientImage_3!: (payload:any) => void
    get img_3() {return this.$store.state.SQuestion.img_3 }
    set img_3(payload:any) {this.setClientImage_3(payload)}

    @SQuestion.Action('saveClientPhotos') saveClientPhotos!: () =>void

    $refs!: {
        img_1: HTMLFormElement,
        img_2: HTMLFormElement,
        img_3: HTMLFormElement,
    }

    encodeImageFileAsURL(element:any) {
        let id = element.target.getAttribute('id')
        let file = element.target.files[0];
        let reader = new FileReader();
        reader.onloadend =  () => {
            if(id === 'img_1') {
                this.img_1 =  reader.result
                this.$refs.img_1.classList.add('cover')
                this.$refs.img_1.style.cssText = `background: url(${this.img_1}) no-repeat center;`
            }
            if(id === 'img_2') {
                this.img_2 =  reader.result
                this.$refs.img_2.classList.add('cover')
                this.$refs.img_2.style.cssText = `background: url(${this.img_2}) no-repeat center;`
            }
            if(id === 'img_3') {
                this.img_3 =  reader.result
                this.$refs.img_3.classList.add('cover')
                this.$refs.img_3.style.cssText = `background: url(${this.img_3}) no-repeat center;`
            }
        }
        reader.readAsDataURL(file);
    }

    created(){
        this.setSlug(this.question.slug)

        eventBus.$on('innerQueue',() => {
            this.step = 'CHOISE'
            eventBus.$emit('paused', false)
        })
    }

}
</script>


