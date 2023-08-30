<template>
    <Modal v-model="open" type="drawer-right" :hasCloseIcon="true">
      <div class="h-panel-bar">
        <span class="h-panel-title">Создание вопроса</span>
      </div>
      <div class="h-panel-body">
        <Form :label-width="130" :model="item" ref="form"
              :rules="rules"
              labelPosition="left"
              :showErrorTip="true">
          <FormItem label="Тип вопроса" pror="type">
            <Select :autosize="true" v-model="item.type_id" :datas="questionTypes" keyName="id" titleName="title"/>
          </FormItem>
          <FormItem label="Слаг" prop="slug">
            <input type="text" v-model="item.slug" />
          </FormItem>

          <FormItem>
            <Button color="primary" @click="create()">Создать</Button>
          </FormItem>

        </Form>
      </div>
    </Modal>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import {eventBus} from "../../../bus";
    import {IQuestionType} from "../../../types/anketa";
    const StoreAnketaQuestion = namespace('StoreAnketaQuestion')

    @Component
    export default class NewQuestion extends Vue {

      $Notice: any
      public open = false

      public item = {
        type_id: null,
        slug: null
      }
      public rules = {}

      @StoreAnketaQuestion.State('questionTypes') questionTypes!: IQuestionType[]
      @StoreAnketaQuestion.Action('fetchCreateQuestion') fetchCreateQuestion!: (payload) => void

      initItem(){
        this.item = {
          type_id: null,
          slug: null
        }
      }

      async create(){
        try {
          await this.fetchCreateQuestion(this.item)
          this.$Notice['success'](`Успешно Добавлено`);
        } catch (e) {
          this.$Notice['success'](`Что то пошло не так`)
          console.log(e);
        }
        this.open = false
        this.initItem()

      }



      created() {

        eventBus.$on('buildNewQuestion', () => { this.open = true })

        document.addEventListener('keyup',  (evt) => {
          if (evt.keyCode === 27) {this.open = false}
        });


        // this.fetchQuestionsType()

      }

    }
</script>
