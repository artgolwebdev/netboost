import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class LaravelService {
  target = "http://127.0.0.1:8000/api";

  constructor(private http : HttpClient) { }

  getData(id:any=null){
    let endpoint = (id)?this.target+'/items/'+id:this.target+'/items/';
    return this.http.get<any>(endpoint);
  }

  crawlData(target:any,depth:number){
    return this.http.post(this.target+'/fetch',{
      target : target , depth :depth 
    });
  }
}
