import {Component, Vue, Watch} from "vue-property-decorator";
import {namespace} from "vuex-class";
import {eventBus} from "../../bus";
import {IAnswer, IQuestion, IQuestionOption} from "../../types/anketa";

const StoreAnketa = namespace('StoreAnketa')

@Component
export default class questionMixin extends Vue {
    @StoreAnketa.State('yaTracks') yaTracks!: string[]

    @StoreAnketa.Mutation('setAnswer') setAnswer !: (payload: IAnswer) => void
    @StoreAnketa.Mutation('setSlug') setSlug !: (payload: string) => void
    @StoreAnketa.Mutation('setQueue') setQueue !: (payload: number) => void
    @StoreAnketa.Mutation('swapQuestions') swapQuestions!: (nextQuestionId: Number) => void
    @StoreAnketa.Mutation('setYatracks') setYatracks!: (payload: string) => void

    @StoreAnketa.Action('saveAnswers') saveAnswers!: () => void

    get question(): IQuestion {
        return this.$store.state.StoreAnketa.currentQuestion
    }

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

    // => METHODS
    checkedClass(id: Number) {
        return Array.isArray(this.options) // this.question.multiple
            ? this.options.indexOf(id) !== -1
            : this.options === id
    }

    isAnswerEmpty() {
        let res = 0
        Array.isArray(this.options)
            ? res = this.options.length ? res + 1 : res
            : res = this.options != '' ? res + 1 : res

        res = this.own != '' ? res + 1 : res

        Array.isArray(this.forms)
            ? res = this.options.length ? res + 1 : res
            : res = this.options != '' ? res + 1 : res

        typeof (this.forms) == 'object'
            ? res = Object.keys(this.forms).length ? res + 1 : res
            : res = this.options != '' ? res + 1 : res


        return res === 0

    }

    async save() {
        this.queue = this.queue
        await this.guessIsNextQuestion()
        if (!this.isAnswerEmpty()) {
            await this.saveAnswers()
        }
        this.setQueue(this.queue + 1)
    }

    async swapQueue(option: IQuestionOption) {
        try {
            if (option.next_question) {
                console.log('option.next_question', option.next_question);
                await this.swapQuestions(option.next_question)
            }
        } catch (e) {
            console.log('swapQueue', e);
        }

    }


    async guessIsNextQuestion() {
        this.forms = await this.forms

        if (!this.question.multiple) {

            let needle = Array.isArray(this.options) ? this.options[0] : this.options

            let currentOption = needle != undefined
                ? this.question.options.find((i: IQuestionOption) => i.id === needle)
                : this.question.options.find((i: IQuestionOption) => i.type === 'nextQuestion')

            if (currentOption) {
                await this.swapQueue(currentOption)
            }

        }
    }


    // metrika(ya : string = '', fb : string  =  '') {
    //     if(!this.yaTracks.includes(this.question.ya_track)) {
    //         // if (location.host == process.env.MIX_ANKETA_PROD_URL) {
    //
    //         setTimeout(() => {
    //             if (ya) {
    //                 // @ts-ignore
    //                 this.$metrika.reachGoal(ya)
    //             } else if (this.question.ya_track) {
    //                 // @ts-ignore
    //                 this.$metrika.reachGoal(this.question.ya_track)
    //                 this.setYatracks(this.question.ya_track)
    //             }
    //             if (fb) {
    //                 // @ts-ignore
    //                 fbq('track', fb)
    //             } else if (this.question.fb_track) {
    //                 // @ts-ignore
    //                 fbq('track', this.question.fb_track)
    //             }
    //         }, 250)
    //         // }
    //     }
    //
    //
    // }

    metrika(ya: string = '', fb: string = '') {
        if (
            !this.yaTracks.includes(this.question.ya_track)
            // && location.host == process.env.MIX_ANKETA_PROD_URL
        ) {
            if (ya) {
                // @ts-ignore
                this.$metrika.reachGoal(ya)
            } else if (this.question.ya_track) {
                // @ts-ignore
                this.$metrika.reachGoal(this.question.ya_track)
                this.setYatracks(this.question.ya_track)
            }

            if (fb) {
                // @ts-ignore
                fbq('track', fb)
            } else if (this.question.fb_track) {
                // @ts-ignore
                fbq('track', this.question.fb_track)
            }
        }


    }

    async created() {
        this.setSlug(this.question.slug)
        this.queue = this.queue
    }


    async updated() {
        this.metrika()
    }

    async mounted() {
        this.metrika()
    }

}
