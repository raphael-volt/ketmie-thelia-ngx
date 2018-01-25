import { Injectable } from '@angular/core';
import { ApiService } from "./api.service";
@Injectable()
export class ImgLoaderService {

  constructor(private api: ApiService) { }

  loadList(imgs: HTMLImageElement[], urls: string[]) {

  }

}
