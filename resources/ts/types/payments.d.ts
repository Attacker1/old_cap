export interface ICloudPaymentResponse {
    Model: ICloudPaymentModel
    Success: boolean
    Message: string
}

export interface ICloudPaymentModel {
    // TransactionId: number
    PaReq : string
    GoReq: string
    AcsUrl: string
    ThreeDsSessionData : string
    IFrameIsAllowed: boolean
    FrameWidth: any
    FrameHeight: any
    ThreeDsCallbackId: string
    // EscrowAccumulationId: any
}

export interface ICloudPaymentModel {
    ReasonCode: number
    PublicId: string
    TerminalUrl: string
    TransactionId: number
    Amount: number
    Currency: string
    CurrencyCode: number
    PaymentAmount: number
    PaymentCurrency: string
    PaymentCurrencyCode: number
    InvoiceId: number
    AccountId: string
    Email: string
    Description: string
    JsonData: string
    CreatedDate: string
    PayoutDate: string
    PayoutDateIso: string
    PayoutAmount: string
    CreatedDateIso: string
    AuthDate: string
    AuthDateIso: string
    ConfirmDate: string
    ConfirmDateIso: string
    AuthCode: string
    TestMode: boolean
    Rrn: any
    OriginalTransactionId: any
    FallBackScenarioDeclinedTransactionId: any
    IpAddress: string
    IpCountry: string
    IpCity: string
    IpRegion: string
    IpDistrict: string
    IpLatitude: number
    IpLongitude: number
    CardFirstSix: number
    CardLastFour: number
    CardExpDate: string
    CardType: string
    CardProduct: string
    CardCategory: string
    EscrowAccumulationId: string
    IssuerBankCountry: string
    Issuer: string
    CardTypeCode: number
    Status: string
    StatusCode: number
    CultureName: string
    Reason: string
    CardHolderMessage: string
    Type: number
    Refunded: false
    Name: string
    Token: string
    SubscriptionId: any
    GatewayName: string
    ApplePay: boolean
    AndroidPay: boolean
    WalletType: any
    TotalFee: number
}


export interface IPaymentRow {
    id: number
    lead_id: string
    amount: number
    paid_at: string
    pay_for: string
    payload: string
    payment_id: number
    source: string
}
