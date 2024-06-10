<?php

namespace App\Controller;

use App\Service\YgoprodeckService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cards')]
class CardsController extends AbstractController
{
    private $ygoprodeckService;

    public function __construct(YgoprodeckService $ygoprodeckService)
    {
        $this->ygoprodeckService = $ygoprodeckService;
    }

    #[Route('/', name: 'app_cards')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCards($page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('cards/index.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $cards['meta']['total_rows']
        ]);
    }

    #[Route('/{id}', name: 'card_detail')]
    public function cardDetails(int $id): Response
    {
        try {
            $card = $this->ygoprodeckService->getCardDetails($id);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('cards/details/show.html.twig', [
            'card' => $card,
            'archetype_url' => isset($card['archetype']) ? urlencode(str_replace('/', '~', $card['archetype'])) : null,
        ]);
    }

    #[Route('/archetype/{archetype}', name: 'detail_archetype')]
    public function showArchetype(string $archetype, Request $request): Response
    {
        $archetype = urldecode(str_replace('~', '/', $archetype));
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByArchetype($archetype, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showArchetype.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'archetype' => $archetype,
        ]);
    }

    #[Route('/minAtk/{atk}', name: 'detail_min_attack')]
    public function showMinAttack(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);
        $minAtk = $request->query->getInt('atk', 0);

        try {
            $cards = $this->ygoprodeckService->getCardsByMinAtk($page, $num, $minAtk);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('cards/details/showMinAttack.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'minAtk' => $minAtk,
            'total' => $cards['meta']['total_rows']
        ]);
    }

    #[Route('/minDef/{def}', name: 'detail_min_defense')]
    public function showMinDefense(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);
        $minDef = $request->query->getInt('def', 0);

        try {
            $cards = $this->ygoprodeckService->getCardsByMinDef($page, $num, $minDef);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('cards/details/showMinDefense.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'minDef' => $minDef,
            'total' => $cards['meta']['total_rows']
        ]);
    }

    #[Route('/atk/{atk}', name: 'detail_attack')]
    public function showAttack(string $atk, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByAtk($atk, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showAttack.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'attack' => $atk,
        ]);
    }

    #[Route('/def/{def}', name: 'detail_defense')]
    public function showDefense(string $def, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByDef($def, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showDefense.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'defense' => $def,
        ]);
    }

    #[Route('/attribute/{attribute}', name: 'detail_attribute')]
    public function showAttribute(string $attribute, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByAttribute($attribute, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showAttribute.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'attribute' => $attribute,
        ]);
    }

    #[Route('/level/{level}', name: 'detail_level')]
    public function showLevel(string $level, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByLevel($level, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showLevel.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'level' => $level,
        ]);
    }

    #[Route('/scale/{scale}', name: 'detail_scale')]
    public function showScale(string $scale, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByScale($scale, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showScale.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'scale' => $scale,
        ]);
    }

    #[Route('/linkval/{linkval}', name: 'detail_link')]
    public function showLink(string $linkval, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByLink($linkval, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showLink.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'linkval' => $linkval,
        ]);
    }

    #[Route('/race/{race}', name: 'detail_race')]
    public function showRace(string $race, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByRace($race, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showRace.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'race' => $race,
        ]);
    }

    #[Route('/type/{type}', name: 'detail_type')]
    public function showType(string $type, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByType($type, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showType.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'type' => $type,
        ]);
    }

    #[Route('/race/{race}/type/{type}', name: 'detail_race_type')]
    public function showTypeRace(string $race, string $type, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $num = $request->query->getInt('num', 8);

        try {
            $cards = $this->ygoprodeckService->getCardsByRaceType($race, $type, $page, $num);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total = $cards['meta']['total_rows'];

        return $this->render('cards/details/showTypeRace.html.twig', [
            'cards' => $cards['data'],
            'page' => $page,
            'num' => $num,
            'total' => $total,
            'race' => $race,
            'type' => $type,
        ]);
    }
}
