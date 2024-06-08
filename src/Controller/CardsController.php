<?php

namespace App\Controller;

use App\Service\YgoprodeckService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CardsController extends AbstractController
{

    private $ygoprodeckService;

    public function __construct(YgoprodeckService $ygoprodeckService)
    {
        $this->ygoprodeckService = $ygoprodeckService;
    }

    #[Route('/cards', name: 'app_cards')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCards($page, $num);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', ['message' => $e->getMessage()]);
        }

        return $this->render('cards/index.html.twig', [
            'cards' => $cards['data'], // Assuming 'data' key contains the cards array
            'page' => $page,
            'num' => $num,
            'total' => $cards['meta']['total_rows'] // Assuming the total rows are provided in the response
        ]);
    }

    #[Route('/card/{id}', name: 'card_detail')]
    public function show(int $id): Response
    {
        try {
            $card = $this->ygoprodeckService->getCardDetails($id);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', ['message' => $e->getMessage()]);
        }

        return $this->render('cards/details/show.html.twig', [
            'card' => $card,
        ]);
    }

    #[Route('/cards/archetype/{archetype}', name: 'detail_archetype')]
    public function showArchetype(string $archetype, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByArchetype($archetype, $page, $num);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', ['message' => $e->getMessage()]);
        }

        return $this->render('cards/details/showArchetype.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $cards['meta']['total_rows'],
            'archetype' => $archetype
        ]);
    }
}
