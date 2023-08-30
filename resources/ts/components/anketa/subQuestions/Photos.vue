<template>
  <div class='question question_upload'>

    <QuestionHead/>

    <div class="answers">
      <div class="answers__header"></div>
      <div class="answers__body answers__body_flex answers__body_photo">

        <div class="images-upload">
          <div class="images-upload-nest" ref="img_1"
               :style="img_1 ? {'background': `url(${img_1}) no-repeat center`, 'background-size': 'cover'} : ''">
            <input type="file" accept="image/*" id="img_1" @change="encodeImageFileAsURL">
          </div>
          <div class="images-upload-nest" ref="img_2"
               :style="img_2 ? {'background': `url(${img_2}) no-repeat center`, 'background-size': 'cover'}: ''">
            <input type="file" accept="image/*" id="img_2" @change="encodeImageFileAsURL">
          </div>
          <div class="images-upload-nest" ref="img_3"
               :style="img_3 ? {'background': `url(${img_3}) no-repeat center`, 'background-size': 'cover'} : ''">
            <input type="file" accept="image/*" id="img_3" @change="encodeImageFileAsURL">
          </div>
        </div>
        <!--        <label v-else class="photo-btn" @click="btnPressed = true">
                  <span>Нажмите, чтобы <br/> загрузить фото</span>
                </label>-->

        <div class='disclaimer' v-if="question.disclaimer">
          <div v-html="question.disclaimer"></div>
        </div>

      </div>
      <div class="answers__footer answers__footer_second">
        <!--        <button class="skip-question" @click="nextQuestion()">Пропустить</button>-->
        <button class="next-question" @click="nextQuestion()">Дальше</button>
      </div>

<!--      <canvas  ref="canvas" id="canvas"></canvas>-->
      <img src="" ref="ddd" alt=""/>

    </div>


  </div>

</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestion, IQuestionOption} from "../../../types/anketa";
import {namespace} from "vuex-class";
import {eventBus} from "../../../bus";
import {Watch} from "vue-property-decorator";
import QuestionHead from "../common/QuestionHead.vue";

const StoreAnketa = namespace('StoreAnketa')

@Component({
  components: {QuestionHead}
})

export default class Photos extends Vue {

  public step = 'CHOISE'

  public btnPressed = false

  // => VARS
  get question(): IQuestion {
    return this.$store.state.StoreAnketa.currentQuestion
  }

  @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
  @StoreAnketa.Mutation('setSlug') setSlug !: (payload: string) => void

  @StoreAnketa.Mutation('setQueue') setQueue !: (payload: number) => void

  get queue() {
    return this.$store.state.StoreAnketa.queue
  }

  set queue(payload: number) {
    this.setQueue(payload)
  }

  get options() {
    return this.$store.state.StoreAnketa.options
  }

  set options(payload) {
    this.setAnswer({options: payload, own: this.own, forms: this.forms})
  }

  get own() {
    return this.$store.state.StoreAnketa.own
  }

  set own(payload) {
    this.setAnswer({options: this.options, own: payload, forms: this.forms})
  }

  get forms() {
    return this.$store.state.StoreAnketa.forms
  }

  set forms(payload) {
    this.setAnswer({options: this.options, own: this.own, forms: payload})
  }

  @StoreAnketa.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void

  @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void

  checkPhotos() {
    let pointer: IQuestionOption | undefined = this.question.options.find((i: IQuestionOption) => i.id === this.options)
    if (pointer !== undefined && pointer.placeholder === "make-photo") {
      this.step = 'PHOTO'
      eventBus.$emit('paused', true)
    } else {
      this.queue++
    }

  }

  // METHODS
  checkedClass(id: Number) {
    return this.question.multiple
        ? this.options && this.options.indexOf(id) !== -1
        : this.options === id
  }

  @StoreAnketa.Mutation('setClientImage_1') setClientImage_1!: (payload: any) => void

