<template>
  <div class="answers">

    <div class="answers__header"></div>

    <div class="answers__body " :class="question.multiple ? 'answers__body_checkbox' : 'answers__body_radio_column answers__body_radio'">

      <label v-for="(option,index) in question.options"
             :class="[{on : checkedClass(option.id)},question.multiple ? 'label__checkbox' : 'label__radio']"
             :key="index" v-if="option.type === 'checkbox'">
        <span v-html="option.text"></span>

        <template v-if="question.multiple">
          <input type="checkbox" v-model="options" :value="option.id" @change="selectUnselect(option.id)">
        </template>
        <template v-else>
          <input type="radio" v-model="options" :value="option.id" @change="selectUnselect(option.id)">
        </template>


      </label>


      <label v-for="(option,index) in question.options" v-if="option.type !== 'checkbox'"
             :class="[{'on': options.indexOf(option.id) !== -1},question.multiple ? 'label__checkbox' : 'label__radio']">
        <span>{{ option.text }}</span>
        <input type="checkbox" :rel="option.type" v-model="options" :value="option.id"
               @change="selectUnselect(option.id)">
      </label>

      <div class="answers__body_radio" v-if="question.own_answer">
                <textarea v-if="question.own_answer" v-model="own" :placeholder="question.own_placeholder"
                          class="text-checkbox"></textarea>
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
import {IQuestionOption} from "../../../types/anketa";

@Component({})

export default class StringList extends Mixins(questionMixin) {


  public classes: any = '';

  // METHODS

  changeClass() {
    let k = this.question.options.findIndex((i: IQuestionOption) => i.type === 'selectAll' || i.type === 'unselectAll')
    return k !== -1 ? 'answers-container_last' : 'answers-container_small'
  }


  selectUnselect(id: Number) {
    let select = this.question.options.find((i: IQuestionOption) => i.type === 'selectAll')
    let unselect = this.question.options.find((i: IQuestionOption) => i.type === 'unselectAll')

    if (select !== undefined && id === select.id) { // save all exept unselectAll

      this.options = []
      this.question.options.map((i: IQuestionOption) => {
        if (i.type !== 'unselectAll') {
          this.options.push(i.id)
        }
      })

    } else if (unselect !== undefined && id === unselect.id) { // save only unselectAll

      this.options = []
      this.options.push(id)

    } else { // save all exept unselectAll and selectId

      if (select && this.options.indexOf(select.id) !== -1) {
        this.options.splice(this.options.indexOf(select?.id), 1)
      }
      if (unselect && this.options.indexOf(unselect.id) !== -1) {
        this.options.splice(this.options.indexOf(unselect?.id), 1)
      }

    }
  }
}
</script>


