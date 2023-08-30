<template>
  <div>
    <div style="height: calc(100vh - 75px); overflow: auto">


      <table>

        <thead>
        <tr>
          <th>Вопросы</th>
        </tr>
        </thead>

        <tbody>

        <tr v-for="(question, key) in questDataVariants">
          <td>
            <Button size="s" @click="openCompareModal(key)"> ------------------------------ вопрос #{{ key }}</Button>
          </td>
          <td v-for="q in question" style="text-align: center; width: 50px;">
            <i class="h-icon-check primary-color" size="s"></i>
          </td>
        </tr>

        </tbody>

      </table>

      <template v-for="dataRow in processedData">

        <Properties :dataRow="dataRow" :uuid="dataRow.uuid" />


        <template v-for="(question,key) in dataRow.data.anketa.question">

<!--          {{alerted(question, key)}}-->


          <q0 v-if="key == 0" :question="changeQuestion(question)" qSlug='name'  :uuid="dataRow.uuid" :questionKey="0" :keys="['type', 'label']"/>
          <q1 v-if="key == 1" :question="changeQuestion(question)" qSlug='styleOnWeekend' :uuid="dataRow.uuid" :questionKey="1"
              :keys="['type', 'label','option']"/>
          <q2 v-if="key == 2" :question="changeQuestion(question)" qSlug='styleOnWork' :uuid="dataRow.uuid" :questionKey="2"
              :keys="['type', 'label','option']"/>
          <q3 v-if="key == 3" :question="changeQuestion(question)" qSlug='tryOtherOrSaveStyle' :uuid="dataRow.uuid" :questionKey="3"
              :keys="['type', 'label','option']"/>
          <q4 v-if="key == 4" :question="changeQuestion(question)" qSlug='choosingStyle4' :uuid="dataRow.uuid" :questionKey="4"
              :keys="['type', 'label','image','option']"/>
          <q5 v-if="key == 5" :question="changeQuestion(question)" qSlug='choosingStyle5' :uuid="dataRow.uuid" :questionKey="5"
              :keys="['type', 'label','image','option']"/>
          <q6 v-if="key == 6" :question="changeQuestion(question)" qSlug='choosingStyle6' :uuid="dataRow.uuid" :questionKey="6"
              :keys="['type', 'label','image','option']"/>
          <q7 v-if="key == 7" :question="changeQuestion(question)" qSlug='choosingStyle7' :uuid="dataRow.uuid" :questionKey="7"
              :keys="['type', 'label','image','option']"/>
          <q8 v-if="key == 8" :question="changeQuestion(question)" qSlug='choosingStyle8' :uuid="dataRow.uuid" :questionKey="8"
              :keys="['type', 'label','image','option']"/>
          <q9 v-if="key == 9" :question="changeQuestion(question)" qSlug='choosingStyle9' :uuid="dataRow.uuid" :questionKey="9"
              :keys="['type', 'label','image','option']"/>
          <q10 v-if="key == 10" :question="changeQuestion(question)" qSlug='choosingStyle10' :uuid="dataRow.uuid" :questionKey="10"
               :keys="['type', 'label','image','option']"/>
          <q11 v-if="key == 11" :question="changeQuestion(question)" qSlug='choosingStyle11' :uuid="dataRow.uuid" :questionKey="11"
               :keys="['type', 'label','image','option']"/>
          <q12 v-if="key == 12" :question="changeQuestion(question)" qSlug='choosingStyle12' :uuid="dataRow.uuid" :questionKey="12"
               :keys="['type', 'label','image','option']"/>
          <q13 v-if="key == 13" :question="changeQuestion(question)" qSlug='choosingStyle13' :uuid="dataRow.uuid" :questionKey="13"
               :keys="['type', 'label','image','option']"/>
          <q14 v-if="key == 14" :question="changeQuestion(question)" qSlug='email' :uuid="dataRow.uuid" :questionKey="14" :keys="['type', 'label']"/>
          <q15 v-if="key == 15" :question="changeQuestion(question)" qSlug='phone' :uuid="dataRow.uuid" :questionKey="15" :keys="['type', 'label']"/>
          <q16 v-if="key == 16" :question="changeQuestion(question)" qSlug='choosingStyle16' :uuid="dataRow.uuid" :questionKey="16"
               :keys="['type', 'label','image','option']"/>
          <q17 v-if="key == 17" :question="changeQuestion(question)" qSlug='choosingStyle17' :uuid="dataRow.uuid" :questionKey="17"
               :keys="['type', 'label','image','option']"/>
          <q18 v-if="key == 18" :question="changeQuestion(question)" qSlug='choosingStyle18' :uuid="dataRow.uuid" :questionKey="18"
               :keys="['type', 'label','image','option']"/>
          <q19 v-if="key == 19" :question="changeQuestion(question)" qSlug='choosingStyle19' :uuid="dataRow.uuid" :questionKey="19"
               :keys="['type', 'label','image','option']"/>
          <q20 v-if="key == 20" :question="changeQuestion(question)" qSlug='choosingStyle20' :uuid="dataRow.uuid" :questionKey="20"
               :keys="['type', 'label','image','option']"/>
          <q21 v-if="key == 21" :question="changeQuestion(question)" qSlug='choosingStyle21' :uuid="dataRow.uuid" :questionKey="21"
               :keys="['type', 'label','image','option']"/>
          <q22 v-if="key == 22" :question="changeQuestion(question)" qSlug='choosingStyle22' :uuid="dataRow.uuid" :questionKey="22"
               :keys="['type', 'label','image','option']"/>
          <q23 v-if="key == 23" :question="changeQuestion(question)" qSlug='choosingStyle23' :uuid="dataRow.uuid" :questionKey="23"
               :keys="['type', 'label','image','option']"/>
          <q24 v-if="key == 24" :question="changeQuestion(question)" qSlug='choosingStyle24' :uuid="dataRow.uuid" :questionKey="24"
               :keys="['type', 'label','image','option']"/>
          <q25 v-if="key == 25" :question="changeQuestion(question)" qSlug='choosingPalletes25' :uuid="dataRow.uuid" :questionKey="25"
               :keys="['type', 'label','option']"/>
          <q26 v-if="key == 26" :question="changeQuestion(question)" qSlug='bioHeight' :uuid="dataRow.uuid" :questionKey="26" :keys="['type', 'label']"/>
          <q27 v-if="key == 27" :question="changeQuestion(question)" qSlug='bioBirth' :uuid="dataRow.uuid" :questionKey="27" :keys="['type', 'label']"/>
          <q28 v-if="key == 28" :question="changeQuestion(question)" qSlug='occupation' :uuid="dataRow.uuid" :questionKey="28"
               :keys="['type', 'label','option']"/>
          <q29 v-if="key == 29" :question="changeQuestion(question)" qSlug='hairColor' :uuid="dataRow.uuid" :questionKey="29"
               :keys="['type', 'label','option']"/>
          <q30 v-if="key == 30" :question="changeQuestion(question)" qSlug='sizeTop' :uuid="dataRow.uuid" :questionKey="30"
               :keys="['type', 'label','option']"/>
          <q31 v-if="key == 31" :question="changeQuestion(question)" qSlug='sizeBottom' :uuid="dataRow.uuid" :questionKey="31"
               :keys="['type', 'label','option']"/>
          <q32 v-if="key == 32" :question="changeQuestion(question)" qSlug='bioChest' :uuid="dataRow.uuid" :questionKey="32" :keys="['type', 'label']"/>
          <q33 v-if="key == 33" :question="changeQuestion(question)" qSlug='bioWaist' :uuid="dataRow.uuid" :questionKey="33" :keys="['type', 'label']"/>
          <q34 v-if="key == 34" :question="changeQuestion(question)" qSlug='bioHips' :uuid="dataRow.uuid" :questionKey="34" :keys="['type', 'label']"/>
          <q35 v-if="key == 35" :question="changeQuestion(question)" qSlug='aboutTopStyle' :uuid="dataRow.uuid" :questionKey="35"
               :keys="['type', 'label','option']"/>
          <q36 v-if="key == 36" :question="changeQuestion(question)" qSlug='aboutBottomStyle' :uuid="dataRow.uuid" :questionKey="36"
               :keys="['type', 'label','option']"/>
          <q37 v-if="key == 37" :question="changeQuestion(question)" qSlug='modelsJeans' :uuid="dataRow.uuid" :questionKey="37"
               :keys="['type', 'label','option']"/>
          <q38 v-if="key == 38" :question="changeQuestion(question)" qSlug='trousersFit' :uuid="dataRow.uuid" :questionKey="38"
               :keys="['type', 'label','option']"/>
          <q39 v-if="key == 39" :question="changeQuestion(question)" qSlug='trouserslength' :uuid="dataRow.uuid" :questionKey="39"
               :keys="['type', 'label','option']"/>
          <q40 v-if="key == 40" :question="changeQuestion(question)" qSlug='dressesType' :uuid="dataRow.uuid" :questionKey="40"
               :keys="['type', 'label','option']"/>
          <q41 v-if="key == 41" :question="changeQuestion(question)" qSlug='bodyPartsToHide' :uuid="dataRow.uuid" :questionKey="41"
               :keys="['type', 'label','option']"/>
          <q42 v-if="key == 42" :question="changeQuestion(question)" qSlug='preferJeansWithTopOrDresses' :uuid="dataRow.uuid" :questionKey="42"
               :keys="['type', 'label','option']"/>
          <q43 v-if="key == 43" :question="changeQuestion(question)" qSlug='whereUsuallyBuyClothes' :uuid="dataRow.uuid" :questionKey="43"
               :keys="['type', 'label','option']"/>
          <q44 v-if="key == 44" :question="changeQuestion(question)" qSlug='earsPierced' :uuid="dataRow.uuid" :questionKey="44"
               :keys="['type', 'label','option']"/>
          <q45 v-if="key == 45" :question="changeQuestion(question)" qSlug='jewelry' :uuid="dataRow.uuid" :questionKey="45"
               :keys="['type', 'label','option']"/>
          <q46 v-if="key == 46" :question="changeQuestion(question)" qSlug='bijouterie' :uuid="dataRow.uuid" :questionKey="46"
               :keys="['type', 'label','option']"/>
          <q47 v-if="key == 47" :question="changeQuestion(question)" qSlug='capsulaFirstOfAll' :uuid="dataRow.uuid" :questionKey="47"
               :keys="['type', 'label','option']"/>
          <q48 v-if="key == 48" :question="changeQuestion(question)" qSlug='capsulaNotFirstOfAll' :uuid="dataRow.uuid" :questionKey="48"
               :keys="['type', 'label','option']"/>
          <q49 v-if="key == 49" :question="changeQuestion(question)" qSlug='capsulaNotWantAccessories' :uuid="dataRow.uuid" :questionKey="49"
               :keys="['type', 'label','option']"/>
          <q50 v-if="key == 50" :question="changeQuestion(question)" qSlug='noColor' :uuid="dataRow.uuid" :questionKey="50"
               :keys="['type', 'label','option']"/>
          <q51 v-if="key == 51" :question="changeQuestion(question)" qSlug='printsDislike' :uuid="dataRow.uuid" :questionKey="51"
               :keys="['type', 'label','option']"/>
          <q52 v-if="key == 52" :question="changeQuestion(question)" qSlug='fabricsShouldAvoid' :uuid="dataRow.uuid" :questionKey="52"
               :keys="['type', 'label','option']"/>
          <q53 v-if="key == 53" :question="changeQuestion(question)" qSlug='additionalNuances' :uuid="dataRow.uuid" :questionKey="53" :keys="['type', 'label']"/>
          <q54 v-if="key == 54" :question="changeQuestion(question)" qSlug='howMuchToSpendOnBlouseShirt' :uuid="dataRow.uuid" :questionKey="54"
               :keys="['type', 'label','option']"/>
          <q55 v-if="key == 55" :question="changeQuestion(question)" qSlug='howMuchToSpendOnSweaterJumperPullover' :uuid="dataRow.uuid" :questionKey="55"
               :keys="['type', 'label','option']"/>
          <q56 v-if="key == 56" :question="changeQuestion(question)" qSlug='howMuchToSpendOnDressesSundresses' :uuid="dataRow.uuid" :questionKey="56"
               :keys="['type', 'label','option']"/>
          <q57 v-if="key == 57" :question="changeQuestion(question)" qSlug='howMuchToSpendOnJacket' :uuid="dataRow.uuid" :questionKey="57"
               :keys="['type', 'label','option']"/>
          <q58 v-if="key == 58" :question="changeQuestion(question)" qSlug='howMuchToSpendOnJeansTrousersSkirts' :uuid="dataRow.uuid" :questionKey="58"
               :keys="['type', 'label','option']"/>
          <q59 v-if="key == 59" :question="changeQuestion(question)" qSlug='howMuchToSpendOnBags' :uuid="dataRow.uuid" :questionKey="59"
               :keys="['type', 'label','option']"/>
          <q60 v-if="key == 60" :question="changeQuestion(question)" qSlug='howMuchToSpendOnBeltsScarvesShawls' :uuid="dataRow.uuid" :questionKey="60"
               :keys="['type', 'label','option']"/>
          <q61 v-if="key == 61" :question="changeQuestion(question)" qSlug='howMuchToSpendOnEarringsNecklacesBracelets' :uuid="dataRow.uuid" :questionKey="61"
               :keys="['type', 'label','option']"/>
          <q62 v-if="key == 62" :question="changeQuestion(question)" qSlug='whatPurpose' :uuid="dataRow.uuid" :questionKey="62"
               :keys="['type', 'label','option']"/>
          <q63 v-if="key == 63" :question="changeQuestion(question)" qSlug='address' :uuid="dataRow.uuid" :questionKey="63" :keys="['type', 'label']"/>
          <q64 v-if="key == 64" :question="changeQuestion(question)" qSlug='deliveryDate' :uuid="dataRow.uuid" :questionKey="64" :keys="['type', 'label']"/>
          <q65 v-if="key == 65" :question="changeQuestion(question)" qSlug='deliveryTime' :uuid="dataRow.uuid" :questionKey="65"
               :keys="['type', 'label','option']"/>
          <q66 v-if="key == 66" :question="changeQuestion(question)" qSlug='deliveryBackTime' :uuid="dataRow.uuid" :questionKey="66"
               :keys="['type', 'label','option']"/>
          <q67 v-if="key == 67" :question="changeQuestion(question)" qSlug='bioName' :uuid="dataRow.uuid" :questionKey="67" :keys="['type', 'label']"/>
          <q68 v-if="key == 68" :question="changeQuestion(question)" qSlug='socials' :uuid="dataRow.uuid" :questionKey="68" :keys="['type', 'label']"/>
          <q69 v-if="key == 69" :question="changeQuestion(question)" qSlug='knewAboutUs' :uuid="dataRow.uuid" :questionKey="69"
               :keys="['type', 'label','option']"/>
          <q70 v-if="key == 70" :question="changeQuestion(question)" qSlug='bioWeight' :uuid="dataRow.uuid" :questionKey="70" :keys="['type', 'label']"/>
          <q71 v-if="key == 71" :question="changeQuestion(question)" qSlug='delivery' :uuid="dataRow.uuid" :questionKey="71"
               :keys="['type', 'label','option']"/>
          <q72 v-if="key == 72" :question="changeQuestion(question)" qSlug='photosAttach' :uuid="dataRow.uuid" :questionKey="72"
               :keys="['type', 'label','option']"/>
          <q73 v-if="key == 73" :question="changeQuestion(question)" qSlug='photos' :uuid="dataRow.uuid" :questionKey="73" :keys="['type', 'label']"/>
          <q74 v-if="key == 74" :question="changeQuestion(question)" qSlug='address_a' :uuid="dataRow.uuid" :questionKey="74" :keys="['type', 'label']"/>
          <q75 v-if="key == 75" :question="changeQuestion(question)" qSlug='addressHome' :uuid="dataRow.uuid" :questionKey="75" :keys="['type', 'label']"/>
          <q76 v-if="key == 76" :question="changeQuestion(question)" qSlug='addressOffice' :uuid="dataRow.uuid" :questionKey="76" :keys="['type', 'label']"/>
          <q77 v-if="key == 77" :question="changeQuestion(question)" qSlug='addressComment' :uuid="dataRow.uuid" :questionKey="77" :keys="['type', 'label']"/>
          <q78 v-if="key == 78" :question="changeQuestion(question)" qSlug='whatHelp' :uuid="dataRow.uuid" :questionKey="78"
               :keys="['type', 'label','option']"/>
          <q79 v-if="key == 79" :question="changeQuestion(question)" qSlug='bioSurname' :uuid="dataRow.uuid" :questionKey="79" :keys="['type']"/>
          <q80 v-if="key == 80" :question="changeQuestion(question)" qSlug='deliveryType' :uuid="dataRow.uuid" :questionKey="80"
               :keys="['type', 'label','option']"/>
          <q81 v-if="key == 81" :question="changeQuestion(question)" qSlug='boxberryPoint' :uuid="dataRow.uuid" :questionKey="81" :keys="['type', 'label']"/>
          <q82 v-if="key == 82" :question="changeQuestion(question)" qSlug='boxberryCity' :uuid="dataRow.uuid" :questionKey="82" :keys="['type', 'label']"/>
          <q83 v-if="key == 83" :question="changeQuestion(question)" qSlug='whatSeason' :uuid="dataRow.uuid" :questionKey="83"
               :keys="['type', 'label','option']"/>
          <q84 v-if="key == 84" :question="changeQuestion(question)" qSlug='bioPatronymic' :uuid="dataRow.uuid" :questionKey="84" :keys="['type']"/>
          <q85 v-if="key == 85" :question="changeQuestion(question)" qSlug='choosingStyle25' :uuid="dataRow.uuid" :questionKey="85"
               :keys="['type', 'label','image','option']"/>

        </template>

      </template>

      <CompareModal/>


    </div>
  </div>

