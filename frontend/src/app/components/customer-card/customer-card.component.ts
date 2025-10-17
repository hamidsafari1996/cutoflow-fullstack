import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Customer } from '../../models/customer.model';

@Component({
  selector: 'app-customer-card',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './customer-card.component.html',
  styleUrls: ['./customer-card.component.css']
})
export class CustomerCardComponent {
  @Input({ required: true }) customer!: Customer;
  @Output() toggleFavorite = new EventEmitter<Customer>();

  onToggle(): void {
    this.toggleFavorite.emit(this.customer);
  }
}


