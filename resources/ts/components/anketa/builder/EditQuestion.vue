<template>
    <Modal v-model="open"  :hasCloseIcon="true" :closeOnMask="false" >
<!--  type="drawer-right"    -->
      <div class="h-panel h-panel-no-border">
        <div class="h-panel-bar">
          <div class="h-panel-title">
            <h4 v-cloak>Редактирование вопроса {{itemId}}</h4>
          </div>
        </div>
        <div class="h-panel-body">
          <Form :label-width="130" :model="grid[itemIndex]" ref="form"
                :rules="rules" labelPosition="left" mode="twocolumn" :showErrorTip="true">
            <FormItem label="Тип вопроса" prop="type" v-if="itemIndex">
              <Select  :autosize="true" v-model="grid[itemIndex].type_id" :datas="questionTypes" keyName="id" titleName="title" @change="updateQuestion()"/>
            </FormItem>
            <FormItem label="Слаг" prop="slug" v-if="itemIndex">
              <input type="text" v-model="grid[itemIndex].slug" @focusout="updateQuestion()"/>
            </FormItem>
            <FormItem label="Текст вопроса" prop="question" :single="true">
              <textarea v-model="grid[itemIndex].question" @focusout="updateQuestion()"></textarea>
            </FormItem>
            <FormItem label="Сортировка" prop="sort">
              <input v-model.number="grid[itemIndex].sort" @focusout="updateQuestion()"/>
            </FormItem>
            <FormItem label="Активность" prop="active">
              <HSwitch v-model="grid[itemIndex].active" class="h-switch-small" @change="updateQuestion()"/>
            </FormItem>
            <FormItem label="Подвопрос?" prop="is_sub">
              <HSwitch v-model="grid[itemIndex].is_sub" class="h-switch-small" @change="updateQuestion()"/>
            </FormItem>
            <FormItem label="Сохранение" prop="save">
              <HSwitch v-model="grid[itemIndex].save" class="h-switch-small" @change="updateQuestion()"/>
            </FormItem>

            <FormItem label="yandex метка" prop="ya_track">
              <input type="text" v-model="grid[itemIndex].ya_track" @focusout="updateQuestion()"/>
            </FormItem>

            <FormItem label="Загрузка фото" prop="style_image" v-if="grid[itemIndex].type_id === 4" >
              <ImageUploader
                  path="/settings/anketa/styles"
                  :filename="'choosingStyle'+grid[itemIndex].id"
                  :picture="grid[itemIndex].style_image ? grid[itemIndex].style_image : null"
              />
            </FormItem>









<!--            <FormItem label="facebook метка" prop="type"></FormItem>-->
<!--            <FormItem label="appeal" prop="type"></FormItem>-->
<!--            <FormItem label="Подсказка инпута" prop="type"></FormItem>-->
            <FormItem label="Ключ старого массива" prop="type">
              <input v-model.number="grid[itemIndex].old_key" @focusout="updateQuestion()"/>
            </FormItem>
<!--            <FormItem label="Дисклеймер" prop="type"></FormItem>-->


            <PickStyle v-if="grid[itemIndex].type_id === 4" :itemIndex="itemIndex"/>


<!--            <FormItem>-->
<!--              <Button color="primary" @click="create()">Создать</Button>-->
<!--            </FormItem>-->

          </Form>
        </div>
      </div>
    </Modal>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import {eventBus} from "../../../bus";
    import {IQuestion, IQuestionType} from "../../../types/anketa";
    import PickStyle from "./questionTemplates/PickStyle.vue";
    import ImageUploader from "../../layout/uploader/ImageUploader.vue";
    const StoreAnketaQuestion = namespace('StoreAnketaQuestion')
    @Component({
      components: {ImageUploader, PickStyle}
    })
    export default class EditQuestion extends Vue {

      public rules = {}
      public open = false

      public itemId: number = 0
      public itemIndex: number = 0

      @StoreAnketaQuestion.State('questionTypes') questionTypes!: IQuestionType[]
      @StoreAnketaQuestion.State('grid') grid!: IQuestion[]
      @StoreAnketaQuestion.Action('fetchUpdateQuestion') fetchUpdateQuestion!: (payload) => void


      updateStyleImage(a) {
        this.grid[this.itemIndex].style_image = a
        this.fetchUpdateQuestion(this.grid[this.itemIndex])
      }

      updateQuestion() {
        this.fetchUpdateQuestion(this.grid[this.itemIndex])
      }


      created() {
        eventBus.$on('editQuestion', (id) => {
          this.itemId = id
          this.itemIndex = this.grid.findIndex(i => i.id === id)
          this.open = true
        })

        document.addEventListener('keyup',  (evt) => {
          if (evt.keyCode === 27) {this.open = false}
        });

        eventBus.$on('imageUploaded', (payload) => {
          this.updateStyleImage(payload)
        })

      }

    }
</script>
