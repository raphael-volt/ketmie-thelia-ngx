export interface APIResponse {
    success: boolean
    body: any
}

export interface APIResponseError {
    code: number
    message: string
}

export interface APISession {
    session_id: string
}


export interface ShopTree {
    shopCategories: Category[]
    cmsContents: CMSContent[]
}

export interface IIdentifiable {
    id?: string
}

export interface ILabelable extends IIdentifiable {
    label: string
}

export interface IDescriptable extends ILabelable {
    description?: string
}

export interface Product extends IDescriptable {

}
export interface ProductDeclination extends IIdentifiable {
    size: string
    price: string
}
export interface ProductDetail extends Product {
    images: string[]
    declinations: ProductDeclination[]
    price: string
    index: string
    ref: string
}

export interface Category extends IDescriptable {
    content: boolean
    children: Product[]
}

export interface CMSContent extends IDescriptable {

}

export interface Customer extends IIdentifiable {
    ref?: string
    datecrea?: string
    raison?: string
    entreprise?: string
    siret?: string
    intracom?: string
    nom?: string
    prenom?: string
    adresse1?: string
    adresse2?: string
    adresse3?: string
    cpostal?: string
    ville?: string
    pays?: string
    telfixe?: string
    telport?: string
    email?: string
    motdepasse?: string
    parrain?: string
    type?: string
    pourcentage?: string
    lang?: string
    loggedIn?: boolean
    isNew?: boolean
}

export interface Address {
    id?: string
    libelle?: string
    client?: string
    raison?: string
    entreprise?: string
    nom?: string
    prenom?: string
    adresse1?: string
    adresse2?: string
    adresse3?: string
    cpostal?: string
    ville?: string
    tel?: string
    pays?: string
}

export interface Country {
    id: string
    name: string
}

const isAPIResponseError = (value: any): value is APIResponseError => {
    if (value && value.code && value.message)
        return true
    return false
}
const addressProperties: string[] = ["raison", "entreprise", "nom", "prenom",
    "adresse1", "adresse2", "adresse3", "cpostal", "ville", "pays"]

const customerToAddress = (customer: Customer): Address => {
    const result: Address = {}
    for (const p of addressProperties) {
        if (!customer[p])
            continue
        result[p] = customer[p]
    }
    if (customer.telfixe) {
        result.tel = customer.telfixe
    }
    if (customer.telport) {
        result.tel = customer.telport
    }
    return result
}
const addressToCustomer = (address: Address, customer?: Customer): Customer => {
    if (!customer)
        customer = {}
    for (const p of addressProperties) {
        if (!address[p])
            continue
        customer[p] = address[p]
    }
    if (address.tel) {
        if (!customer.telfixe)
            customer.telfixe = address.tel

        if (!customer.telport)
            customer.telport = address.tel
    }
    return customer
}

const FR_ID: string = "64"
/*
var $declinaison;
        var $valeur;
*/
export interface CardItemPerso {
    declinaison: string
    valeur?: string
}

export interface CardItem {
    index?: number
    productId?: string
    decliId?: string
    quantity?: number
    parent?: string
    perso?: CardItemPerso[]
}

export interface ICard {
    items: CardItem[]
    total: number
}

export class Card {
    constructor(json?: ICard) {
        if (json)
            this.update(json)
    }
    items: CardItem[] = []
    total: number = 0
    update(json: ICard) {
        if (json.items != undefined)
            this.items = json.items
        this.total = Number(json.total)
    }
    
    get numItems(): number {
        return this.items.length
    }

    get numArticles(): number {
        let n = 0
        this.items.forEach(value=>{n+=(value.quantity)})
        return n
    }
}
export { isAPIResponseError, customerToAddress, addressToCustomer, FR_ID }