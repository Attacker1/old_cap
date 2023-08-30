<template>
  <div class="total-price">
    <div class="total-price__item">
      <div class="total-price__item-name">Услуги стилиста</div>
      <div class="total-price__item-count">{{oldPrice}} ₽</div>
    </div>
    <div class="total-price__item total-price__item_coupon">
      <div class="total-price__item-name">Купон</div>
      <div class="total-price__item-count">-{{calcDiscount}} ₽</div>
    </div>
    <div class="total-price__separator"></div>
    <div class="total-price__amount">
      <div class="total-price__amount-name">К оплате</div>
      <div class="total-price__amount-count">{{newPrice}} ₽</div>
    </div>
<!--    <a href="javascript:" class="total-price__link">У меня есть промокод</a>-->
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {namespace} from "vuex-class";
import {IQuestion} from "../../../types/anketa";
const StoreAnketa = namespace('StoreAnketa')
@Component({})
export default class TotalPrice extends Vue {


  @StoreAnketa.State('price') price!: number
  get question(): IQuestion {return this.$store.state.StoreAnketa.currentQuestion}

  get oldPrice(){
    return Number(this.question.options[0].text_thumb)
  }

  get newPrice(){
    return this.price ?? Number(this.question.options[0].text_thumb)
  }

  get calcDiscount(){
    return Number(this.question.options[0].text_thumb) - this.newPrice
  }
}
</script>
