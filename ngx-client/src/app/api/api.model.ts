export interface ShopTree {
    shopCategories: Category[]
    cmsContents: CMSContent[]
}

export interface IIdentifiable { 
    id: string
}

export interface ILabelable extends IIdentifiable { 
    label: string
}

export interface IDescriptable extends ILabelable {
    description?: string
}

export interface Product extends IDescriptable {

}

export interface Category extends IDescriptable {
    content: boolean
    children: Product[]
}

export interface CMSContent extends IDescriptable{
    
}

