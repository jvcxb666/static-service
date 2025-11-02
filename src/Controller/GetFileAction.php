<?php

namespace StaticService\Controller;

use Aws\S3\Exception\S3Exception;
use OpenApi\Attributes as OA;
use StaticService\Interface\S3\S3ProviderCached;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GetFileAction extends AbstractController
{
    public function __construct(
        private readonly S3ProviderCached $s3Service,
    ) {
    }

    #[OA\Get(
        path: '/static/{id}',
        description: 'Get preview of file by id',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'storage file id',
                required: true,
                schema: new OA\Schema(),
            ),
        ],
        tags: ['static service']
    )]
    #[OA\Response(response: '200', description: 'Returns added file uid')]
    #[OA\Response(response: '404', description: 'Returns when file is not found')]
    #[OA\Response(response: '400', description: 'Returns error message')]
    #[Route('/static/{id}', methods: [Request::METHOD_GET])]
    public function __invoke(string $id): Response
    {
        try {
            $result = $this->s3Service->get($id);
            $response = new Response($result);
            $response->headers->add(['Content-Type' => 'image/png']);
        } catch (S3Exception) {
            $response = $this->json(['error' => "File with id = '{$id}' was not found"], 404);
        } catch (\Throwable $e) {
            $response = $this->json(['error' => $e->getMessage()], 400);
        }

        return $response;
    }
}
