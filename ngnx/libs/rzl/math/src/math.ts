export class MathCore {}
const interpolate = (t: number, from: number, to: number): number => {
  return from + (to - from) * t;
};
export { interpolate };
