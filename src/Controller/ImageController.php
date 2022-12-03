<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use League\Glide\ServerFactory;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use League\Glide\Signatures\SignatureException;
use Symfony\Component\Routing\Annotation\Route;
use League\Glide\Responses\SymfonyResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ImageController extends AbstractController
{
    #[Route('/image/{name}', name: 'app_image')]
    public function index(string $name, Request $request): Response
    {
        $server = ServerFactory::create([
            'source' => $this->getParameter('kernel.project_dir') . '/public/uploads/thumbnails',
            'cache' => $this->getParameter('kernel.project_dir') . '/var/cache/' . $this->getParameter('kernel.environment'),
            'cache_path_prefix' => 'thumbnails',
            'driver' => 'gd',
            'base_url' => 'image',
            'response' => new SymfonyResponseFactory($request),
            'max_image_size' => 1000 * 400,
            'defaults' => [
                'q' => 60,
                'fm' => 'jpg',
                'fit' => 'crop',
            ],
        ]);

        try {
            SignatureFactory::create($this->getParameter('app.image.key'))->validateRequest($request->getPathInfo(), $request->query->all());

            return $server->getImageResponse($name, $request->query->all());
        } catch (SignatureException $exception) {
            throw ExceptionFactory::throw(AccessDeniedHttpException::class, ExceptionCode::INVALID_IMAGE_SIGNATURE, 'Invalid image signature');
        }
    }
}
