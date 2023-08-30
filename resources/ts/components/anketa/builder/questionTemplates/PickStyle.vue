<template>
  <div>

    <table style="width: 100%;" cellspacing="10">

      <thead>
      <tr>
        <th>Ключ (необязательно)</th>
        <th>Позиция сверху в %</th>
        <th>Позиция слева в %</th>
        <th>Описание предмета</th>
        <th>
          <Button color="primary" icon="h-icon-plus" size="s" @click="addCommonOption(grid[itemIndex].id)">Добавить
          </Button>
        </th>
      </tr>
      </thead>

      <tfoot>
      <tr v-for="option in grid[itemIndex].options" v-if="option.type === 'likeAll'">
        <td><b>Нравится</b></td>
        <td colspan="4"><input type="text" v-model="option.text" style="width: 378px"></td>
      </tr>
      <tr v-for="option in grid[itemIndex].options" v-if="option.type === 'unlikeAll'">
        <td><b>He нравится</b></td>
        <td colspan="4"><input type="text" v-model="option.text" style="width: 378px"></td>
      </tr>
      </tfoot>

      <tbody>
      <tr v-for="option in grid[itemIndex].options" v-if="option.type === 'checkbox'">
        <td><input type="text" v-model="option.option_key"></td>
        <td><input type="text" v-model="option.position_top"></td>
        <td><input type="text" v-model="option.position_left"></td>
        <td><input type="text" v-model="option.text"></td>
        <td>
          <Button color="yelow" icon="h-icon-minus" size="s">Удалить ({{option.id}})</Button>
        </td>
      </tr>
      </tbody>

    </table>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import {IQuestion} from "../../../../types/anketa";
import ImageUploader from "../../../layout/uploader/ImageUploader.vue";

const StoreAnketaQuestion = namespace('StoreAnketaQuestion')
@Component({
  components: {ImageUploader}
})
export default class PickStyle extends Vue {

  @Prop() itemIndex
  public itemId
  @StoreAnketaQuestion.State('grid') grid!: IQuestion[]
  @StoreAnketaQuestion.Action('fetchCreateQuestionOption') fetchCreateQuestionOption!: (payload) => void


  addCommonOption(id) {

    let payload = [
      {
        type: 'checkbox',
        position_top: 10,
        position_left: 10,
        question_id: id,
      },
      {
        type: 'likeAll',
        question_id: id,
        text: 'Нравится весь образ',
        option_key: 0
      },
      {
        type: 'unlikeAll',
        question_id: id,
        text: 'Ничего не понравилось'
      }
    ]

    let likeUnlike = this.grid[this.itemIndex].options.find(i => i.type === 'likeAll') === undefined
        ? payload
        : [payload[0]]

    this.fetchCreateQuestionOption(likeUnlike)

  }


  mounted() {
    // this.itemId = this.grid[this.itemIndex].options
  }

}
</script>
