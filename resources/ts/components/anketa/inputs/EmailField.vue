<template>
    <div class="input-wrap input-wrap_mail">
        <input type="text"
               :id="option.slug"
               :placeholder="option.placeholder"
               v-model="forms[option.slug]"
        >
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

export default class EmailField extends Vue {

    @Prop() option!: IQuestionOption
    @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    get forms() {return this.$store.state.StoreAnketa.forms}
    set forms(payload) {this.setAnswer({options: [], own: '', forms: payload})}
}
</script>
