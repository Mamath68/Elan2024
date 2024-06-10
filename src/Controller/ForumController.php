<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Comment;
use App\Entity\Publications;
use App\Form\AnswersType;
use App\Form\CommentType;
use App\Form\PublicationsType;
use App\Repository\PublicationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'app_forum')]
    public function index(PublicationsRepository $publicationsRepository): Response
    {
        return $this->render('forum/publication.html.twig', [
            'publications' => $publicationsRepository->findAll(),
        ]);
    }

    #[Route('/publication/new', name: 'app_publications_new', methods: ['GET', 'POST'])]
    public function newPublication(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publications();
        $publication->setUser($this->getUser());
        $form = $this->createForm(PublicationsType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publications/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/publication/{id}/edit', name: 'app_publications_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publications $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationsType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publications/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/publication/{id}', name: 'app_publications_delete', methods: ['POST'])]
    public function delete(Request $request, Publications $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $publication->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/publications/{id}', name: 'app_forum_show', methods: ['GET'])]
    public function showPublications(Publications $publications): Response
    {
        return $this->render('forum/comment.html.twig', [
            'publication' => $publications,
            'comments' => $publications->getComments(),
        ]);
    }

    #[Route('/comment/new/{publicationId}', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function newComment(Request $request, int $publicationId, EntityManagerInterface $entityManager): Response
    {
        $publication = $entityManager->getRepository(Publications::class)->find($publicationId);

        if (!$publication) {
            throw $this->createNotFoundException('La publication correspondante n\'a pas été trouvée.');
        }
        $comment = new Comment();
        $comment->setPublication($publication);
        $comment->setUser($this->getUser());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_show', ['id' => $publication->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/comment/{publicationId}/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function editComment(Request $request, int $publicationId, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $publication = $entityManager->getRepository(Publications::class)->find($publicationId);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_show', ['id' => $publication->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/comment/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function deleteComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez si le jeton CSRF est valide
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            // Supprimez le commentaire
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        // Redirigez vers la page où les commentaires sont affichés
        return $this->redirectToRoute('app_forum_show', ['id' => $comment->getPublication()->getId()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/comment/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function showComment(Comment $comment): Response
    {
        return $this->render('forum/answer.html.twig', [
            'comment' => $comment,
            'answers' => $comment->getAnswers(),
        ]);
    }

    #[Route('/new/{commentId}', name: 'app_answers_new', methods: ['GET', 'POST'])]
    public function newAnswer(Request $request, int $commentId, EntityManagerInterface $entityManager): Response
    {
        $comment = $entityManager->getRepository(Comment::class)->find($commentId);

        if (!$comment) {
            throw $this->createNotFoundException('Le commentaire correspondante n\'a pas été trouvée.');
        }
        $answer = new Answers();
        $answer->setComment($comment);
        $answer->setUser($this->getUser());

        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_show', ['id' => $comment->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answers/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
            'comment' => $comment
        ]);
    }

    #[Route('/{commentId}/{id}/edit', name: 'app_answers_edit', methods: ['GET', 'POST'])]
    public function editAnswer(Request $request, int $commentId, Answers $answer, EntityManagerInterface $entityManager): Response
    {
        $comment = $entityManager->getRepository(Comment::class)->find($commentId);

        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_show', ['id' => $comment->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answers/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
            'comment' => $comment
        ]);
    }

    #[Route('/{commentId}/{id}', name: 'app_answers_delete', methods: ['POST'])]
    public function deleteAnswer(Request $request, int $commentId, Answers $answer, EntityManagerInterface $entityManager): Response
    {
        $comment = $entityManager->getRepository(Comment::class)->find($commentId);

        if ($this->isCsrfTokenValid('delete' . $answer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_show', ['id' => $comment->getId()], Response::HTTP_SEE_OTHER);
    }
}
