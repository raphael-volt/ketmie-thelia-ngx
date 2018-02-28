import { Component, OnInit } from '@angular/core';
import { SliderBaseComponent } from '../slider-base.component';
import { DeclinationService, CardService } from '@ngnx/thelia/api';
import { serializableCardItem, Card, CardItem, IDeclination, IDeclinationItem } from '@ngnx/thelia/model';
export type CellType = string & 'image' | 'quantity' | 'options' | 'product' | 'price' | 'action' | 'none';
type TypeMap<K extends string, T> = { [P in K]?: T };
type TemplateMap = TypeMap<CellType, string>;

const templateMap = (): TemplateMap => {
  const v: CellType[] = ['none', 'image', 'product', 'quantity', 'options', 'price', 'action'];
  let res: TemplateMap = {};
  let i = 0;
  for (const t of v) {
    res[t] = String(i++);
  }
  return res;
};

export interface ITableDataColumn<T> {
  label: string;
  index: number;
  property: string;
  flex?: string;
  type?: CellType;
}

export interface ITableData<T> {
  columns: ITableDataColumn<T>[];
  rows: T[];
}

export class TableData<T> implements ITableData<T> {
  columns: ITableDataColumn<T>[] = [];
  rows = [];

  addColumn<K extends keyof T>(label: string, property: K, type: CellType, flex?: string): ITableDataColumn<T> {
    const col = {
      label: label,
      index: this.columns.length,
      property: property,
      flex: flex,
      type: type
    };
    this.columns.push(col);
    return col;
  }
}
const getTableDataProvider = () => {
  const data = new TableData<CardItem>();
  data.addColumn('-', 'decliId', 'image', '0');
  data.addColumn('Produit', 'product', 'product', '0');
  data.addColumn('Options', 'decliId', 'options', '0');
  data.addColumn('Qantité', 'quantity', 'quantity', '0');
  data.addColumn('Prix', 'product', 'price', '0');
  data.addColumn('-', 'product', 'none', '1');
  data.addColumn('-', 'product', 'action', '0');
  return data;
};

@Component({
  selector: 'card',
  templateUrl: './card.component.html',
  styleUrls: ['./card.component.scss']
})
export class CardComponent extends SliderBaseComponent implements OnInit {
  tableData: TableData<CardItem>;
  constructor(private decli: DeclinationService, public service: CardService) {
    super();
  }

  cellTypes: TemplateMap = templateMap();
  card: Card;

  ngOnInit() {
    this.card = this.service.card;
    this.tableData = getTableDataProvider();
    this.tableData.rows = this.card.items;
    this.slideIn();
  }

  quantityChange(item: CardItem, value: number) {
    value = Number(value);
    let p = serializableCardItem(item);
    p.quantity = value;
    this.service.update(p, result => {
      item.quantity = value;
    });
  }
  declinationChange(item: CardItem, value: IDeclinationItem) {
    let p = serializableCardItem(item);
    p.decliId = value.id;
    this.service.update(p, result => {
      item.decliId = p.decliId;
    });
  }
}
