<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use Symfony\Component\Uid\Uuid;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class FileUploaderService
{
    private const ALLOWED_MIME_TYPES = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
    private const MEGA_SIZE_FACTOR = 1024 * 1024; // 1Mb
    private const MAX_FILE_SIZE = 1 * self::MEGA_SIZE_FACTOR; // 1Mb

    private string $embedDirectory;
    private string $embedPath;

    public function __construct(string $embedDirectory, string $embedPath)
    {
        $this->embedDirectory = $embedDirectory;
        $this->embedPath = $embedPath;
    }

    /**
     * Validate a given file.
     *
     * @param int $size Number of bytes (default: 1048576 => 1Mb)
     * @param int $allowedMimeTypes
     */
    public function validateFile(UploadedFile $file, int $size = self::MAX_FILE_SIZE, array $allowedMimeTypes = self::ALLOWED_MIME_TYPES): void
    {
        if (null === $file) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::MISSING_FILE_PARAM, "Missing key 'image' in the request body");
        }

        if (!$file->isReadable()) {
            throw ExceptionFactory::throw(UnsupportedMediaTypeHttpException::class, ExceptionCode::UNREADABLE_UPLOADED_FILE, 'Unreadable uploaded file');
        }

        if ($file->getSize() > $size) {
            $sizeInMega = $file->getSize() / self::MEGA_SIZE_FACTOR;
            throw ExceptionFactory::throw(UnsupportedMediaTypeHttpException::class, ExceptionCode::UPLOADED_FILE_TOO_BIG, 'Uploaded file must be less than 1Mb (%0.2fMb)', [$sizeInMega]);
        }

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw ExceptionFactory::throw(UnsupportedMediaTypeHttpException::class, ExceptionCode::INVALID_MIME_TYPE, 'Invalid file type. Must be png, jpg, jpeg, gif or webp');
        }
    }

    public function upload(UploadedFile $file)
    {
        $fileName = Uuid::v4()->toRfc4122() . '.' . $file->guessExtension();
        $filePath = $this->embedPath . DIRECTORY_SEPARATOR . $fileName;

        try {
            $file->move($this->embedDirectory, $fileName);
        } catch (FileException $e) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::UPLOADED_FILE_ERROR, 'An error occured while uploading the file');
        }

        return $filePath;
    }
}
