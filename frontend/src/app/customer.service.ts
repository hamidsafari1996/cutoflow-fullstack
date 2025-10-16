import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Customer } from './customer.model';

@Injectable({ providedIn: 'root' })
export class CustomerService {
  // Use relative URL; dev-server proxy forwards to backend in Docker
  private readonly baseUrl = '/customers';

  constructor(private readonly http: HttpClient) {}

  getCustomers(search?: string): Observable<Customer[]> {
    const params = search ? new HttpParams().set('search', search) : undefined;
    return this.http.get<Customer[]>(this.baseUrl, { params });
  }

  markFavorite(id: number): Observable<Customer> {
    return this.http.post<Customer>(`${this.baseUrl}/${id}/favorite`, {});
  }

  removeFavorite(id: number): Observable<Customer> {
    return this.http.delete<Customer>(`${this.baseUrl}/${id}/favorite`);
  }
}


