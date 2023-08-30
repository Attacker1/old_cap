<template>
  <MainFrame>
    <template v-slot:headerLeft>
      <div class="h-input-group" v-width="700">
        <span class="h-input-addon">K-во</span>
        <input type="text" v-model="skipQuest" style="text-align: center; width: 50px;">
        <span class="h-input-addon">Лимит</span>
        <input type="text" v-model="takeQuest" @focusout="changeTakeQuest()" style="text-align: center; width: 50px;">
        <Button
            :class="isRequesting ? 'bg-red-color white-color': 'bg-primary-color white-color'"
            size="s" :icon="isRequesting ? 'h-icon-spinner' : 'h-icon-right'" @click="start()"
        />
        <span class="h-input-addon">Всего</span>
        <input type="text" v-model="count" style="text-align: center; width: 50px;">
        <Button color="primary" icon="h-icon-down" @click="saveJson()">json</Button>
      </div>
    </template>

    <template v-slot:headerCenter>
      <!--      <div class="h-input-group" >-->
      <!--        <Button color="primary" icon="h-icon-down" @click="consoleAnswers">log</Button>-->
      <!--        <span class="h-input-addon">зациклить</span>-->
      <!--        <HSwitch v-model="cicle"/>-->
      <!--      </div>-->

      <div class="h-input-group">
        <Button color="primary" icon="h-icon-refresh" @click="parse()">Парсить</Button>
        <Button
            :class="isRequesting ? 'bg-red-color white-color': 'bg-primary-color white-color'"
            size="s" :icon="isRequesting ? 'h-icon-spinner' : 'h-icon-right'" @click="isRequesting = false"
        />
        <HSwitch v-model="cicle"/>
      </div>

    </template>

    <ConverterTable/>

    <!--    <Loading text="Loading" :loading="loading"></Loading>-->


  </MainFrame>

</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import MainFrame from "../../../components/layout/MainFrame.vue";
import {eventBus} from "../../../bus";
import ConverterTable from "../../../components/anketa/convertor/ConvertorTable.vue";
import axios from "axios";

const StoreAnketaConverter = namespace('StoreAnketaConverter')
@Component({
  components: {ConverterTable, MainFrame}
})
export default class AnketaOldDataConverter extends Vue {

  // public skipQuest: number = 0
  // public takeQuest: number = 100
  // public questData = []
  // public count: number = 0


  @StoreAnketaConverter.State('skipQuest') skipQuest
  @StoreAnketaConverter.Mutation('setSkipQuest') setSkipQuest!: (payload: number) => void
  @StoreAnketaConverter.State('takeQuest') takeQuest
  @StoreAnketaConverter.Mutation('setTakeQuest') setTakeQuest!: (payload: number) => void


  @StoreAnketaConverter.State('questData') questData
  @StoreAnketaConverter.Action('getQuestData') getQuestData!: ({}) => void
  @StoreAnketaConverter.Action('getParse') getParse!: ({}) => void

  @StoreAnketaConverter.State('count') count
  @StoreAnketaConverter.State('questDataVariants') questDataVariants
  @StoreAnketaConverter.Mutation('setInitQuestDataVariants') setInitQuestDataVariants!: (payload) => void
  @StoreAnketaConverter.Mutation('setConvertedAnswers') setConvertedAnswers!: (payload) => void

  get convertedAnswers() {
    return this.$store.state.StoreAnketaConverter.convertedAnswers
  }

  set convertedAnswers(payload) {
    this.setConvertedAnswers(payload)
  }

  // @StoreAnketaConverter.State('convertedAnswers') convertedAnswers

  public loading = false
  public isRequesting = false

  public cicle = false

  start() {

    if (this.isRequesting) {
      this.isRequesting = false
    } else {
      this.isRequesting = true
      this.processing()
    }

  }

  async processing() {

    // this.loading = true

    await this.getQuestData({
      skip: this.skipQuest,
      take: this.takeQuest,
      convertedAnswers: await this.convertedAnswers
    })

    if (this.skipQuest % 1000 == 0) {
      await this.saveJson()
    }
    eventBus.$emit('questionsLoaded', this.questData)
    // this.loading = false
    if (this.isRequesting) {

      if (this.skipQuest >= this.count) {
        this.isRequesting = false
      }
      if (this.cicle) {
        await this.processing()
      }


    }

  }

  // async getQuestData(payload) {
  //   const response = await axios.post('/admin/vuex/anketa/questions-converter', {
  //     func: 'getQuestData',
  //     skip: payload.skip,
  //     take: payload.take
  //   })
  //   return await response.data;
  // }


  async parse() {

    await this.getParse({
      skip: this.skipQuest,
      take: this.takeQuest,
      convertedAnswers: await this.convertedAnswers
    })
    localStorage.setItem('skipQuest', JSON.stringify(this.skipQuest))
    localStorage.setItem('takeQuest', JSON.stringify(this.takeQuest))

    if (this.cicle) {

      if (this.skipQuest >= this.count) {
        this.cicle = false
      }
      if (this.cicle) {
        await this.parse()
      }


    }
  }


  saveJson() {
    localStorage.setItem('questDataVariants', JSON.stringify(this.questDataVariants))
    localStorage.setItem('skipQuest', JSON.stringify(this.skipQuest))
    localStorage.setItem('takeQuest', JSON.stringify(this.takeQuest))
    console.log('saved json');
  }

  // saveSkipQuest() {
  //   localStorage.setItem('skipQuest', this.skipQuest)
  // }
  //
  // saveTakeQuest() {
  //   localStorage.setItem('takeQuest', this.takeQuest)
  // }
  changeTakeQuest() {
    this.setTakeQuest(this.takeQuest)
  }

  setDataFromLocal() {
    let localData = localStorage.getItem('questDataVariants')
    let localTakeQuest = localStorage.getItem('takeQuest')
    let localSkipQuest = localStorage.getItem('skipQuest')
    if (localData) {
      this.setInitQuestDataVariants(JSON.parse(localData))
    }
    if (localTakeQuest) {
      this.setTakeQuest(JSON.parse(localTakeQuest))
    }
    if (localSkipQuest) {
      this.setSkipQuest(JSON.parse(localSkipQuest))
    }
  }

  consoleAnswers() {

    let k = 25
    let slug = 'choosingPalletes25'
    let slug2 = 'wewewe'

    let obj = {}

    this.questData.map(i => {
      if (!obj.hasOwnProperty(i.uuid)) {
        obj[i.uuid] = {
          type: '',
          raw: '',
          converted: '',
          key: k,
          slug: ''
        }
      }

      if (i.data.hasOwnProperty('anketa')) {
        obj[i.uuid].raw = i.data.anketa.question[k] && i.data.anketa.question[k].answer ? i.data.anketa.question[k].answer.toString() : 'empty'
      } else {
        obj[i.uuid].raw = i.data[k] ? i.data[k].toString() : 'empty'
      }
      if (this.convertedAnswers[i.uuid][slug]) {
        obj[i.uuid].converted = this.convertedAnswers[i.uuid][slug].toString()
        obj[i.uuid].slug = slug
      } else if (this.convertedAnswers[i.uuid][slug2]) {
        obj[i.uuid].converted = this.convertedAnswers[i.uuid][slug2].toString()
        obj[i.uuid].slug = slug2
      } else {
        obj[i.uuid].converted = '----'
      }
      // ? :

    })

    console.table(obj);

  }


  async created() {
    await this.setDataFromLocal()
  }


}
</script>