</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'

const StoreAnketaConverter = namespace('StoreAnketaConverter')
import {eventBus} from "../../../bus";

import q0 from "./questions/q0.vue";
import q1 from "./questions/q1.vue";
import q2 from "./questions/q2.vue";
import q3 from "./questions/q3.vue";
import q4 from "./questions/q4.vue";
import q5 from "./questions/q5.vue";
import q6 from "./questions/q6.vue";
import q7 from "./questions/q7.vue";
import q8 from "./questions/q8.vue";
import q9 from "./questions/q9.vue";

import q10 from "./questions/q10.vue";
import q11 from "./questions/q11.vue";
import q12 from "./questions/q12.vue";
import q13 from "./questions/q13.vue";
import q14 from "./questions/q14.vue";
import q15 from "./questions/q15.vue";
import q16 from "./questions/q16.vue";
import q17 from "./questions/q17.vue";
import q18 from "./questions/q18.vue";
import q19 from "./questions/q19.vue";

import q20 from "./questions/q20.vue";
import q21 from "./questions/q21.vue";
import q22 from "./questions/q22.vue";
import q23 from "./questions/q23.vue";
import q24 from "./questions/q24.vue";
import q25 from "./questions/q25.vue";
import q26 from "./questions/q26.vue";
import q27 from "./questions/q27.vue";
import q28 from "./questions/q28.vue";
import q29 from "./questions/q29.vue";

