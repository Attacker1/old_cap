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

      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>

    </div>

    <div class="answers__footer">
      <button class="next-question" @click="nextQuestionSave()">Дальше</button>
    </div>

  </div>
</template>

<script lang="ts">
import {Component, Vue, Mixins} from 'vue-property-decorator'
import questionMixin from "../../../mixins/anketa/questionMixin";

import {IQuestionOption} from "../../../types/anketa";
import {namespace} from "vuex-class";
import {eventBus} from "../../../bus";
import Alert from "../common/Alert.vue";
// import Text from "@/components/inputs/Text.vue";
import TextField from "../inputs/TextField.vue";
import NumberField from "../inputs/NumberField.vue";
import DateField from "../inputs/DateField.vue";
import TextareaField from "../inputs/TextareaField.vue";
import EmailField from "../inputs/EmailField.vue";
import PhoneField from "../inputs/PhoneField.vue";
import DadataField from "../inputs/DadataField.vue";
import TextUserField from "../inputs/TextUserField.vue";
import DropDatePicker from "../inputs/DropDatePicker.vue";

const StoreAnketa = namespace('StoreAnketa')
@Component({
  components: {
    DropDatePicker,
    TextUserField,
    DadataField, PhoneField, EmailField, TextareaField, DateField, NumberField, TextField, Alert
  }
})

export default class CustomForm extends Mixins(questionMixin) {

  @StoreAnketa.Mutation('setDadataQueryResultData') setDadataQueryResultData!: (payload: any) => void

  get dadataQueryResultData() {
    return this.$store.state.StoreAnketa.dadataQueryResultData
  }

  set dadataQueryResultData(payload: any) {
    this.setDadataQueryResultData(payload)
  }

  @StoreAnketa.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void

  @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void

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
    if (this.forms.hasOwnProperty(slug) && this.forms[slug].length > 0) {
      return true
    } else {
      eventBus.$emit('openAlert', alertText)
      return false
    }
  }

  async checkForms() {

    let result = this.question.options.some((i: IQuestionOption) => {

      if (i.required) {

        if (i.type === 'email') {
          return !this.validateEmail(i.slug, i.error_text)
        } else if (i.type === 'phone') {
          return !this.validatePhone(i.slug, i.error_text)
        } else if (i.type === 'text') {
          return !this.validateRequired(i.slug, i.error_text)
        } else if (i.type === 'textarea') {
          return !this.validateRequired(i.slug, i.error_text)
        } else if (i.type === 'number') {
          return !this.validateRequiredNumber(i.slug, i.error_text)
        } else if (i.type === 'date') {
          return !this.validateRequired(i.slug, i.error_text)
        } else if (i.type === 'dadata') {
          // return this.validateDadataAddress(i.slug, i.error_text)
        }
      } else {
        return false
      }

    })


    return !result;
  }

  async nextQuestionSave() {

    if (!await this.checkForms()) {
      return false
    }

    if(!this.isAnswerEmpty()){
     await this.makeForms()
    }
    await this.save()

  }

 async mounted(){
    eventBus.$on('isHouse', (payload: boolean) => this.is_house = payload)
  }

}
</script>


