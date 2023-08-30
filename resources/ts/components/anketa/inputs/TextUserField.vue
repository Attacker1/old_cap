<template>
    <div class="input-wrap input-wrap_user">
        <input type="text"
               :id="option.slug"
               :placeholder="option.placeholder"
               v-model="forms[option.slug]"
        >
<!--        <div class="input-wrap__message" v-html='"dasdasdas"'></div>-->
<!-- todo:uretral вопрос 0 реакция на ошибку ввода -->
    </div>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestionOption} from "../../../types/anketa";
import {namespace} from "vuex-class";
import {Prop} from "vue-property-decorator";

const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class TextUserField extends Vue {

    @Prop() option!: IQuestionOption
    @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void

    get forms() {
        return this.$store.state.StoreAnketa.forms
    }

    set forms(payload) {
        this.setAnswer({options: [], own: '', forms: payload})
    }

}
</script>
