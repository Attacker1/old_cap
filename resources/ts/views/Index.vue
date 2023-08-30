<template>

  <MainFrame>
    <h2>Главная страница</h2>

    <p>Место для персональых данных, сводочных данных по рабочим процессам (графики) -> в идеале треккер задач</p>

  </MainFrame>
</template>

<script lang="ts">
import {Component, Mixins, Vue} from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import MainFrame from "../components/layout/MainFrame.vue";
import rootMixin from "../mixins/settings/rootMixin";

    @Component({
      components: {MainFrame}
    })
    export default class IndexDD extends Mixins(rootMixin) {

    }
</script>
