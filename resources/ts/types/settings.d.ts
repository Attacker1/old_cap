// export interface ISideMenu {
//     id: number
//     sort: number
//     active: number
//     title: string
//     parent: number
//     icon: string
//     h_icon: string
//     page: string
//     children: ISideMenu[]
//     route_name: string
//     user_rights: string
// }

export interface ISideMenu {
    title: string
    key: string
    icon: string
    path: string
    href: string
    children: ISideMenu[]
}

export interface IUser {
    id: number
    name: string
    role_id: number
    role_name: string
}

export interface IUsersRoles {
    id: number
    name: string
    slug: string
}

export interface IUsersPermissions {
    id: number
    name: string
    slug: string
}

export interface IPagination {
    page?: number,
    size?: number,
    total?: number
}

export interface IUsersByRoles {
    stylist: IUser[]
}

export interface IEmitResponse {
    url: string
    payload: any
}

export interface ISettingsInit {
    namespace: any
    role?: number
}

export interface IManageBlock {
    id: number
    namespace: string
    slug: string
    title:string
    type:string
    roles:number[]
    items:IManageBlockItem[]
    tooltip:string
    sort:number
}

export interface IManageBlockItem {
    id?:number
    key: string
    sort: number
    title: string
    input_type: string,
    input_value: any,
    input_mask?: string,
    input_disabled?: boolean,
    input_rules: IFormItemRules,
    input_list?: {id: any, name: string}[]
    input_list_null_option?: boolean
    tooltip?: string
    roles: number[]
    position?: string
    view_class: ? string
    view_type: ? string
    sortable?: boolean

}

export interface IFormItem {
    id?:number
    key: string
    sort?: number
    title: string
    input_type: string,
    input_value: any,
    input_mask?: string,
    input_disabled?: boolean,
    input_rules?: IFormItemRules,
    input_list?: {id: any, name: string}[]
    input_list_null_option?: boolean
    tooltip?: string
    legend?: string

}
export interface IFormRules {
    required: string[]
    rules: {}
}
export interface IFormItemRules {
    required?: boolean
    int?: boolean
    number?: boolean
    url?: boolean
    email?: boolean
    tel?: boolean
    mobile?: boolean
    textarea?: {
        maxLen: number,
        minLen: number
    },
    custom?: any
}

export interface IPermissionsFieldsData {
    namespace: string
    items
}
