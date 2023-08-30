<template>
  <div>
    <div class="loading" v-if="loading">
      <div class="loading_center">
        <p v-if="text" v-html="text"/>
        <img src="/img/bars.svg" alt="loader"/>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import {eventBus} from "../../../bus";

const ex = namespace('Example')

@Component
export default class Loader extends Vue {
  public loading = false
  public text = ''

  created() {
    eventBus.$on('actionSuccess', (resp) => {
      this.loading = false
    })

    eventBus.$on('emitRequestStarted', (payload) => {
      this.text = payload
      this.loading = true
    })
  }
}
</script>

<style scoped>
.loading {
  box-sizing: border-box;
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(255, 255, 255, .8);
  z-index: 100;
  display: flex;
  justify-content: center;
  align-items: center;
}

.loading_center {
  /*background: red;*/
  opacity: 1;
  width: 300px;
  height: 300px;
  margin: 0px auto;
  text-align: center;
}

.loading_center p {
  font-size: 18px;

}
</style>