import q30 from "./questions/q30.vue";
import q31 from "./questions/q31.vue";
import q32 from "./questions/q32.vue";
import q33 from "./questions/q33.vue";
import q34 from "./questions/q34.vue";
import q35 from "./questions/q35.vue";
import q36 from "./questions/q36.vue";
import q37 from "./questions/q37.vue";
import q38 from "./questions/q38.vue";
import q39 from "./questions/q39.vue";

import q40 from "./questions/q40.vue";
import q41 from "./questions/q41.vue";
import q42 from "./questions/q42.vue";
import q43 from "./questions/q43.vue";
import q44 from "./questions/q44.vue";
import q45 from "./questions/q45.vue";
import q46 from "./questions/q46.vue";
import q47 from "./questions/q47.vue";
import q48 from "./questions/q48.vue";
import q49 from "./questions/q49.vue";

import q50 from "./questions/q50.vue";
import q51 from "./questions/q51.vue";
import q52 from "./questions/q52.vue";
import q53 from "./questions/q53.vue";
import q54 from "./questions/q54.vue";
import q55 from "./questions/q55.vue";
import q56 from "./questions/q56.vue";
import q57 from "./questions/q57.vue";
import q58 from "./questions/q58.vue";
import q59 from "./questions/q59.vue";

import q60 from "./questions/q60.vue";
import q61 from "./questions/q61.vue";
import q62 from "./questions/q62.vue";
import q63 from "./questions/q63.vue";
import q64 from "./questions/q64.vue";
import q65 from "./questions/q65.vue";
import q66 from "./questions/q66.vue";
import q67 from "./questions/q67.vue";
import q68 from "./questions/q68.vue";
import q69 from "./questions/q69.vue";

