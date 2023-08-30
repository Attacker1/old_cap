export interface IAnketaBuilder {
    id?: number
    title?: string
    slug?: string
    is_current?: number
    questions?: number[]
    created_at?: string
    updated_at?: string
    deleted_at?: string
}

export interface IQuestion {
    id: Number,
    is_sub: Number,
    active: number,
    slug: string,
    sort: Number,
    type_id: Number,
    multiple: Number,
    style_image: String,
    question: String,
    appeal: String,
    placeholder: String,
    disclaimer: String,
    own_answer: boolean,
    own_type: boolean,
    own_placeholder: String,
    labelC: String,
    ya_track: string,
    fb_track: string,
    old_key: Number,
    created_at: String,
    updated_at: String,
    deleted_at: String,
    type: IQuestionType,
    save: number,
    options: IQuestionOption[]
    tables: ITables[],
    recursive_options: IQuestionOption[]
}

export interface IQuestionType {
    id: Number,
    title: String
    type: String,
    sample: String,
    created_at: String,
    updated_at: String,
    deleted_at: String,
}

export interface IQuestionOption {
    id: Number,
    question_id: Number,
    active: Number,
    sort: Number,
    group_id: Number,
    slug: string,
    text: String,
    type: string,
    related_to: string,
    placeholder: String,
    text_thumb: Number,
    next_question: Number,
    emoji: String,
    image: String,
    position_top: Number,
    position_left: Number,
    pallete: String,
    option_key: Number,
    required: Number,
    old_key: Number,
    select_all: Number,
    unselect_all: Number,
    checked: Number,
    default_value: Number,
    recursive_question: IQuestion,
    error_text: String,

    created_at: String,
    updated_at: String,
    deleted_at: String
}






export interface IAnswers {
    [key: string]: any
}

export interface ILooseObject {
    [key: string]: any
}

export interface IKeyValue {
    key: any,
    value: any
}


export interface IAnsewrs {
    [key: string]: IAnswer
}
export interface IAnswer {
    options : ILooseObject,
    own : string,
    forms: ILooseObject
}

export interface ITables {
    id: Number
    options_prints: IQuestionOption[]
    pivot:Object
    title: String
}

export interface IDadataAddress {
    suggestions : IDadataAddressItem[]
}
export interface IDadataAddressItem {
    value : any
}

export interface IBoxberry {
    address: String,
    id: String
    loadlimit: String
    name: String
    period: String
    phone: String
    prepaid: String
    price: String
    workschedule: String
    zip: String
}

export interface ICoupon {
    id: number
    name: String
    price: number
    type: String
}

export interface IBonus {
    id: number
    client_id: string
    points: number
    promocode: string
}

export interface IUpdateLists {
    curentVariantSlug: string | undefined
    anketaList: IQuestion[]
}

export interface ILocalAnketa {
    anketaSlug: string
    uuid: string
}

export interface IPayment {
    id: number
    lead_id: string
    amount: number
    paid_at: string
    pay_for: string
    payload: string
    payment_id: number
    source: string
}

export interface IUtm {
    amo_id?: number
    lead_uuid?: string
    utm_source?: string
    utm_medium?: string
    utm_campaign?: string
    utm_content?: string
    utm_term?: string

}

export interface IAnketaProps {
    backend : {
        payment : {
            cloudPaymentPublicId: string
            cloudPaymentResultPage: string
            tinkoffTerminalKey: string
        }
    }

}


// converter
export interface IQuestDataVariant {
    label: string
    type: string
    option?: IQuestDataVariantOption[]
    image?: string

}

export interface IQuestDataVariantOption {
    text?: string

}
