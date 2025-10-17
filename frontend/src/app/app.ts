import { Component, OnInit, computed, effect, signal } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { Customer } from './models/customer.model';
import { CustomerCardComponent } from './components/customer-card/customer-card.component';
import { SearchBarComponent } from './components/search-bar/search-bar.component';
import { PaginationComponent } from './components/pagination/pagination.component';
import { CustomerService } from './services/customer.service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    RouterOutlet,
    CommonModule,
    FormsModule,
    HttpClientModule,
    CustomerCardComponent,
    SearchBarComponent,
    PaginationComponent
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
  private loadSequence = 0;
  private refreshTimer: any = null;

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
    const seq = ++this.loadSequence;
    this.customerService.getCustomers(this.searchTerm()).subscribe(list => {
      // Ignore stale responses that started before a newer request
      if (seq === this.loadSequence) {
        this.customers.set(list);
      }
    });
  }

  protected onSearch(): void {
    this.loadCustomers();
  }

  protected toggleFavorite(c: Customer): void {
    const originalFavorite = c.favorite;

    // Optimistic update
    const optimistic = this.customers().map(item => item.id === c.id ? { ...item, favorite: !originalFavorite } : item);
    this.customers.set(optimistic);

    const action$ = originalFavorite ? this.customerService.removeFavorite(c.id) : this.customerService.markFavorite(c.id);
    action$.subscribe({
      next: updated => {
        const synced = this.customers().map(item => (item.id === updated.id ? updated : item));
        this.customers.set(synced);
        this.scheduleRefresh();
      },
      error: () => {
        // Revert on failure
        const reverted = this.customers().map(item => item.id === c.id ? { ...item, favorite: originalFavorite } : item);
        this.customers.set(reverted);
      }
    });
  }

  protected goto(page: number): void {
    if (page < 1 || page > this.totalPages()) return;
    this.currentPage.set(page);
  }

  protected trackById(_: number, item: Customer): number {
    return item.id;
  }

  private scheduleRefresh(): void {
    if (this.refreshTimer) clearTimeout(this.refreshTimer);
    this.refreshTimer = setTimeout(() => this.loadCustomers(), 1500);
  }
}