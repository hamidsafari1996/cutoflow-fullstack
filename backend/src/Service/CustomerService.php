<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerService
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Find customers by optional search term.
     *
     * @return Customer[]
     */
    public function searchCustomers(?string $searchTerm): array
    {
        return $this->customerRepository->findBySearch($searchTerm);
    }

    /**
     * Mark a customer as favorite.
     */
    public function markAsFavorite(Customer $customer): Customer
    {
        $customer->setFavorite(true);
        $this->entityManager->flush();

        return $customer;
    }

    /**
     * Remove favorite status from a customer.
     */
    public function removeFavorite(Customer $customer): Customer
    {
        $customer->setFavorite(false);
        $this->entityManager->flush();

        return $customer;
    }

    /**
     * Map a Customer entity to an array representation.
     */
    public function mapToArray(Customer $customer): array
    {
        return [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'email' => $customer->getEmail(),
            'company' => $customer->getCompany(),
            'favorite' => $customer->isFavorite(),
        ];
    }
}

