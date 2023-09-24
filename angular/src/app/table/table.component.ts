import { Component } from '@angular/core';
import { LaravelService } from '../laravel.service';
import { NgForm } from '@angular/forms';
@Component({
  selector: 'app-table',
  templateUrl: './table.component.html',
  styleUrls: ['./table.component.css']
})
export class TableComponent {
  constructor(private laravel : LaravelService) { }

  depth:number = 1; 
  items:any =[];
  isLoading = true;

  ngOnInit(): void {
    this.isLoading = false;
    this.fetchDataFromServer();
    console.log(this.depth)
  }

  onFetchData(form:NgForm)
  {
    this.isLoading = true;
    this.laravel.crawlData(form.value.url,form.value.depth)
    .subscribe((response:any)=>{
      alert(response.Message);
      this.isLoading = false;
      this.fetchDataFromServer()
      console.log(response);
    })
  }

  fetchDataFromServer()
  {
    this.laravel.getData().subscribe(data => {
      console.log(data);
      this.items = data;
    })
  }
}
