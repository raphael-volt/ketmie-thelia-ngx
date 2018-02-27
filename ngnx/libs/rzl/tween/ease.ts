
type EaseFunction = (time: number, start: number, end: number, duration: number) => number;

export class Linear {
  static in(t: number, b: number, c: number, d: number): number {
    return c * t / d + b;
  }

  static out(t: number, b: number, c: number, d: number): number {
    return Linear.in(t, b, c, d);
  }

  static inOut(t: number, b: number, c: number, d: number): number {
    return Linear.in(t, b, c, d);
  }
}

export class Sine {
  static in(t: number, b: number, c: number, d: number): number {
    return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;
  }

  static out(t: number, b: number, c: number, d: number): number {
    return c * Math.sin(t / d * (Math.PI / 2)) + b;
  }

  static inOut(t: number, b: number, c: number, d: number): number {
    return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
  }
}

export class Quintic {
  static in(t: number, b: number, c: number, d: number): number {
    return c * (t /= d) * t * t * t * t + b;
  }

  static out(t: number, b: number, c: number, d: number): number {
    return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
  }

  static inOut(t: number, b: number, c: number, d: number): number {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;

    return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
  }
}

export class Quartic {
  static in(t: number, b: number, c: number, d: number): number {
    return c * (t /= d) * t * t * t + b;
  }

  static out(t: number, b: number, c: number, d: number): number {
    return -c * ((t = t / d - 1) * t * t * t - 1) + b;
  }

  static inOut(t: number, b: number, c: number, d: number): number {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;

    return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
  }
}

export class Quadratic {
  static in(t: number, b: number, c: number, d: number): number {
    return c * (t /= d) * t + b;
  }

  static out(t: number, b: number, c: number, d: number): number {
    return -c * (t /= d) * (t - 2) + b;
  }

  static inOut(t: number, b: number, c: number, d: number): number {
    if ((t /= d / 2) < 1) return c / 2 * t * t + b;

    return -c / 2 * (--t * (t - 2) - 1) + b;
  }
}

export { EaseFunction };
