<template>
  <div class="answers">
    <div class="answers__header"></div>
    <div class="answers__body" :class="question.multiple ? 'answers__body_checkbox' : 'answers__body_radio'">
      <label v-for="(option,index) in question.options" class="checkbox-input"
             :class="[{on : checkedClass(option.id)},question.multiple ? 'label__checkbox' : 'label__radio']"
      >

        <template v-if="question.multiple">
          <input type="checkbox" v-model="options" :value="option.id">
        </template>
        <template v-else>
          <input type="radio" v-model="options" :value="option.id">
        </template>

        <span v-html="option.text"></span>
      </label>

      <div class="answers__body_flex" v-if="question.own_answer" style="width: 100%; margin-top: 20px;">
        <textarea v-model="own" :placeholder="question.own_placeholder"/>
      </div>


      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>

    </div>


    <div class="answers__footer">
      <button class="next-question" @click="save">Дальше</button>
    </div>

  </div>
</template>

<script lang="ts">
import {Component, Vue, Mixins} from 'vue-property-decorator'
import questionMixin from "../../../mixins/anketa/questionMixin";
@Component({})

export default class ColumnList extends Mixins(questionMixin) {}
</script>


