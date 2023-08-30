import {Component, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'

const StoreAnketaConverter = namespace('StoreAnketaConverter')

@Component
export default class typeTextMixin extends Vue {
    @Prop() question
    @Prop() uuid
    @Prop() questionKey
    @Prop() keys
    @Prop() qSlug

    @StoreAnketaConverter.Mutation('setConvertedAnswers') setConvertedAnswers!: (data) => void
    public value

    async setAnketa() {
        if (this.question.hasOwnProperty('answer') && this.question.answer) {
            this.value = this.question.answer
        }

        if(this.value) {
            await this.setConvertedAnswers({
                uuid: this.uuid,
                slug: this.qSlug,
                value: this.value
            })

            this.value = undefined
        }

    }

    async mounted() {
        await this.setAnketa()
    }
    async updated() {
        await this.setAnketa()
    }


}
