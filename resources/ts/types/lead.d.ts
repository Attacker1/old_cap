import {IClient} from "./clients";
import {IQuestionnaire} from "./questionnaire";

export interface ILead {
    uuid: string
    client_id: string
    stylist_id: number
    amo_lead_id: number
    anketa_id: number
    anketa_uuid: string
    total: number
    summ: number
    discount: number
    state: number
    state_logistic: number
    client_num: number
    amo_link_contact_id: number
    deadline_at: string
    delivery_at: string
    state_id: number
    substate_id: number
    description: string
    tag: string
    data: ILeadDataField
    create_type: string
    source: string
    lead_client: IClient
    lead_state: ILedState
    lead_tag: ILeadTag
    has_delivery: IDelivery
    questionnaire: IQuestionnaire

    // deleted_at :
    created_at: string
    // updated_at :
}

export interface ILeadDataField {
    city_delivery: string
    coupon: string
    date_delivery: string
}

export interface ILeadPaginate {
    current_page?: number
    data?: ILead[]
    first_page_url?: string
    from?: number
    last_page?: number
    last_page_url?: string
    next_page_url?: string
    path?: string
    per_page?: number
    prev_page_url?: string
    to?: number
    total?: number
}

export interface ILeadTag {
    id: number
    name: string
    color: string
    type: string
    created_at: string
    updated_at: string
}

export interface ILedState {
    id: number
    name: string
    parent_id: number
    created_at: string
    updated_at: string
    deleted_at: string
}

export interface IDelivery {
    id: number
    lead_id:string
    source:string
    delivery_id:string
    delivery_point_id:string
    delivery_address:string
    state:string
    created_at:string
    updated_at:string
    deleted_at:string
    arrived_at:string
}
