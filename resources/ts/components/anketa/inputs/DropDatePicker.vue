<template>
    <div class="datepicker" v-cloak>
        <div class="datepicker__text">{{option.placeholder}}</div>
        <div class="datepicker__inner">
            <div class="date-dropdowns">
                <select name="day" id="day" v-model="day" @change="setDate()">
                    <option v-for="k in days" :value="keyMaker(k)">{{ k }}</option>
                </select>

                <select name="month" id="month" v-model="month" @change="setDate()">
                    <option v-for="(m,k) in arMonth"
                            :value="keyMaker(k)"
                            v-if="m"
                            :selected="month === k"
                    >{{ m }}</option>
                </select>

                <select name="year" id="year" v-model="year" @change="setDate()">
                    <option v-for="y in arYears" :value="keyMaker(y)"
                            :selected="year === y"
                    >{{ y }} </option>
                </select>

            </div>
        </div>
        <div class="datepicker__message" v-html="message"></div>
    </div>
</template>

<script lang="ts">

import {Component, Mixins, ModelSync, Prop, Vue, Watch} from "vue-property-decorator"
import {namespace} from "vuex-class"
import {eventBus} from "../../../bus";


import {IAnswer, IQuestionOption} from "../../../types/anketa";
import {setup} from "vue-class-component/dist/vue-class-component";
import questionMixin from "../../../mixins/anketa/questionMixin";

const StoreAnketa = namespace('StoreAnketa')
@Component

export default class DropDatePicker extends Mixins(questionMixin) {

    @Prop() option!: IQuestionOption

    // @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    // get forms() {return this.$store.state.StoreAnketa.forms}
    // set forms(payload) {this.setAnswer({options: [], own: '', forms: payload})}
    // async makeForms() {this.forms = this.forms}




    @Prop() yearsRange!: number[]
    @Prop() date!: any
    @Prop() placeholder!: String

    public year!: number
    public month!: number
    public day!: number
    public days!: number

    public arYears: number[] = []
    public arMonth: string[] = ['',
        'Января',
        'Февраля',
        'Марта',
        'Апреля',
        'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря']

    public message: String = ''

    public range = (start: number, end: number) => {
        const length = end - start;
        return Array.from({length}, (_, i) => start + i);
    }

    setMinDate(){
        let date = new Date()
        date.setDate(date.getDate() + 4);
        return  date.toISOString().substr(0,10)
    }

    parceDate(){
        if(this.option.default_value){
            this.forms[this.option.slug] = this.setMinDate()
        } else {
            this.forms[this.option.slug] = new Date().toJSON().slice(0, 10)
        }
        this.convertDate(this.forms[this.option.slug])
    }

    loadDateHistory() {
      if(this.forms[this.option.slug]) {
        this.convertDate(this.forms[this.option.slug])
      }
    }

    keyMaker(k: number) {
        return k.toString().length === 1
            ? ('0' + k).toString()
            : k.toString()
    }

    convertDate(date: any) {
        let arDate = date.split('-')
        this.year = arDate[0]
        this.month = arDate[1]
        this.day = arDate[2]
        this.days = new Date(arDate[0], arDate[1], 0).getDate()
    }

    checkDate(){
        let date1 = new Date()
        let date2 = new Date(this.forms[this.option.slug]);
        let daysLag = Math.ceil((date2.getTime() - date1.getTime() )/ (1000 * 3600 * 24))
        if(daysLag < 4) {
            this.message =  this.option.error_text
            this.parceDate();
        } else {
            this.message = ''
        }
    }

    setDate() {
        this.forms[this.option.slug] = `${this.year}-${this.month}-${this.day}`;
        if(this.option.default_value){
            this.checkDate()
        }
        this.forms = this.forms
        this.$emit('dateChanged',{
            year: this.year,
            month: this.month,
            day: this.day,
            date: `${this.year}-${this.month}-${this.day}`
        })
    }

   async created() {
        this.arYears = this.yearsRange
            ? this.range(this.yearsRange[0], this.yearsRange[1])
            : this.range(1920, 2050)
        this.parceDate()
        this.loadDateHistory()

      // console.log('this.forms',this.forms);
      // console.log('this.option',this.option.slug);
      // console.log('erererer',this.forms[this.option.slug] );
    }

}
</script>

<style>
.datepicker__message {
    margin-top: 10px;
    padding-left: 40px;
}
</style>
