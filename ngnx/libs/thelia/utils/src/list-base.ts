export type IListImpl<T> = {
  items: T[];
  length: number;
  add(t: T): number;
  remove(t: T): number;
  at(index: number): T;
  index(t: T): number;
};

export class ListBase<T> implements IListImpl<T> {
  constructor(source?: T[]) {
    if (source) this.items = source;
  }

  items: T[] = [];
  get length(): number {
    return this.items.length;
  }
  at(index: number): T {
    if (index >= 0 && index < this.length) return this.items[index];
    return null;
  }
  index(item: T): number {
    return this.items.indexOf(item);
  }
  add(item: T): number {
    if (this.items.indexOf(item) < 0) {
      this.items.push(item);
      return this.length;
    }
    return -1;
  }
  remove(item: T): number {
    const i = this.items.indexOf(item);
    if (i > -1) {
      this.items.splice(i, 1);
    }
    return i;
  }
}
