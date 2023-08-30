<template>

        <input type="date"
               :id="option.slug"
               :placeholder="option.placeholder"
               @focusout="makeForms()"
               v-model="forms[option.slug]"

        >

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

export default class DateField extends Vue {

    @Prop() option!: IQuestionOption
    @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    get forms() {return this.$store.state.StoreAnketa.forms}
    set forms(payload) {this.setAnswer({options: [], own: '', forms: payload})}
    async makeForms() {this.forms = this.forms}

}
</script>
