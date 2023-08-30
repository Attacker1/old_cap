<template>
  <MainFrame :title="`Сборка анкеты - вариант: ${$route.params.slug}`">
    <!--  v-slot:headerLeft  -->
    <template v-slot:headerLeft>
      <RouteButton link="admin.anketas" title="Список анкет"/>
    </template>

    <!-- Content -->

    <div class="items-row">
      <div class="items-row-col">

        <draggable class="list-group" :list="anketaList" group="people" @end="updateLists()">
          <div class="list-item" v-for="(element, index) in anketaList" :key="index"
               :class="feedClass(element)"
          >

            <span class="h-tag small">{{ index }}</span>
            <template v-if="element.type_id === 9">
              <div class="opt-nest"
                   @click="open('drawer-right', index)" v-tooltip placement="right"
                   :content="element.question"
              >
                <template v-for="option in element.options"
                          v-if="option.type !== 'nextQuestion'">
                  <div class="opt-nest-str">
                      <span class="h-tag h-icon-fullscreen h-tag-bg-gray small h-tag-first">
                          {{ option.slug }}
                      </span>
                  </div>
                </template>

              </div>
              <div class="opt-nest"> &nbsp; { {{ element.slug }} }</div>
            </template>

            <template v-else>

              <Button icon="h-icon-fullscreen" class=" h-tag-first" size="xs"
                      @click="open('drawer-right', index)"
                      v-tooltip placement="right"
                      :circle="true"
                      :content="element.question"> {{ element.slug }}
              </Button>
            </template>


            <NextQuestion v-if="element.recursive_options.length" :server="server"
                          :question="element"/>


            <a class="edit-link" target="_blank"
               :href="`/admin/settings/anketa/${element.id}/edit#tab-form-2`">
              <i class="h-icon-edit white-color"></i>
            </a>

          </div>
        </draggable>
      </div>

      <div class="items-row-col feed">
        <draggable class="list-group " :list="questionsList" group="people" @end="updateLists()">
          <div class="list-item" v-for="(element, index) in questionsList" :key="index"
               :class="feedClass(element)"
               @dblclick="moveElementToEnd(index)"
          >
            <span class="h-tag h-tag-bg-yellow small">{{ element.old_key }}</span>
            <span class="h-tag small"
                  v-tooltip placement="left"
                  :content="element.question"
            >{{ element.slug }}</span>
          </div>
        </draggable>
      </div>

    </div>


    <Modal v-model="modal" :type="modalDirection">
      <AnketaFrontend/>
    </Modal>

  </MainFrame>
</template>

<script lang="ts">
import {Component, Vue,} from 'vue-property-decorator';
import {namespace} from "vuex-class";
import draggable from "vuedraggable"

const StoreAnketaBuilder = namespace('StoreAnketaBuilder')
const StoreAnketa = namespace('StoreAnketa')

import {IQuestion, IQuestionOption, IUpdateLists} from "../../../types/anketa";
import NextQuestion from "./tpl/NextQuestion.vue";

import AnketaFrontend from "../front/AnketaFrontend.vue";
import MainFrame from "../../../components/layout/MainFrame.vue";
import ButtonToList from "../../../components/layout/buttons/ButtonToList.vue";
import RouteButton from "../../../components/layout/buttons/RouteButton.vue";

@Component({
  components: {
    RouteButton,
    draggable, NextQuestion, AnketaFrontend, MainFrame, ButtonToList
  }
})
export default class AnketaBuilder extends Vue {

  @StoreAnketa.State('server') server!: string
  @StoreAnketaBuilder.Mutation('setAnketaList') setAnketaList!: (payload: IQuestion[]) => void

  get anketaList(): IQuestion[] {
    return this.$store.state.StoreAnketaBuilder.anketaList
  }

  @StoreAnketaBuilder.Mutation('setQuestionsList') setQuestionsList!: (payload: any) => void

  get questionsList(): IQuestion[] {
    return this.$store.state.StoreAnketaBuilder.questionsList
  }

  set questionsList(payload: IQuestion[]) {
    this.setQuestionsList(payload)
  }

  @StoreAnketaBuilder.Action('initBuilderData') initBuilderData!: (payload: string) => void
  @StoreAnketaBuilder.Action('updateBuilderData') updateBuilderData!: (payload: IUpdateLists) => void

  @StoreAnketa.Mutation('setQueue') setQueue!: (payload: Number) => void

  get queue() {
    return this.$store.state.StoreAnketa.queue
  }

  set queue(payload: number) {
    this.setQueue(payload)
  }

  @StoreAnketa.Mutation('setVariantId') setVariantId!: (payload: any) => void
  @StoreAnketaBuilder.Mutation('moveToEnd') moveToEnd!: (payload: number) => void

  get variantId() {
    return this.$store.state.StoreAnketa.variantId
  }

  set variantId(payload: any) {
    this.setVariantId(payload)
  }

  public curentVariantSlug: string = ''

  public modal = false;
  public modalDirection = '';
  public rightOrder: number[] = []


  feedClass(element: IQuestion) {
    if (element.slug === 'coupon')
      return 'bg-yellow-color'
    if (element.is_sub) {
      return 'bg-dark4-color'
    }
    if (element.save) {
      return 'saving'
    }
  }

  moveElementToEnd(index: number) {
    this.moveToEnd(index)
    this.updateLists()
    this.$emit('scrollToEnd')

  }

  open(place: string, order: number) {
    if (place === 'drawer-left') {
      this.setOrder()
    }

    this.queue = order
    this.modal = true
    this.modalDirection = place
  }

  setOrder() {
    this.variantId = this.questionsList.map((i: IQuestion) => i.id)
  }

  updateLists() {
    this.setAnketaList(this.anketaList)
    this.updateBuilderData({
      curentVariantSlug: this.curentVariantSlug,
      anketaList: this.anketaList
    })
  }

  async variator() {
    let arUri = window.location.href.split('/');
    let paramId = Number(arUri.pop())
    let paramVariant = arUri.pop()

    if (paramVariant === 'variant') {
      await this.setVariantId(paramId)
    }
  }

  async created() {
    this.curentVariantSlug = window.location.href.split('/').pop() ?? '';
    await this.initBuilderData(this.curentVariantSlug)
    await this.variator()
  }

  updated() {
    this.$on('scrollToEnd', () => {
      window.scrollTo(0, document.body.scrollHeight);
    })
  }
}
</script>
