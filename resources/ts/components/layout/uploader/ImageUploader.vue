<template>
  <div class="images-upload">
    <label class="images-upload-nest" ref="file" for="img"
           :style="makeStyle(file)">
      <input type="file" accept="image/*" id="img" @change="encodeImageFileAsURL" style="display: none;">
    </label>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import {eventBus} from "../../../bus";
const StoreSettings = namespace('StoreSettings')
@Component
export default class Simple extends Vue {

  $refs!: { file: HTMLFormElement }
  @Prop() path
  @Prop() filename
  @Prop() picture
  public file = null


  @StoreSettings.State('image') image
  @StoreSettings.Mutation('setImage') setImage!: (payload) => void
  @StoreSettings.Action('uploadImage') uploadImage!: (payload) => void


   encodeImageFileAsURL(element: any) {
    let id = element.target.getAttribute('id')
    let file = element.target.files[0];
    let reader = new FileReader();
     reader.onloadend = () => {
      this.picture = null
      // @ts-ignore
      this.file = reader.result
      // this.$refs.file.classList.add('cover')
      // this.$refs.file.style.cssText = `background: url(${this.file}) no-repeat center;`

    }

    reader.readAsDataURL(file);
     setTimeout(() => {
       this.uploadImage({
         image: this.file,
         path: this.path,
         filename: this.filename
       })
     },1000)

     setTimeout(() => {
       eventBus.$emit('imageUploaded', this.image)
     }, 2000)




  }

  makeStyle(file) {
    if(this.picture) {
      file = '/storage/' + this.picture
    }
    return file ? {'background': `url(${file}) no-repeat center`} : ''
  }

}
</script>

<style>

.images-upload-nest {
  background-size: cover !important;
  width: 120px;
  height: 120px;
  display: block;
  position: relative;
  cursor: pointer;
  background-color: red;
}
</style>
