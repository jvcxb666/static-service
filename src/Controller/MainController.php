<?php

namespace StaticService\Controller;

use Aws\S3\Exception\S3Exception;
use StaticService\Interface\S3\S3ServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/static')]
final class MainController extends AbstractController
{
    public function __construct(
        private readonly S3ServiceInterface $s3Service,
    ) {
    }

    #[Route('/{id}', methods: [Request::METHOD_GET])]
    public function get(string $id): Response
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

    #[Route('/{id}/download', methods: [Request::METHOD_GET])]
    public function download(string $id): Response
    {
        try {
            $result = $this->s3Service->get($id);
            $response = new Response($result);
            $response->headers->add(['Content-Type' => 'application/octet-stream']);
            $response->headers->add(['Content-Disposition' => "attachment; filename={$id}"]);
        } catch (S3Exception) {
            $response = $this->json(['error' => "File with id = '{$id}' was not found"], 404);
        } catch (\Throwable $e) {
            $response = $this->json(['error' => $e->getMessage()], 400);
        }

        return $response;
    }

    #[Route('/', methods: [Request::METHOD_POST])]
    public function add(Request $request): JsonResponse
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
