<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customers')]
class CustomerController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService
    ) {
    }

    #[Route('', name: 'customers_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $search = $request->query->get('search');
        $customers = $this->customerService->searchCustomers($search);

        return $this->json(array_map(
            fn(Customer $c) => $this->customerService->mapToArray($c),
            $customers
        ));
    }

    #[Route('/{id}/favorite', name: 'customers_mark_favorite', methods: ['POST'])]
    public function markFavorite(Customer $customer): JsonResponse
    {
        $updated = $this->customerService->markAsFavorite($customer);

        return $this->json($this->customerService->mapToArray($updated));
    }

    #[Route('/{id}/favorite', name: 'customers_remove_favorite', methods: ['DELETE'])]
    public function removeFavorite(Customer $customer): JsonResponse
    {
        $updated = $this->customerService->removeFavorite($customer);

        return $this->json($this->customerService->mapToArray($updated));
    }
}