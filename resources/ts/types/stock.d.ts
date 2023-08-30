export interface ICategories {
    id?: number
    parent_id?: ICategories
    name?: string
    content?: string
    slug?: string
    user_id?: number
    visible?: number
    created_at?: string
    updated_at?: string
    deleted_at?: string
}

export interface IBrands {
    id?: number
    name?: string
    created_at?: string
    updated_at?: string
    deleted_at?: string
}

export interface IPageProducts {
    current_page?: number;
    data?: IProducts[];
    per_page?: number;
    total?: number;
    last_page?: number;
}

export interface ICart {
    id?: number;
    expired_time?: string;
    lead_uuid?: string;
    state_id?: number;
    confirmed_at?: string;
    products?: IProducts[]
}

export interface IProducts {
    id?: number,
    category_id?: ICategories,
    brand_id?: IBrands,
    attributes?: IAttributes[],
    sku?: string,
    name?: string,
    isLiked?: boolean,
    img?: string,
    images?: any[],
    img_original?: string,
    material?: string,
    amo_name?: string,
    content?: string,
    quantity?: number,
    price?: number,
    slug?: string,
    color?: string,
    note?: string,
    user_id?: number
    visible?: number
    created_at?: String,
    updated_at?: String,
    deleted_at?: String,
    external_id?: string,
    size?: string,
}

export interface ITablesStock {
    id?: Number
    title?: IProducts.name
    product_info?: object
    pivot?:Object

}

export interface IAttributes {
    id?: number,
    name?: string,
    description?: string,
    pivot?: IPivot
    values?: string[]
}

export interface IPivot {
    attribute_id?: number,
    product_id?: number,
    value?: string
}


