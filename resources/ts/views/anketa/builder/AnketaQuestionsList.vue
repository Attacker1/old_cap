<template>
  <MainFrame :title="$route.meta['title']">

    <template v-slot:headerLeft>
      <div class="h-input-group" v-width="700">

        <Button color="primary" @click="newQuestion()">Новый вопрос</Button>

<!--        <span class="h-input-addon">K-во</span>-->
<!--        <input type="text" v-model="skipQuest" style="text-align: center; width: 50px;">-->
<!--        <span class="h-input-addon">Лимит</span>-->
<!--        <input type="text" v-model="takeQuest" @focusout="changeTakeQuest()" style="text-align: center; width: 50px;">-->
<!--        <Button-->
<!--            :class="isRequesting ? 'bg-red-color white-color': 'bg-primary-color white-color'"-->
<!--            size="s" :icon="isRequesting ? 'h-icon-spinner' : 'h-icon-right'" @click="start()"-->
<!--        />-->
<!--        <span class="h-input-addon">Всего</span>-->
<!--        <input type="text" v-model="count" style="text-align: center; width: 50px;">-->
<!--        <Button color="primary" icon="h-icon-down" @click="saveJson()">json</Button>-->
      </div>
    </template>


    <Table :datas="grid" stripe :class="'fixed-header'" :height="tableHeigght">
      <TableItem title="ID" prop="id" :width="30"/>


      <TableItem title="вкл\выкл" align="center"  :width="70">
        <template v-slot="{data}">
          <HSwitch v-model="data.active" small @change="updateQuestion(data)"
                   :class="{readonly: data.is_current === 1}"/>
        </template>
      </TableItem>

      <TableItem title="slug" :width="300">
        <template v-slot="{data}"><span class="h-tag h-tag-blue small">{{data.slug}}</span></template>
      </TableItem>

      <TableItem title="Вопрос">
        <template v-slot="{data}">
          {{data.question}}
        </template>
      </TableItem>
      <TableItem title="Ключ"  prop="old_key" align="center">
<!--      <template v-slot="{data}">-->
<!--        -->
<!--      </template>-->
      </TableItem>
      <TableItem title="title" prop="type_id" align="center">
<!--      <template v-slot="{data}">-->
<!--        <HSwitch v-model="data.is_current" small @change="makeDefault(data.id)"-->
<!--                 :disabled="data.is_current === 1" :class="{readonly: data.is_current === 1}"/>-->
<!--      </template>-->
      </TableItem>

      <TableItem title="yandex" align="center">
      <template v-slot="{data}">
        <Button v-if="toggeler.ya_track !== data.id" icon="h-icon-edit" size="s" @click="toggeler.ya_track = data.id">{{data.ya_track}}</Button>
       <input v-else type="text" v-model="data.ya_track" @focusout="toggeler.yatrack = null, updateQuestion(data)">
      </template>
      </TableItem>

      <TableItem title="facebool" align="center">
        <template v-slot="{data}">
          <Button v-if="toggeler.fb_track !== data.id" icon="h-icon-edit" size="s" @click="toggeler.fb_track = data.id">{{data.fb_track}}</Button>
          <input v-else type="text" v-model="data.fb_track" @focusout="toggeler.fb_track = null, updateQuestion(data)">
        </template>
      </TableItem>

      <TableItem title="Управление" align="center">
        <template v-slot="{data}">
          <Button color="primary" icon="h-icon-edit" size="s" @click="editQuestion(data.id)"/>
        </template>
      </TableItem>

      <!--      <TableItem title="active" prop="active">-->


    </Table>


    <new-question></new-question>
    <edit-question></edit-question>


  </MainFrame>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import MainFrame from "../../../components/layout/MainFrame.vue";
import {IQuestion} from "../../../types/anketa";
import {eventBus} from "../../../bus";
import NewQuestion from "../../../components/anketa/builder/NewQuestion.vue";
import EditQuestion from "../../../components/anketa/builder/EditQuestion.vue";

const StoreAnketaQuestion = namespace('StoreAnketaQuestion')
@Component({
  components: {EditQuestion, NewQuestion, MainFrame}
})
export default class AnketaQuestionsList extends Vue {
  $Message!: {
    text: string,
    type: string,
    timeout: number
  }

  /*    text: string,
    type: string,
    timeout: number*/

  @StoreAnketaQuestion.State('grid') grid!: IQuestion[]
  @StoreAnketaQuestion.Action('fetchQuestionsList') fetchQuestionsList!: () => void
  @StoreAnketaQuestion.Action('fetchUpdateQuestion') fetchUpdateQuestion!: (payload: IQuestion) => void

  public tableHeigght: Number = 0


  public toggeler = {
    'ya_track': null,
    'fb_track': null
  }



  async updateQuestion(item: IQuestion) {
    await this.fetchUpdateQuestion(item);
    // this.$Message({
    //   type,
    //   text: `This is an ${text} message`
    // });
  }


  getTableHeigght() {
    this.tableHeigght = document.body.scrollHeight - 114;
    console.log('444', this.tableHeigght);
  }

  newQuestion(){
    eventBus.$emit('buildNewQuestion')
  }

  editQuestion(id:number){
    eventBus.$emit('editQuestion', id)
  }



  async created() {
    await this.fetchQuestionsList()
    this.getTableHeigght()


    eventBus.$on('actionSuccess', (content: string) => {
      // @ts-ignore
      this.$Message({type: 'success', text: content});
    })

    eventBus.$on('actionError', (content: string) => {
      // @ts-ignore
      this.$Notice({type: 'error', timeout: 30000, title: 'Ошибка сохнанения 😬', content});
    })

  }

}
</script>

