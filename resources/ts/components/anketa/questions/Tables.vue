<template>

  <div ref="answers" class="answers answers_prints answers-prints">

    <div class="answers__header"></div>

    <div class="answers__body color-grid color-grid_prints color-grid_negative scroll-block">
      <div class="color-grid__table">
        <div class="color-grid__table-column" v-for="(table, key) in question.tables">

          <div class="color-grid__table-title" v-html="table.title"></div>
          <label class="color-grid__item" v-for="option in table.options_prints"
                 :class="{'on': options.indexOf(option.id) !== -1}"
                 :style="{'background-image': `url(/storage/${option.image})`}">
            <input type="checkbox" v-model="options" :value="option.id">
            <span class="color-grid__item-name">{{ option.text }}</span>
          </label>

        </div>
      </div>

      <div class='disclaimer' v-if="question.disclaimer">
        <div v-html="question.disclaimer"></div>
      </div>

    </div>

    <div class="scroll">
      <div class="scroll-bar">
        <div class="scroll-thumb"></div>
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


import {IAnswer, IQuestion} from "../../../types/anketa";
import {namespace} from "vuex-class";

const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class Tables extends Mixins(questionMixin) {
  $refs!: {
    answers: HTMLFormElement,
  }

 async mounted() {
    // @ts-ignore
    this.$scrollBlocks(this.$refs.answers)
  }
}
</script>


