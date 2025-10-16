import { Component, OnInit, computed, effect, signal } from '@angular/core';
import { MatFormFieldModule } from '@angular/material/form-field';
import { RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
// Removed import of MatFormFieldModule as it cannot be found
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { Customer } from './customer.model';
import { CustomerService } from './customer.service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    RouterOutlet,
    CommonModule,
    FormsModule,
    HttpClientModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatCardModule
  ],
  templateUrl: './app.html',
  styleUrls: ['./app.css']
})
export class App implements OnInit {
  protected readonly title = signal('Kunden');
  protected readonly searchTerm = signal<string>('');
  protected readonly customers = signal<Customer[]>([]);
  protected readonly currentPage = signal<number>(1);
  protected readonly pageSize = 6;

  protected readonly filtered = computed(() => {
    const term = this.searchTerm().trim().toLowerCase();
    if (!term) return this.customers();
    return this.customers().filter(c =>
      c.name.toLowerCase().includes(term) ||
      c.email.toLowerCase().includes(term) ||
      c.company.toLowerCase().includes(term)
    );
  });

  protected readonly totalPages = computed(() => Math.max(1, Math.ceil(this.filtered().length / this.pageSize)));
  protected readonly pageItems = computed(() => {
    const page = this.currentPage();
    const start = (page - 1) * this.pageSize;
    return this.filtered().slice(start, start + this.pageSize);
  });

  constructor(private readonly customerService: CustomerService) {
    effect(() => {
      // Reset to first page when search changes
      this.searchTerm();
      this.currentPage.set(1);
    });
  }

  ngOnInit(): void {
    this.loadCustomers();
  }

  protected loadCustomers(): void {
    this.customerService.getCustomers(this.searchTerm()).subscribe(list => {
      this.customers.set(list);
    });
  }

  protected onSearch(): void {
    this.loadCustomers();
  }

  protected toggleFavorite(c: Customer): void {
    const action$ = c.favorite ? this.customerService.removeFavorite(c.id) : this.customerService.markFavorite(c.id);
    action$.subscribe(updated => {
      const next = this.customers().map(item => (item.id === updated.id ? updated : item));
      this.customers.set(next);
    });
  }

  protected goto(page: number): void {
    if (page < 1 || page > this.totalPages()) return;
    this.currentPage.set(page);
  }
}