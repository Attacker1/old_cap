<template>
  <Modal v-model="open" :closeOnMask="false" :hasCloseIcon="true" :fullScreen="true">
    <table class="compare-modal-table">

      <thead>
      <tr>
        <th>Вопрос № {{key}}</th>
        <th v-for="(col, colKey) in content">{{colKey}}</th>
      </tr>
      </thead>

      <tbody>

      <tr v-for="key in keys">
        <td>{{ key }}</td>
        <template v-for="value in content">
          <td v-if="key === 'option'">
            <pre v-html="syntaxHighlight(value[key])"></pre>
          </td>
          <td v-else>{{ value[key] }}</td>
        </template>
      </tr>
      </tbody>

    </table>
  </Modal>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'

const StoreAnketaConverter = namespace('StoreAnketaConverter')
import {eventBus} from "../../../bus";
import {IQuestDataVariant} from "../../../types/anketa";
// @ts-ignore
import VueJsonPretty from 'vue-json-pretty';
import 'vue-json-pretty/lib/styles.css';

@Component({components: {VueJsonPretty}})
export default class CompareModal extends Vue {

  public open = false

  public content: IQuestDataVariant[] = []
  public key
  public keys

  @StoreAnketaConverter.State('questDataVariants') questDataVariants!: IQuestDataVariant[][]

  syntaxHighlight(json) {
    json = JSON.stringify(json, undefined, 4)
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
      var cls = 'number';
      if (/^"/.test(match)) {
        if (/:$/.test(match)) {
          cls = 'key';
        } else {
          cls = 'string';
        }
      } else if (/true|false/.test(match)) {
        cls = 'boolean';
      } else if (/null/.test(match)) {
        cls = 'null';
      }
      return '<span class="' + cls + '">' + match + '</span>';
    });
  }

  viewData(key: number) {
    this.key = key
    this.keys = Object.keys(this.questDataVariants[key][0])
    this.content = this.questDataVariants[key]
  }

  created() {
    eventBus.$on('makeCompare', (key: number) => {
      this.viewData(key)
      this.open = true
    })


    document.addEventListener('keyup',  (evt) => {
      if (evt.keyCode === 27) {
       this.open = false
      }
    });


  }

}
</script>

<style>
.compare-modal-table {
  margin: 15px auto;


}

.compare-modal-table td {
  padding: 10px 8px;
  border-bottom: 1px solid gray;
}
.compare-modal-table thead tr {
  width: 100%;
  background: grey;
}
.compare-modal-table th {
  color: white;
  padding-top: 5px;
}

.compare-modal-table td:first-of-type {
  font-weight: bold;
  text-transform: uppercase;
  width: 120px;


}
pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
.string { color: green; }
.number { color: darkorange; }
.boolean { color: blue; }
.null { color: magenta; }
.key { color: red; }
</style>
