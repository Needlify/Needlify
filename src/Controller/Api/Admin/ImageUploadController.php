<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api\Admin;

use App\Service\FileUploaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/rest', format: 'json')]
#[IsGranted('ROLE_USER')]
class ImageUploadController extends AbstractController
{
    #[Route('/article/image/upload', 'api_image_upload', methods: ['POST'], options: ['expose' => true])]
    public function uploadImage(Request $request, FileUploaderService $fileUploaderService): Response
    {
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('image');

        $fileUploaderService->validateFile($imageFile);
        $filePath = ltrim($fileUploaderService->upload($imageFile), '/');

        return $this->json([
            'data' => [
                'filePath' => $filePath,
            ],
        ], Response::HTTP_OK);
    }
}
