import {Component, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'

const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class converterMixin extends Vue {

    @Prop() question
    @Prop() uuid
    @Prop() questionKey
    @Prop() keys

    @StoreAnketaConverter.State('questDataVariants') questDataVariants!: [][]
    @StoreAnketaConverter.Mutation('setQuestDataVariants') setQuestDataVariants!: (data) => void
    @StoreAnketaConverter.Mutation('setConvertedAnswers') setConvertedAnswers!: (data) => void

    @StoreAnketaConverter.State('convertedAnswers') convertedAnswers



    async control() {
        // if (this.question.hasOwnProperty('type') && this.question.type == "converted") {
        //     return false
        // }
        //
        // if (this.questDataVariants[this.questionKey] && this.questDataVariants[this.questionKey].length > 0) {
        //     await this.checkDiff()
        // } else {
        //     await this.makeItemVariant()
        // }

        return false

    }


    async checkDiff() {

        // @ts-ignore
        let check = this.questDataVariants[this.questionKey].some(item => this.lodash.isEqual(item, this.mapObj(this.question)))
        if (!check) {
            await this.makeItemVariant()
        }
        return check
    }

    async makeItemVariant() {
        await this.setQuestDataVariants({
            key: this.questionKey,
            data: this.mapObj(this.question)
        })
    }


    showAnswerForTest() {
        console.log('showAnswerForTest', this.question.answer);
    }

    mapObj(obj) {
        let newObj = {}
        this.keys.map(i => newObj[i] = obj[i])
        return newObj
    }


}
