import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router'
import { LaravelService } from '../laravel.service';

@Component({
  selector: 'app-item',
  templateUrl: './item.component.html',
  styleUrls: ['./item.component.css']
})
export class ItemComponent {

  item : any = [];

  constructor(
    private route: ActivatedRoute,
    private laravel : LaravelService
  ) {

  }


  ngOnInit(): void {
    this.route.paramMap.subscribe((params) => {
      const id = params.get('id');
      //console.log(id)
      this.laravel.getData(id).subscribe(data => {
        console.log(data);
        this.item = data;
      })
    });
  }

  
}
