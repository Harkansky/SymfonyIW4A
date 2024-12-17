<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
    )
    {
    }

    #[Route(path: '/', name: 'page_homepage')]
    public function home(
        MediaRepository $mediaRepository,
    ): Response
    {
        $medias = $mediaRepository->findPopular();

        return $this->render(view: 'index.html.twig', parameters: [
            'medias' => $medias,
        ]);
    }
}
