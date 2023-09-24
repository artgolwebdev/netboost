import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

// my pages 
import { TableComponent } from './table/table.component';
import { ItemComponent } from './item/item.component';
const routes: Routes = [
  { path : '' , component:TableComponent},
  { path : 'item/:id' , component:ItemComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
