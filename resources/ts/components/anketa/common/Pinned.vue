<template>
    <div class="pinned">
        <div>
            <div class="progress">
                <div class="line" style="flex-direction: column">
                    <div class="done" :style="{width:progress+'%'}"></div>
                    <div class="more" style="width: 100%; position: relative; top: -3px; z-index: -1;"></div>
                </div>
                <div class="stepers">

                    <div v-if="queue === 0" class="steper"></div>
                    <div v-else-if="queue === 1" class="steper on current"></div>
                    <div v-else class="steper on"></div>


                    <div class="steper" :class="onCurrent(1)"></div>
                    <div class="steper" :class="onCurrent(2)"></div>
                    <div class="steper" :class="onCurrent(3)"></div>
                    <div class="steper" :class="onCurrent(4)"></div>

                    <div v-if="queue === lastKey" class="steper on current"></div>
                    <div v-else class="steper"></div>
                </div>
            </div>
            <div class="progress-percent"> {{ progress }}%</div>
        </div>
    </div>
</template>

<script lang="ts">

import {Component, Prop, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";

const StoreAnketa = namespace('StoreAnketa')
@Component({})

export default class Pinned extends Vue {

    @Prop() queue: number = 0
    @Prop() queueLength: number = 0

    get lastKey() {
        return this.queueLength - 1
    }


    get progress() {
        let percent = (100 / (this.queueLength - 1)) * this.queue
        return percent >= 100 ? 100 : percent.toFixed()
    }

    onCurrent(part: number) {
        let grade = Math.round(this.queueLength / 5 * part)
        if (grade == this.queue) {
            return 'on current'
        } else if (this.queue > grade) {
            return 'on'
        } else {
            return ''
        }

    }


}
</script>
