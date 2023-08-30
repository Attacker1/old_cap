<template>
<div class="alert" v-if="active">
    <div class="alert-body">
        <a href="javascript:" class="alert-close" @click="active = false">✖</a>
        <div class="alert-content" v-html="content"></div>
    </div>
</div>
</template>

<script lang="ts">

import {Component, Prop, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";
const StoreAnketa = namespace('StoreAnketa')
import {eventBus} from "../../../bus";
@Component({})

export default class Alert extends Vue {

    public content!: String
    public active: boolean = false


    mounted(){
        eventBus.$on('openAlert', (payload: String) => {
            this.active = true
            this.content = payload
        })
    }

}
</script>