import q70 from "./questions/q70.vue";
import q71 from "./questions/q71.vue";
import q72 from "./questions/q72.vue";
import q73 from "./questions/q73.vue";
import q74 from "./questions/q74.vue";
import q75 from "./questions/q75.vue";
import q76 from "./questions/q76.vue";
import q77 from "./questions/q77.vue";
import q78 from "./questions/q78.vue";
import q79 from "./questions/q79.vue";

import q80 from "./questions/q80.vue";
import q81 from "./questions/q81.vue";
import q82 from "./questions/q82.vue";
import q83 from "./questions/q83.vue";
import q84 from "./questions/q84.vue";
import q85 from "./questions/q85.vue";

import CompareModal from "./CompareModal.vue";
import Properties from "./questions/Properties.vue";

@Component({
  components: {
    Properties,
    CompareModal,
    q0, q1, q2, q3, q4, q5, q6, q7, q8, q9,
    q10, q11, q12, q13, q14, q15, q16, q17, q18, q19,
    q20, q21, q22, q23, q24, q25, q26, q27, q28, q29,
    q30, q31, q32, q33, q34, q35, q36, q37, q38, q39,
    q40, q41, q42, q43, q44, q45, q46, q47, q48, q49,
    q50, q51, q52, q53, q54, q55, q56, q57, q58, q59,
    q60, q61, q62, q63, q64, q65, q66, q67, q68, q69,
    q70, q71, q72, q73, q74, q75, q76, q77, q78, q79,
    q80, q81, q82, q83, q84, q85
  }
})
export default class ConverterTable extends Vue {

