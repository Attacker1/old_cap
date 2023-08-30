<template>
    <div class="answers custom-fields">

        <div class="answers__header"></div>
        <div class="answers__body answers__body_flex">
            <template v-for="(option, key) in question.options">
                <TextField v-if="option.type === 'text'" :option="option"/>
                <TextUserField v-if="option.type === 'textUser'" :option="option"/>
                <NumberField v-if="option.type === 'number'" :option="option"/>
                <DateField v-if="option.type === 'date'" :option="option"/>
                <TextareaField v-if="option.type === 'textarea'" :option="option"/>
                <EmailField v-if="option.type === 'email'" :option="option"/>
                <PhoneField v-if="option.type === 'phone'" :option="option"/>
                <DadataField v-if="option.type === 'dadata'" :option="option"/>
                <DropDatePicker v-if="option.type === 'date_picker'" :option="option"/>
            </template>

        </div>

        <div class="answers__footer">
            <button class="next-question" @click="nextQuestion()">Дальше</button>
        </div>

    </div>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion, IQuestionOption} from "@/types/anketa";
import {namespace} from "vuex-class";
import {eventBus} from "@/bus";
import Alert from "@/components/common/Alert.vue";
import Text from "@/components/inputs/Text.vue";
import TextField from "@/components/inputs/TextField.vue";
import NumberField from "@/components/inputs/NumberField.vue";
import DateField from "@/components/inputs/DateField.vue";
import TextareaField from "@/components/inputs/TextareaField.vue";
import EmailField from "@/components/inputs/EmailField.vue";
import PhoneField from "@/components/inputs/PhoneField.vue";
import DadataField from "@/components/inputs/DadataField.vue";
import TextUserField from "@/components/inputs/TextUserField.vue";
import DropDatePicker from "@/components/inputs/DropDatePicker.vue";

const SQuestion = namespace('SQuestion')
@Component({components: {
        DropDatePicker,
        TextUserField,
        DadataField, PhoneField, EmailField, TextareaField, DateField, NumberField, TextField, Alert}})

export default class CustomForm extends Vue {

    // => VARS
    get question(): IQuestion {
        return this.$store.state.SQuestion.currentQuestion
    }
    @SQuestion.Mutation('setSlug') setSlug !: (payload: string) => void
    @SQuestion.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
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

    @SQuestion.Mutation('setDadataQueryResultData') setDadataQueryResultData!: (payload: any) => void

    get dadataQueryResultData() {
        return this.$store.state.SQuestion.dadataQueryResultData
    }

    set dadataQueryResultData(payload: any) {
        this.setDadataQueryResultData(payload)
    }

    @SQuestion.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void

    @SQuestion.Action('saveAnswers') saveAnswers!: () => void

    // METHODS
    checkedClass(id: Number) {
        return this.question.multiple
            ? this.options && this.options.indexOf(id) !== -1
            : this.options === id
    }

    makeForms() {
        this.forms = this.forms
    }

    validateEmail(slug: string, alertText: String) {
        const emailReg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
        if ((this.forms.hasOwnProperty(slug) && this.forms[slug] == "") ? "" : (emailReg.test(this.forms[slug]))) {
            return true
        } else {
            this.forms[slug]
                ? eventBus.$emit('openAlert', 'Проверьте правильность заполнения Email')
                : eventBus.$emit('openAlert', alertText)

            return false
        }
    }

    validatePhone(slug: string, alertText: String) {
        if (this.forms.hasOwnProperty(slug) && this.forms[slug].replace(/\D/g, '').length === 10) {
            return true
        } else {
            this.forms[slug]
                ? eventBus.$emit('openAlert', 'Проверьте правильность заполнения Номера Телефона')
                : eventBus.$emit('openAlert', alertText)
            return false
        }
    }

    validateRequired(slug: string, alertText: String) {
        if (this.forms.hasOwnProperty(slug) && this.forms[slug].length > 0) {
            return true
        } else {
            eventBus.$emit('openAlert', alertText)
            return false
        }
    }

    validateRequiredNumber(slug: string, alertText: String) {
        if (this.forms.hasOwnProperty(slug) && this.forms[slug]) {
            return true
        } else {
            eventBus.$emit('openAlert', alertText)
            return false
        }
    }

    is_house = false;

    validateDadataAddress(slug: string, alertText: String) {
        // console.log('hhhh',slug,alertText);
        // console.log('this.forms[slug]',this.forms[slug]);
        console.log('this.forms.hasOwnProperty(slug)', this.forms.hasOwnProperty(slug));
        if (this.forms.hasOwnProperty(slug) && this.forms[slug].length > 0) {
            // if (this.is_house || this.dadataQueryResultData.data.house) {
            //     return true
            // } else {
            //     eventBus.$emit('openAlert', 'Необходимо указать номер дома, офиса или квартиры')
            //     return false
            // }

            return  true

        } else {
            eventBus.$emit('openAlert', alertText)
            return false
        }
    }

    async checkForms() {

        let result = this.question.options.map((i: IQuestionOption) => {
            if (i.required) {
                if (i.type === 'email') {
                    return this.validateEmail(i.slug, i.error_text)
                } else if (i.type === 'phone') {
                    return this.validatePhone(i.slug, i.error_text)
                } else if (i.type === 'text') {
                    return this.validateRequired(i.slug, i.error_text)
                } else if (i.type === 'textarea') {
                    return this.validateRequired(i.slug, i.error_text)
                } else if (i.type === 'number') {
                    return this.validateRequiredNumber(i.slug, i.error_text)
                } else if (i.type === 'date') {
                    return this.validateRequired(i.slug, i.error_text)
                } else if (i.type === 'dadata') {
                    // return this.validateDadataAddress(i.slug, i.error_text)
                }
            } else {
                return true
            }

        })

        return result.indexOf(false) === -1;
    }

    save() {
        if (this.question.save) {
            this.saveAnswers()
        }
    }

    async nextQuestion() {
        if (!await this.checkForms()) {
            return false
        }
        this.makeForms();
        this.save();


        const currentOption: IQuestionOption | undefined
            = this.question.options.find((i: IQuestionOption) => i.type === 'nextQuestion')
        if (currentOption && currentOption.next_question) {
            this.swapQuestions(currentOption.next_question)
        }
        this.queue++

    }


    created() {
        this.setSlug(this.question.slug)
        eventBus.$on('isHouse', (payload: boolean) => this.is_house = payload)
    }

}
</script>


