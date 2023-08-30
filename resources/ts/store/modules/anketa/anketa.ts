import {Module, Mutation, VuexModule, Action, MutationAction} from "vuex-module-decorators";
import Axios from "axios";
import {
    IAnswer,
    IAnswers, IBonus,
    IBoxberry,
    ICoupon,
    IKeyValue, ILocalAnketa,
    ILooseObject,
    IQuestion,
    IQuestionOption, IUtm
} from "../../../types/anketa";
import {eventBus} from "../../../bus";


const axios = Axios.create({
    baseURL: '/'
})


function emitRequestStarted(payload = ''){
    eventBus.$emit('emitRequestStarted',payload)
}

function emitSuccess(res){
    if(res.status >= 200 && res.status <= 299  ) {
        eventBus.$emit('actionSuccess','Сохранено 😋')
        return res.data
    }
}
function emitError(error) {
    console.log('error', error);
    eventBus.$emit('actionError', error.message)
}


@Module({
    namespaced: true
})

export default class StoreAnketa extends VuexModule {

    // public server: string = process.env.NODE_ENV === 'production' ? '' : 'http://localhost:8000'
    public server: string = '/'

    public finalSlug: string = 'coupon'

    // => VARS
    public questions: IQuestion[] = []
    public currentQuestion = {}

    public answers: IAnswers = {}
    public currentAnswer: IAnswer = {options: [], own: '', forms: []}

    public queue: number = 0
    public slug: string = ''

    public options: any[] = []
    public own: string = ''
    public forms: ILooseObject = {}

    public queuePaused: boolean = false
    public prewQuestion!: Number


    public email: string = ''
    public phone: string = ''

    public anketaId!: number


    public img_1: any = ''
    public img_2: any = ''
    public img_3: any = ''

    public anketaSlug!: string;


    // => IS_TEST
    public capsulaTest: boolean = false


    @Mutation setCapsulaTest(payload: boolean) {
        this.capsulaTest = payload
    }


    // => SLUG
    @Mutation setSlug(payload: string) {
        this.slug = payload
        this.answers['lastQuestionSlug'] = payload
    }

    @Mutation swapQuestions(questionId: number, is_back: boolean) {

        let index = this.questions.findIndex((i: IQuestion) => i.id === questionId)

        if ((Math.min(index, this.queue + 1) < 0) || (Math.max(index, this.queue + 1) >= this.questions.length)) {
            console.error('Out of range')
            return null;
        }
        const item = this.questions.splice(index, 1);
        this.questions.splice((this.queue + 1 > 0) ? this.queue + 1 : 0, 0, item[0])

        console.log('---' ,questionId , new Date().toISOString());


        // let tt = this.questions.map(i => {
        //     return `${i.slug} ${i.is_sub ? '--' : ''}`
        // })
        // console.log('to', tt, item[0].slug );

    }

    @Mutation swapQuestionsBack(questionId: number) {
        let index = this.questions.findIndex((i: IQuestion) => i.id === questionId)
        if ((Math.min(index, this.queue + 1) < 0) || (Math.max(index, this.queue + 1) >= this.questions.length)) {
            console.error('Out of range')
            return null;
        }
        const item = this.questions.splice(index, 1);

        this.questions.push(item[0])

        // let tt = this.questions.map(i => {
        //     return `${i.slug} ${i.is_sub ? '--' : ''}`
        // })
        // console.log('back', tt);
    }

    // => QUESTION QUEUE
    @Mutation setQueue(payload: number) { // MAIN FABRIC
        if (payload >= 0 && payload < this.questions.length) {

            this.queue = payload
            this.currentQuestion = this.questions[this.queue]
            this.slug = this.questions[this.queue].slug

            if (this.answers[this.slug]) {
                this.options = this.answers[this.slug].options
                this.own = this.answers[this.slug].own
                this.forms = this.answers[this.slug].forms
            } else {
                this.options = []
                this.own = ''
                this.forms = {}
            }

            this.currentAnswer = this.answers[this.slug]

        }
    }

