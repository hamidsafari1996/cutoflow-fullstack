<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customers')]
class CustomerController extends AbstractController
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('', name: 'customers_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $search = $request->query->get('search');
        $customers = $this->customerRepository->findBySearch($search);

        return $this->json(array_map($this->mapCustomer(...), $customers));
    }

    #[Route('/{id}/favorite', name: 'customers_mark_favorite', methods: ['POST'])]
    public function markFavorite(Customer $customer): JsonResponse
    {
        $customer->setFavorite(true);
        $this->entityManager->flush();

        return $this->json(($this->mapCustomer)($customer));
    }

    #[Route('/{id}/favorite', name: 'customers_remove_favorite', methods: ['DELETE'])]
    public function removeFavorite(Customer $customer): JsonResponse
    {
        $customer->setFavorite(false);
        $this->entityManager->flush();

        return $this->json(($this->mapCustomer)($customer));
    }

    private function mapCustomer(Customer $customer): array
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


