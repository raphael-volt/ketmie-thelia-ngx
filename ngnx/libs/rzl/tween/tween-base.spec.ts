import { async } from '@angular/core/testing';
import { Sine } from './ease';
import { TweenBase, TweenData, TweenChangeEvent } from './tween-base';
import { Subscription } from "rxjs";

const nearEq = (a, b, p = 40) => p >= Math.abs(a - b)

const td = {
  start: 0,
  end: 100,
  ease: Sine.in,
  duration: 500
}
const th = {
  start: false,
  finish: false,
  change: 0
}
const resetTh = () => {
  th.start = th.finish = false
  th.change = 0
}
describe('TweenBase', () => {

  it('should run tween', async(() => {
    const d = th
    let t = new TweenBase()
    t.data = td
    let s = t.events.subscribe(e => {
      expect(t).not.toBeNull()
      d.change++
      if (e.type == "start") {
        expect(d.start).toBeFalsy()
        expect(d.change).toEqual(1)
        expect(d.finish).toBeFalsy()
        d.start = true
      }
      else if (e.type == "change") {
        expect(d.start).toBeTruthy()
        expect(d.change).toBeGreaterThanOrEqual(2)
        expect(d.finish).toBeFalsy()
      }
      else if (e.type == "finish") {
        expect(d.start).toBeTruthy()
        expect(d.change).toBeGreaterThanOrEqual(2)
        expect(d.finish).toBeFalsy()
        d.finish = true
        t = null
        s.unsubscribe()
        setTimeout(() => {
          expect(true).toBeTruthy()
        }, 600);
      }
    })

    t.start()
  }));

  it('should pause and resume', async(() => {
    let t = new TweenBase(td)
    let tce: TweenChangeEvent
    const n = Date.now()
    let s = t.events.subscribe(e => {
      tce = e
      if (e.type == "finish") {
        expect(nearEq(t.elapsed, t.data.duration)).toBeTruthy()
      }
    })
    t.start()
    setTimeout(() => {
      t.pause()
      expect(nearEq(t.elapsed, 100)).toBeTruthy()
      setTimeout(() => {
        expect(nearEq(t.elapsed, 100)).toBeTruthy()
        t.resume()
        setTimeout(() => {
          expect(nearEq(t.elapsed, 200)).toBeTruthy()
        }, 100);
      }, 100);
    }, 100);
  }))
})
