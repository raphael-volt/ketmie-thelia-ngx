export class LoaderEvent {
    constructor(
        public target?: HTMLImageElement, 
        public loaded: number = NaN, 
        public total: number = NaN,
        public complete: boolean = false
    ) {}
}