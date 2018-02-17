export interface APIResponse {
    success: boolean
    body: any
}

export interface APIResponseError {
    code: number
    message: string
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
}

const isAPIResponseError = (value: any): value is APIResponseError => {
    if (value && value.code && value.message)
        return true
    return false
}

export { isAPIResponseError }