    @Mutation setQueuePaused(payload: boolean) {
        this.queuePaused = payload
    }

    @Mutation setPrewQuestion(questionId: number) {
        this.prewQuestion = questionId
    }

    // => ANSWERS
    @Mutation setAnswer(payload: any) { // MAIN DECORATOR
        this.options = payload.options
        this.own = payload.own
        this.forms = payload.forms
        this.answers[this.slug] = payload
    }

    @Mutation setAnswers(payload: IAnswers) {
        this.answers = payload
    }


    get getForms() {
        return this.forms
    }


    // => QUESTIONS
    @Mutation setQuestions(payload: any) {
        this.questions = payload
    }

    public local: ILocalAnketa[] = []

    @Mutation setLocal(payload: any) {

        this.questions = payload
    }

    @MutationAction({mutate: ['answers', 'questions', 'local', 'uuid', 'queue', 'img_1','img_2','img_3', ]})
    async fetchQuestions(anketaSlug: string) {

        const local = localStorage.getItem('anketa')
        const urlSearchParams = new URLSearchParams(window.location.search);
        // @ts-ignore
        const urlQuery = Object.fromEntries(urlSearchParams.entries());
        const response = await axios.post('/questions',
            {
                func: 'initAnketa',
                anketaSlug: anketaSlug,
                local: local,
                urlQuery: urlQuery
            })

        if(await response.data) {
            let ls = localStorage.getItem('anketa')

            if(ls) {
                let lsData = JSON.parse(ls)
                let current = lsData.find(i => i.anketaSlug === anketaSlug)
                if(!current) { // нет записи в локал сторадже -> новая
                    localStorage.setItem('anketa', JSON.stringify(response.data.local))
                    console.log('repited');
                } else if(current.uuid !== response.data.uuid){ // произошло удаление анкеты из базы по какой либо причине
                    console.log('current changed');
                    current.uuid = response.data.uuid
                    localStorage.setItem('anketa', JSON.stringify(lsData))
                }
            } else  {
                localStorage.setItem('anketa', JSON.stringify(response.data.local))
            }

            return response.data

        }

    }


    // => QUESTION CURRENT
    @Mutation setCurrentQuestion() {
        if (this.questions.length >= 0 && this.queue < this.questions.length) {
            this.currentQuestion = this.questions[this.queue]
        }
    }


    // => PERSONAL DATA

    @Mutation setEmail(payload: string) {
        this.email = payload
    }

    get getEmail() {
        return this.email
    }

    @Mutation setPhone(payload: string) {
        this.phone = payload
    }

    get getPhone() {
        return this.phone
    }

    // => DELIVERY

    public city = {}
    public cities: any[] = []

    @Mutation setSities(payload: any[]) {
        this.cities = payload
    }

    @Action({commit: 'setSities'})
    async fetchSities(payload: string) {
        console.log('fetchSities', payload);
        const response = await axios.post('/questions', {func: 'getCities', cityLike: payload})
        return response.data
    }


    // => BOXBERRY
    public boxberry!: IBoxberry
    public boxberryLoaded: boolean = false

    @Mutation setBoxberry(payload: IBoxberry) {
        this.boxberry = payload
        this.boxberryLoaded = true
    }

    public boxberryCity: String = ''

    @Mutation setBoxberryCity(payload: String) {
        this.boxberryCity = payload
    }

    // DADATA
    public dadataQueryResultData!: any

    @Mutation setDadataQueryResultData(payload: any) {
        this.dadataQueryResultData = payload
    }

    get getDadataObject() {
        return this.dadataQueryResultData
    }

    // => CLIENT PHOTOS

    @Mutation setClientImage_1(payload: any) {
        this.img_1 = payload
    }

    @Mutation setClientImage_2(payload: any) {
        this.img_2 = payload
    }

    @Mutation setClientImage_3(payload: any) {
        this.img_3 = payload
    }

