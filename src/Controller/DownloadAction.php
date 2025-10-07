<?php

namespace StaticService\Controller;

use Aws\S3\Exception\S3Exception;
use StaticService\Interface\S3\S3ProviderCached;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DownloadAction extends AbstractController
{
    public function __construct(
        private readonly S3ProviderCached $s3Service,
    ) {
    }

    #[Route('/static/{id}/download', methods: [Request::METHOD_GET])]
    public function __invoke(string $id): Response
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
}
