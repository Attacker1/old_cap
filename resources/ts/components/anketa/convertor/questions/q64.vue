<template>
  <i v-if="control()"></i>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import converterMixin from "../../../../mixins/anketa/converterMixin";
import typeTextMixin from "../../../../mixins/anketa/typeTextMixin";
const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class q64 extends Mixins(converterMixin, typeTextMixin) {}
</script>