    @Mutation setClientImages(payload: any) {
        if (payload[0]) {
            this.img_1 = this.server + 'storage/' + payload[0]
        }
        if (payload[1]) {
            this.img_2 = this.server + 'storage/' + payload[1]
        }
        if (payload[2]) {
            this.img_3 = this.server + 'storage/' + payload[2]
        }

        this.forms = payload

    }

    get getImg_1() {
        return this.img_1
    }

    get getImg_2() {
        return this.img_2
    }

    get getImg_3() {
        return this.img_3
    }

    @Action({commit: 'setClientImages'})
    async saveClientPhotos() {
        await emitRequestStarted('Сохраняем фото')
        const response = await axios.post('/questions', {
            func: 'saveClientPhotos', anketaId: this.uuid,
            images: [this.img_1, this.img_2, this.img_3]
        })
        if (await response) {
            emitSuccess(response)
            return response.data
        } else  {
            emitError(response)
        }
        // return response.data
    }


    // COUPON

    public price: number = 0


    @Mutation setPrice(payload: number) {
        this.price = payload
    }

    get getPrice() {
        return this.price
    }



    //BONUS
    public bonus!: IBonus
    @Mutation setBonus(payload: IBonus){
        this.answers['rf'] = payload
            ? payload.promocode
            : false
        this.bonus = payload
    }
    @Action({commit: 'setBonus'})  async checkBonus(payload: string) {
        const response = await axios.post('/questions', {func: 'checkBonus', code: payload})
        return response.data
    }

    // COUPON
    public coupon!: ICoupon
    @Mutation setCoupon(payload: ICoupon) {
        this.price = payload.price
        this.coupon = payload
        this.answers['coupon'] = payload.name
    }
    @Action({commit: 'setCoupon'})  async checkCoupon(payload: String) {
        const response = await axios.post('/questions', {func: 'checkCoupon', code: payload})
        return response.data
    }

    public created!: any
    public updated!: any
    public uuid: string = ''


    @Mutation setUuid(payload: string) {
        this.uuid = payload
    }



    @Mutation setCreated(payload: any) {
        this.created = payload
        // this.uuid = payload.uuid

    }

    public leadUuid: string = ''
    @Mutation setleadUuid(payload: string) {this.leadUuid = payload}
    @Action({commit: 'setleadUuid'}) async fetchLeadUuid() {
        const responce = await axios.post('/questions', {func: 'fetchLeadUuid', uuid: this.uuid})
        return responce.data
    }

    @Action({commit: 'setCreated'})
    async saveAnswers() {
        await emitRequestStarted('Готовим следующий вопрос')
        this.answers['amount'] = this.price
        this.answers['coupon'] = this.coupon ? this.coupon.name : (this.bonus ? this.bonus.promocode : '')
        const urlSearchParams = new URLSearchParams(window.location.search);
        // @ts-ignore
        const urlQuery = Object.fromEntries(urlSearchParams.entries());
        const responce = await axios.post('/questions', {
            func: 'save',
            data: this.answers,
            uuid: this.uuid,
            capsulaTest: this.capsulaTest,
            urlQuery: urlQuery
        })

        if (await responce) {
            emitSuccess(responce)
            return responce.data
        } else  {
            emitError(responce)
        }


    }


    public clientIp!: string

    @Mutation setClientIP(payload: string) {
        this.clientIp = payload
    }

    @Action({commit: 'setClientIP'})
    async fetchClientIp() {
        const response = await axios.post('/questions', {func: 'fetchClientIp'})
        return response.data;
    }


    public yaTracks: string[] = []

    @Mutation setYatracks(payload: string){
        if(!this.yaTracks.includes(payload)){
            this.yaTracks.push(payload)
        }
    }


    // public utms: IUtm = {}
    //
    // @Mutation setUtm(payload: IUtm) {this.utms = payload}
    //
    // @MutationAction({ mutate: ['utm']}) async initialsCheckings(payload) {
    //     return await axios.post('/questions', {
    //         func: 'initialsCheckings',
    //         pa
    //     })
    // }


}
