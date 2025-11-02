<?php

namespace StaticService\Controller;

use OpenApi\Attributes as OA;
use StaticService\Interface\S3\S3Storage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Info(version: '3.0.0', title: 'Static service', description: 'File storage and preview service')]
final class AddFileAction extends AbstractController
{
    public function __construct(
        private readonly S3Storage $s3Service,
    ) {
    }

    #[OA\Post(
        path: '/static/',
        description: 'Add new file to a storage',
        requestBody: new OA\RequestBody(
            description: 'file',
            required: true,
            content: new OA\MediaType('file')
        ),
        tags: ['static service']
    )]
    #[OA\Response(response: '200', description: 'Returns added file uid')]
    #[OA\Response(response: '400', description: 'Returns error message')]
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