  get img_1() {
    return this.$store.state.StoreAnketa.img_1
  }

  set img_1(payload: any) {
    this.setClientImage_1(payload)
  }

  @StoreAnketa.Getter('getImg_1') getImg_1!: String

  @StoreAnketa.Mutation('setClientImage_2') setClientImage_2!: (payload: any) => void

  get img_2() {
    return this.$store.state.StoreAnketa.img_2
  }

  set img_2(payload: any) {
    this.setClientImage_2(payload)
  }

  @StoreAnketa.Getter('getImg_2') getImg_2!: String

  @StoreAnketa.Mutation('setClientImage_3') setClientImage_3!: (payload: any) => void

  get img_3() {
    return this.$store.state.StoreAnketa.img_3
  }

  set img_3(payload: any) {
    this.setClientImage_3(payload)
  }

  @StoreAnketa.Getter('getImg_3') getImg_3!: String

  @StoreAnketa.Action('saveClientPhotos') saveClientPhotos!: () => void

  $refs!: {
    img_1: HTMLFormElement,
    img_2: HTMLFormElement,
    img_3: HTMLFormElement,
    canvas: HTMLCanvasElement
  }

  encodeImageFileAsURL(e) {
    eventBus.$emit('emitRequestStarted','Загружаем фото')
    const width = 1200;
    const height = 1200;
    let id = e.target.getAttribute('id')
    const fileName = e.target.files[0].name;
    const reader = new FileReader();
    reader.readAsDataURL(e.target.files[0]);

    reader.onload = event => {
      const img = new Image();
      // @ts-ignore
      img.src = event.target.result;
      img.onload = () => {

        let iw=img.width;
        let ih=img.height;
        // let scale=Math.min((width/iw),(height/ih));
        let scale= iw/width;
        console.log('scale',scale,iw,ih);
        let iwScaled=iw/scale;
        let ihScaled=ih/scale;

        const elem = document.createElement('canvas');
        elem.width = iwScaled;
        elem.height = ihScaled;
        const ctx = elem.getContext('2d');
        // @ts-ignore
        ctx.drawImage(img, 0, 0, iwScaled, ihScaled);

        let base64 = elem.toDataURL("image/jpeg",.94)

        if (id === 'img_1') {
          this.img_1 = base64
          this.$refs.img_1.classList.add('cover')
          this.$refs.img_1.style.cssText = `background: url(${this.img_1}) no-repeat center;`
          eventBus.$emit('actionSuccess')
        }
        if (id === 'img_2') {
          this.img_2 = base64
          this.$refs.img_2.classList.add('cover')
          this.$refs.img_2.style.cssText = `background: url(${this.img_2}) no-repeat center;`
          eventBus.$emit('actionSuccess')
        }
        if (id === 'img_3') {
          this.img_3 = base64
          this.$refs.img_3.classList.add('cover')
          this.$refs.img_3.style.cssText = `background: url(${this.img_3}) no-repeat center;`
          eventBus.$emit('actionSuccess')
        }


      }
      reader.onerror = error => console.log(error);
    };

  }

  async makeForms() {
    await this.saveClientPhotos()
    let arr = [this.getImg_1, this.getImg_2, this.getImg_3]
    this.forms = arr.map((i) => i)
  }


  async nextQuestion() {
    // if (!this.img_1 && !this.img_2 && !this.img_3) {
    //   eventBus.$emit('openAlert', 'Загрузите хотябы одно фото, или вернитесь на шаг назад')
    //   return false
    // }

    await this.makeForms()

    const currentOption: IQuestionOption | undefined = this.question.options.find((i: IQuestionOption) => i.id === this.options)
    if (currentOption && currentOption.next_question) {
      this.swapQuestions(currentOption.next_question)
    }

    this.saveAnswers()
    this.queue++

  }

  created() {

  }

}
</script>

<style>
.images-upload {
  right: calc(50% - 105px) !important;
}
#canvas {
  display: none;
}

</style>

