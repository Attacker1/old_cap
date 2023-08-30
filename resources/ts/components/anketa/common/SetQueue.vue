<template>
    <div style="position: fixed; z-index: 50000">
        <input type="text" v-model.number="q" @keyup="setQueue(q)">
    </div>
</template>

<script lang="ts">

import {Component, Prop, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";
const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class SetQueue extends Vue {

    @StoreAnketa.Mutation('setQueue') setQueue!: (payload: Number) => void
    public q!: Number

}
</script>
