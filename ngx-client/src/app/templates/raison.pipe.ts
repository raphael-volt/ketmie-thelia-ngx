import { Pipe, PipeTransform } from '@angular/core';
const raisonsMap: { [raison: string]: { short: string, long: string } } = {
  "1": { short: "Mme", long: "Madame" },
  "2": { short: "Mlle", long: "Mademoiselle" },
  "3": { short: "M", long: "Monsieur" }
}
export {raisonsMap}
@Pipe({
  name: 'raison'
})
export class RaisonPipe implements PipeTransform {

  transform(value: any, display?: "short"|"long"): any {
    if (display != "short" && display != "long")
      display = "short"
    if (!raisonsMap[value])
      return null
    return raisonsMap[value][display];
  }
}