  // public questDataVariants = []

  // setQuestDataVariants(payload) {
  //   // console.log('jjjjjjjjjjjj', payload);
  //   if (this.questDataVariants[payload.key]) {
  //     // @ts-ignore
  //     this.questDataVariants[payload.key].push(payload.data)
  //   } else {
  //     // @ts-ignore
  //     this.questDataVariants[payload.key] = []
  //     // @ts-ignore
  //     this.questDataVariants[payload.key].push(payload.data)
  //   }
  //
  //
  // }

  @StoreAnketaConverter.State('questDataVariants') questDataVariants
  @StoreAnketaConverter.Mutation('setQuestDataVariants') setQuestDataVariants!: (data) => void

  public processedData = []

  alerted(o,k) {
    console.log('alerted',o, k);

  }

  changeQuestion(q){
    if(q.hasOwnProperty('answer')){
      return q
    } else {
      let converted = {}
      converted['type'] = 'converted'
      converted['answer'] = q
     return  converted
    }

  }

  process(data) {
    this.processedData = []
    data.forEach(item => {
      // if(item.data) {
      //   item.data = JSON.parse(item.data)
      // }

      if (item.data) {
        if (
            item.data
            && item.data.hasOwnProperty('anketa')
            && item.data.anketa.hasOwnProperty('question')
        ) {

          // @ts-ignore
          this.processedData.push(item) //.data.anketa.question
          // return item.data.anketa.question
        } else if (item.data.hasOwnProperty(15)) {
          // @ts-ignore
          // console.log('ggggggggggggg',item.data);
          // data.anketa.question




          let newData = {
            data: {
              anketa: {
                question: item.data
              }
            },
            uuid: item.uuid

          }
          // @ts-ignore
          this.processedData.push(newData)
        }
      }


    })

    this.processedData.map(i => {

      // @ts-ignore
      // console.log('ff', i.data.anketa.question); // .data.anketa.question
    })

    // console.log('processedData', this.processedData);

  }

  showCell(data) {
    return 'E'
  }

  openCompareModal(key: number) {
    eventBus.$emit('makeCompare', key)
  }


  created() {
    eventBus.$on('questionsLoaded', (arrData) => {
      this.process(arrData)
    })
  }

}
</script>
