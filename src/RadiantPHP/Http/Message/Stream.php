<?php

namespace Jacksonsr45\RadiantPHP\Http\Message;

use Jacksonsr45\RadiantPHP\Http\Message\Errors\InvalidStreamProvidedError;
use Jacksonsr45\RadiantPHP\Http\Message\Errors\StreamIsDetachedError;
use Jacksonsr45\RadiantPHP\Http\Message\Errors\StreamIsNotReadableError;
use Jacksonsr45\RadiantPHP\Http\Message\Errors\StreamIsNotSeekableError;
use Jacksonsr45\RadiantPHP\Http\Message\Errors\UnableToDetermineStreamPositionError;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\StreamInterface;

class Stream implements StreamInterface
{
    private $stream;

    public function __construct($stream)
    {
        if (is_resource($stream) || is_string($stream)) {
            $this->stream = $stream;
        } else {
            throw new InvalidStreamProvidedError();
        }
    }

    public function __toString(): string
    {
        try {
            if ($this->isSeekable()) {
                $this->rewind();
            }
            return $this->getContents();
        } catch (\Exception $e) {
            return '';
        }
    }


    public function close(): void
    {
        fclose($this->stream);
    }

    public function detach(): void
    {
        $this->stream = null;
    }

    public function getSize(): mixed
    {
        if (!isset($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);
        if ($stats === false) {
            return null;
        }

        return $stats['size'];
    }

    public function tell(): int
    {
        if (!isset($this->stream)) {
            throw new StreamIsDetachedError();
        }

        $result = ftell($this->stream);
        if ($result === false) {
            throw new UnableToDetermineStreamPositionError();
        }

        return $result;
    }

    public function eof(): bool
    {
        return !isset($this->stream) || feof($this->stream);
    }

    public function isSeekable(): bool
    {
        return isset($this->stream) && $this->getMetadata('seekable');
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!isset($this->stream)) {
            throw new StreamIsDetachedError();
        }

        if (!$this->isSeekable()) {
            throw new StreamIsNotSeekableError();
        }

        $result = fseek($this->stream, $offset, $whence);
        if ($result === -1) {
            throw new UnableToDetermineStreamPositionError();
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        if (!isset($this->stream)) {
            return false;
        }

        $mode = $this->getMetadata('mode');
        return strpos($mode, 'w') !== false ||
            strpos($mode, 'a') !== false || strpos($mode, 'x') !== false ||
            strpos($mode, 'c') !== false || strpos($mode, '+') !== false;
    }

    public function write($string): int
    {
        if (!isset($this->stream)) {
            throw new StreamIsDetachedError();
        }

        if (!$this->isWritable()) {
            throw new StreamIsNotSeekableError();
        }

        $result = fwrite($this->stream, $string);
        if ($result === false) {
            throw new UnableToDetermineStreamPositionError();
        }

        return $result;
    }

    public function isReadable(): bool
    {
        if (!isset($this->stream)) {
            return false;
        }

        $mode = $this->getMetadata('mode');
        return strpos($mode, 'r') !== false || strpos($mode, '+') !== false;
    }

    public function read($length): string
    {
        if (!isset($this->stream)) {
            throw new StreamIsDetachedError();
        }

        if (!$this->isReadable()) {
            throw new StreamIsNotReadableError();
        }

        $result = fread($this->stream, $length);
        if ($result === false) {
            throw new UnableToDetermineStreamPositionError();
        }

        return $result;
    }

    public function getContents(): string
    {
        if (!isset($this->stream)) {
            throw new StreamIsDetachedError();
        }

        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new UnableToDetermineStreamPositionError();
        }

        return $contents;
    }

    public function getMetadata($key = null): mixed
    {
        if (!isset($this->stream)) {
            return null;
        }

        $metadata = stream_get_meta_data($this->stream);

        if ($key === null) {
            return $metadata;
        }

        return $metadata[$key] ?? null;
    }
}
