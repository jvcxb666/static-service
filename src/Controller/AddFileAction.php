<?php

namespace StaticService\Controller;

use StaticService\Interface\S3\S3Storage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AddFileAction extends AbstractController
{
    public function __construct(
        private readonly S3Storage $s3Service,
    ) {
    }

    #[Route('/static/', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $result = $this->s3Service->add($request->files->get('file') ?? '');
            $response = $this->json($result);
        } catch (\Throwable $e) {
            $response = $this->json(['error' => $e->getMessage()], 400);
        }

        return $response;
    }
}
