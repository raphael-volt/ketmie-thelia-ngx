import { Injectable } from '@angular/core';
import { LayoutService, LayoutSizes } from "../layout.service";
import { px } from "../shared/utils/css-utils";

@Injectable()
export class ImgTileService {

  constructor(private layoutService: LayoutService) {
    layoutService.layoutChange.subscribe(this.resizeHandler)
  }

  private tiles: TileData[] = []
  private ids: string[] = []

  create(id: string, parent: HTMLElement, images: HTMLImageElement[]) {
    const td: TileData = { parent: parent, images: images }
    this.ids.push(id)
    this.tiles.push(td)
    this.updateTile(td, this.layoutService.layout)
  }

  destroy(id) {
    const i: number = this.ids.indexOf(id)
    if (i > -1) {
      this.ids.splice(i, 1)
      this.tiles.splice(i, 1)
    }
    else {
      console.log("ImgTileService.destroy", id + " not found")
    }
  }

  private resizeHandler = (layout: LayoutSizes) => {
    for (let i = 0; i < this.ids.length; i++) {
      this.updateTile(this.tiles[i], layout)
    }
  }
  updateTile(tile: TileData, layout: LayoutSizes) {
    const _contentWidth: number = tile.parent.offsetWidth
    const _initHeight: number = tile.parent.clientHeight

    let _maxHeight: number = Number.MAX_SAFE_INTEGER
    let rects: RectHelper[] = tile.images.map(img => {
      if (img.naturalHeight < _maxHeight)
        _maxHeight = img.naturalHeight
      return new RectHelper(img, 0, 0, img.naturalWidth, img.naturalHeight)
    })
    let maxY: number = 0
    const _hGap: number = 10
    const _vGap: number = 10
    let i: number = 0
    let r: RectHelper
    const numItems = rects.length
    const lastRect = rects[rects.length-1]
    let s: number
    let x: number = 0
    let y: number = 0
    let rectRows: RectHelper[][] = [
      []
    ]
    let rowIndex: number = 0
    let maxX: number = 0
    for (i = 0; i < numItems; i++) {
      r = rects[i]
      r.x = x
      r.y = y
      r.width = RectHelper.widthForHeight(r, _maxHeight)
      r.height = _maxHeight
      x += r.width
      rectRows[rowIndex].push(r)
      if (x >= _contentWidth || i == numItems - 1) {
        let rl = rectRows[rowIndex].length
        let ws: number
        if(rl > 1) {
          ws = _contentWidth - (rl-1) * _hGap
        }
        else
          ws = _contentWidth
        x = 0
        let j
        for (j = 0; j < rl; j++) {
          x += rectRows[rowIndex][j].width
        }
        s = ws / x
        if (s > 1)
          s = 1
        
        let imgS: number
        x = 0
        for (j = 0; j < rl; j++) {
          r = rectRows[rowIndex][j]
          imgS   = (s * _maxHeight) / r.naturalHeight
          this.setTransform(r.img, x, y, imgS)
          x += r.naturalWidth * imgS
          if (maxX < x)
            maxX = x
          x += _hGap
        }
        x = 0
        y += s * _maxHeight + _vGap

        rowIndex++
        rectRows[rowIndex] = []
      }
    }
    y -= _vGap
    this.updateHeight(tile.parent, y)
    if (y > layout[1] && _initHeight < layout[1]) {
      window.requestAnimationFrame(() => {
        this.updateTile(tile, layout)
      })
    }
  }
  _updateTile(tile: TileData, layout: LayoutSizes) {
    const _contentWidth: number = tile.parent.clientWidth
    const _initHeight: number = tile.parent.clientHeight

    let item: RectHelper
    let _maxHeight: number = Number.MAX_SAFE_INTEGER
    let items: RectHelper[] = tile.images.map(img => {
      if (img.naturalHeight < _maxHeight)
        _maxHeight = img.naturalHeight
      return new RectHelper(img, 0, 0, img.naturalWidth, img.naturalHeight)
    })
    let maxY: number = 0
    const _hGap: number = 10
    const _vGap: number = 10
    let _hSpace: number = 10

    let row: { row: RectHelper, items: RectHelper[], complete: boolean } = { row: new RectHelper(null, 0, 0, 0, _maxHeight), items: [], complete: false }
    let rows: { row: RectHelper, items: RectHelper[], complete: boolean }[] = [row]
    let r: RectHelper
    let i: number = 0
    let iw: number
    let hg: number
    let lastItem = items[items.length - 1]
    for (item of items) {

      iw = RectHelper.widthForHeight(item, _maxHeight)
      r = row.row
      hg = (row.items.length) * _hGap
      _hSpace = _contentWidth - r.width - hg - iw
      if (_hSpace >= 0) {
        r.width += iw
        row.items.push(item)

      }
      else {
        row.complete = true
        row = { row: new RectHelper(null, 0, 0, iw, _maxHeight), items: [item], complete: false }
        rows.push(row)
      }
      if (item == lastItem)
        row.complete = true

    }
    if (!rows.length) {
      this.updateHeight(tile.parent, maxY)
      return []
    }

    let s: number
    let n: number = 0
    let mh: number = 0
    let x: number
    let y: number = 0
    let rects: RectHelper[] = []
    for (row of rows) {

      if (!row.complete) {
        for (item of row.items) {
          rects.push(item)
        }
        continue
      }
      else {
        rects.push(item)
      }

      r = row.row
      hg = (row.items.length - 1) * _hGap
      _hSpace = _contentWidth - hg
      s = _hSpace / r.width
      r.height = s * _maxHeight
      mh += r.height
      n++
    }
    if (n == 0)
      mh = _maxHeight
    else
      mh = mh / n
    y = 0
    x = 0
    for (row of rows) {
      r = row.row
      if (!row.complete) {
        r.height = mh
      }
      x = 0
      r.y = y
      for (item of row.items) {
        s = r.height / item.height
        this.setTransform(item.img, x, y, s)
        item.width = item.width * s
        item.height = r.height
        x += item.width + _hGap
      }
      y += r.height + _vGap
    }

    maxY = row.row.y + row.row.height

    this.updateHeight(tile.parent, maxY)

    if (maxY > layout[1] && _initHeight < layout[1]) {
      window.requestAnimationFrame(() => {
        this.updateTile(tile, layout)
      })
    }
  }
  private windowFlag: boolean = false

  private updateHeight(parent: HTMLElement, maxY) {
    parent.style.height = px(maxY)
  }
  private updateWidth(parent: HTMLElement, maxY) {
    parent.style.width = px(maxY)
  }
  private setTransform(img: HTMLImageElement, x: number, y: number, scale: number) {
    img.style.transformOrigin = "0% 0%"
    img.style.transform = `translate(${x}px,${y}px) scale(${scale},${scale})`
  }
}

interface TileData {
  parent: HTMLElement
  images: HTMLImageElement[]
}

class RectHelper {

  static widthForHeight(item: RectHelper, height: number): number {
    return item.width * height / item.height
  }

  get naturalWidth(): number {
    return this.img.naturalWidth
  }
  get naturalHeight(): number {
    return this.img.naturalHeight
  }

  constructor(
    public img: HTMLImageElement,
    public x: number,
    public y: number,
    public width: number,
    public height: number
  ) {
  }
}