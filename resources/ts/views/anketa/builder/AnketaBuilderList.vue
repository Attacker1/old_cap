<template>
  <MainFrame :title="$route.meta.title">
<!--  v-slot:headerLeft  -->
    <template v-slot:headerLeft>
      <ButtonGroup size="s">
        <Button class="icon-search">Фильтр</Button>
        <RouteButton link="admin.anketas.questions"/>
        <Button class="icon-plus" @click="modalAdd()">Новая анкета</Button>
      </ButtonGroup>
    </template>

<!-- content -->
    <Table :datas="grid" stripe>

      <TableItem title="ID" prop="id" :width="50"></TableItem>
      <TableItem title="Активная?" align="center">
        <template v-slot="{data}">
          <HSwitch v-model="data.is_current" small @change="makeDefault(data.id)"
                   :disabled="data.is_current === 1" :class="{readonly: data.is_current === 1}"/>
        </template>
      </TableItem>

  <!--  Название анкеты    -->
      <TableItem title="Название анкеты" prop="title"></TableItem>

  <!--Публичная ссылка на анкету-->
      <TableItem title="Публичная ссылка на анкету">
        <template v-slot="{data}">
          <a v-cloak target="_blank" :href="`${frontendLink}${data.slug}`" class="linked">{{ data.slug }}</a>

        </template>
      </TableItem>

  <!-- Собрать -->
      <TableItem title="Собрать анкету" align="center">
        <template v-slot="{data}">
          <ButtonLink color="yellow" title="Собрать" link="admin.anketas.build" :params="{slug: data.slug}"/>
        </template>
      </TableItem>

  <!-- Просмотр -->
      <TableItem title="Просмотр анкеты" align="center">
        <template v-slot="{data}">
          <ButtonLink color="primary" title="Просмотр" link="admin.anketas.frontend" :params="{slug: data.slug}"/>
        </template>
      </TableItem>

  <!--Действия-->
      <TableItem title="Действия" :width="100" align="center">
        <template v-slot="{data}">
          <Button icon="h-icon-edit" :text="true" size="xs" @click="modalEdit(data)"></Button>
          <Poptip content="Вы уверены? ;)" @confirm="deleteAndFetch(data.id)">
            <Button icon="h-icon-trash" :text="true" size="xs"></Button>
          </Poptip>
        </template>
      </TableItem>

      <div slot="empty">Данные отсутствуют</div>

    </Table>

  <!--CRUD-->
    <Modal :type="`drawer-${modal.drawer}`" v-model="modal.open">

      <div class="h-panel" style="width: 400px;">
        <div class="h-panel-bar">

          <span class="h-panel-title" v-text="modal.case === 'edit' ? 'Редактирование анкеты' : 'Добавление анкеты'"/>
          <span class="h-panel-right"><a @click="modal.open = false">Закрыть</a></span>
        </div>
        <div class="h-panel-body">
          <Form
              ref="editForm"
              :validOnChange="true"
              :showErrorTip="true"
              labelPosition="left"
              :labelWidth="130"
              :rules="validationRules"
              :model="item"
          >

            <FormItem label="Название" prop="title">
              <input type="text" v-model="item.title">
            </FormItem>

            <FormItem label="Слаг" prop="slug">
              <input type="text" v-model="item.slug">
            </FormItem>

            <FormItem v-if="modal.case === 'edit'">
              <Button color="primary" @click="edit()">Обновить</Button>
            </FormItem>
            <FormItem v-if="modal.case === 'add'">
              <Button color="primary" @click="add()">Добавить</Button>
            </FormItem>

          </Form>
        </div>
      </div>


    </Modal>


  </MainFrame>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import MainFrame from "../../../components/layout/MainFrame.vue";

const StoreAnketaBuilder = namespace('StoreAnketaBuilder')
import {namespace} from "vuex-class";
import {IAnketaBuilder} from "../../../types/anketa";
import ButtonAdd from "../../../components/layout/buttons/ButtonAdd.vue";
import ButtonLink from "../../../components/layout/buttons/ButtonLink.vue";
import ButtonFilter from "../../../components/layout/buttons/ButtonFilter.vue";
import ButtonToList from "../../../components/layout/buttons/ButtonToList.vue";
import RouteButton from "../../../components/layout/buttons/RouteButton.vue";


@Component({components: {RouteButton, ButtonFilter, ButtonLink, ButtonAdd, MainFrame, ButtonToList}})
export default class AnketaBuilderList extends Vue {

  $Notice: any
  $refs!: {
    editForm: HTMLFormElement,
    addForm: HTMLFormElement,
  }

  public frontendLink: string = ''

  @StoreAnketaBuilder.State('grid') grid!: IAnketaBuilder[]
  @StoreAnketaBuilder.Action('fetchGrid') fetchGrid!: () => void
  @StoreAnketaBuilder.Action('updateAndFetch') updateAndFetch!: (payload: IAnketaBuilder) => void
  @StoreAnketaBuilder.Action('makeDefault') makeDefault!: (payload: number) => void
  @StoreAnketaBuilder.Action('deleteAndFetch') deleteAndFetch!: (payload: number) => void
  @StoreAnketaBuilder.Action('createAndFetch') createAndFetch!: (payload: IAnketaBuilder) => void

  @StoreAnketaBuilder.State('tft') tft!: string
  @StoreAnketaBuilder.Mutation('setTft') setTft!: (str: string) => void

  public modal = {
    open: false,
    drawer: '',
    case: ''
  }

  tftCh(str: string) {
    this.setTft(str)
  }

// editing
  public item = {
    title: '',
    slug: '',
    is_current: 0
  }
  public itemClone!: IAnketaBuilder

  validationRules = {
    required: ['title', 'slug']
  }


  defineItem(data: IAnketaBuilder){
    Object.keys(this.item).map((i) => {this.item[i] = data[i]})
  }

  modalAdd() {
    this.defineItem(this.itemClone)
    this.modal.drawer = 'right'
    this.modal.case = 'add'
    this.modal.open = true
  }

  public isValid(){
    return this.$refs.editForm.valid().result
  }

 async add() {
    if(!this.isValid){
      return false
    }
    try {
      await this.createAndFetch(this.item)
      this.$Notice['success'](`Успешно Добавлено`);
    } catch (e) {
      this.$Notice['success'](`Что то пошло не так`)
      console.log(e);
    }
    this.modal.open = false
  }




  modalEdit(data: IAnketaBuilder) {
    this.defineItem(data)
    this.item['id'] = data['id']

    this.modal.drawer = 'left'
    this.modal.case = 'edit'
    this.modal.open = true
  }


  async edit() {
    if(!this.isValid){
      return false
    }
    try {
      await this.updateAndFetch(this.item)
      this.$Notice['success'](`Успешно обновлено`);
    } catch (e) {
      this.$Notice['success'](`Что то пошло не так`)
      console.log(e);
    }
    this.modal.open = false
  }


  async created() {
    await this.fetchGrid()
    this.itemClone = {...this.item}
    console.log(process.env.MIX_ANKETA_FRONTEND);
    if(process.env.MIX_ANKETA_FRONTEND) {

      this.frontendLink = process.env.MIX_ANKETA_FRONTEND
    }


  }
};
</script>
