<?php

namespace Jacksonsr45\RadiantPHP\Http\Message;

use Jacksonsr45\RadiantPHP\Http\Message\Errors\InvalidArgumentException;
use Jacksonsr45\RadiantPHP\Http\Message\Errors\RuntimeException;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{
    private mixed $file;
    private mixed $size;
    private mixed $error;
    private mixed $clientFilename;
    private mixed $clientMediaType;
    private bool $moved = false;

    public function __construct(
        mixed $file,
        mixed $size,
        mixed $error,
        mixed $clientFilename = null,
        mixed $clientMediaType = null
    ) {
        $this->file = $file;
        $this->size = $size;
        $this->error = $error;
        $this->clientFilename = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    public function getStream(): mixed
    {
        if ($this->moved) {
            throw new RuntimeException('The uploaded file has already been moved.');
        }

        return is_resource($this->file) ? $this->file : fopen($this->file, 'r');
    }

    public function moveTo($targetPath): void
    {
        if ($this->moved) {
            throw new RuntimeException('The uploaded file has already been moved.');
        }

        if ($this->error !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Cannot move uploaded file. The file contains an error.');
        }

        if (!is_string($targetPath) || empty($targetPath)) {
            throw new InvalidArgumentException('Invalid target path provided; it must be a non-empty string.');
        }

        $targetDirectory = dirname($targetPath);
        if (!is_dir($targetDirectory) || !is_writable($targetDirectory)) {
            throw new InvalidArgumentException('The target directory is not writable.');
        }

        $sapi = PHP_SAPI;
        if (strpos($sapi, 'cli') !== false) {
            $this->moved = rename($this->file, $targetPath);
        } else {
            $this->moved = move_uploaded_file($this->file, $targetPath);
        }

        if (!$this->moved) {
            throw new RuntimeException('Error occurred while moving the uploaded file.');
        }
    }

    public function getSize(): mixed
    {
        return $this->size;
    }

    public function getError(): mixed
    {
        return $this->error;
    }

    public function getClientFilename(): mixed
    {
        return $this->clientFilename;
    }

    public function getClientMediaType(): mixed
    {
        return $this->clientMediaType;
    }
}
