<template>

    <div>
        <vue-dadata
            :query="forms[option.slug]"
            class="dadata_city"
            token="e713602c02594a12e79a39fc8167ccccd5dced60"
            :placeholder="option.placeholder"
            :on-change="submitDadataAddress"
        ></vue-dadata>
<!--        <input type='text' v-model='house' @change="validateHouse()" placeholder="квартира / номер офиса">-->

<!--        <textarea :placeholder="option.placeholder" @keyup="dadataAddress()" v-model="forms[option.slug]"/>-->
<!--        <div class="autocomplete">-->
<!--            <ul v-if="dadataQueryResult.length">-->
<!--                <li v-for="(item, keys) in dadataQueryResult" @click="submitDadataAddress(item)" :key="keys">-->
<!--                    {{ item.value }}-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
    </div>

</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import {IAnswer, IQuestionOption} from "../../../types/anketa";
import {namespace} from "vuex-class";
import {Prop} from "vue-property-decorator";
import {eventBus} from "../../../bus";
// @ts-ignore
import VueDadata from 'vue-dadata'
const StoreAnketa = namespace('StoreAnketa')
@Component({ components: {VueDadata}})

export default class DadataField extends Vue {

    @Prop() option!: IQuestionOption
    @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void

    public address: string = ''
    public house: string = ''

    get forms() {
        return this.$store.state.StoreAnketa.forms
    }

    set forms(payload) {
        this.setAnswer({options: [], own: '', forms: payload})
    }

    @StoreAnketa.Mutation('setDadataQueryResultData') setDadataQueryResultData!: (payload: any) => void

    get dadataQueryResultData() {
        return this.$store.state.StoreAnketa.dadataQueryResultData
    }

    set dadataQueryResultData(payload: any) {
        this.setDadataQueryResultData(payload)
    }

    public dadataObject = {}
    public dadataQueryResult = []
    // public is_house = null

    getSuggestion(suggestion: any){
        // this.address = suggestion.
            console.log(suggestion.value);
            this.forms[this.option.slug] = suggestion.value
    }
    validateHouse(){

    }

    submitDadataAddress(item: any) {
      console.log();
        this.forms[this.option.slug] = item.value
        this.forms = this.forms
        // eventBus.$emit('isHouse', item.data && item.data.house)


        this.dadataObject = item
        this.setDadataQueryResultData(item)
        // this.is_house = item.data.house
        this.dadataQueryResult = []
    }

    dadataAddress() {
        const url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address"
        const token = "e713602c02594a12e79a39fc8167ccccd5dced60"
        const options = {
            method: "POST",
            mode: "cors",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Token " + token
            },
            body: JSON.stringify({query: this.forms[this.option.slug], count: 3})
        }

        // @ts-ignore
        fetch(url, options)
            .then((response) => response.text())
            .then((result: any) => {
                this.dadataQueryResult = JSON.parse(result).suggestions
            })
            .catch(error => console.log("error", error))
    }



}
</script>
