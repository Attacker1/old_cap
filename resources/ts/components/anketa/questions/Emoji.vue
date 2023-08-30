<template>
  <div class="answers answers-emoji">
    <div class="answers__header"></div>
    <div class="answers__body answers__body_flex emoji">

      <label v-for="option in question.options" :class="{on : checkedClass(option.id)}">

        <input v-if="question.multiple" type="checkbox" v-model="options" :value="option.id">
        <input v-else type="radio" v-model="options" :value="option.id">

        <span class="emoji" :rel="option.emoji">{{ option.text }}</span>
      </label>

      <textarea v-if="question.own_answer"
                v-model="own"
                :placeholder="question.own_placeholder"
                class="text-checkbox">
        </textarea>


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
export default class Emoji extends Mixins(questionMixin) {}
</script>
