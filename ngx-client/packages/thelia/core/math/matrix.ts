export class SimpleMatrix {
    constructor(
    public s: number = 1,
    public tx: number = 0,
    public ty: number = 0) {}

    getCssTransform(unity: string="px"): string {
        return `scale(${this.s},${this.s}) translate(${this.tx}${unity},${this.ty}${unity})`
        //return `translate(${this.tx}${unity},${this.ty}${unity}) scale(${this.s},${this.s})`
    }
}