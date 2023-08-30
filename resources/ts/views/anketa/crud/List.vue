<template>
    <div>
        <h1>AnketaCRUDList Module</h1>
    </div>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    const ex = namespace('Example')

    @Component
    export default class AnketaCRUDList extends Vue {
        // @ex.State
        // private text!: string
        // @ex.Getter
        // private textUpper!: string
        // @ex.Mutation
        // private setText!: (newText: string) => void
        //
        // changeText (event: any): void {
        //     this.setText(event.target.value)
        // }
    }
</script>
