<template>
    <div class="input-wrap input-wrap_user">
        <input type="text"
               :id="option.slug"
               min="0"
               :placeholder="option.placeholder"
               @focusout="makeForms()"
               v-model="forms[option.slug]"
               v-number

        >
        <div class="input-wrap__message" v-if="!(this.option.required && this.forms[this.option.slug])" >
            {{option.error_text}}
        </div>
    </div>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion, IQuestionOption} from "../../../types/anketa";
import {namespace} from "vuex-class";
import {eventBus} from "../../../bus";
import {Prop} from "vue-property-decorator";

const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class NumberField extends Vue {

    @Prop() option!: IQuestionOption
    @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    get forms() {return this.$store.state.StoreAnketa.forms}
    set forms(payload) {this.setAnswer({options: [], own: '', forms: payload})}
    async makeForms() {this.forms = this.forms}

    // validate(){
    //     console.log(!(this.option.required && this.forms[this.option.slug]));
    //    return  !(this.option.required
    //        && this.forms[this.option.slug])
    //     // console.log(this.forms[this.option.slug]);
    //     // return !!(this.forms[this.option.slug] && this.forms[this.option.slug].length)
    // }


}
</script>
