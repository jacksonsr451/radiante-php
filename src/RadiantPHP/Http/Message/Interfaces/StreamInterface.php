<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Interfaces;

interface StreamInterface
{
    public function __toString();

    public function close();

    public function detach();

    public function getSize();

    public function tell();

    public function eof();

    public function isSeekable();

    public function seek($offset, $whence = SEEK_SET);

    public function rewind();

    public function isWritable();

    public function isReadable();

    public function read($length);

    public function getContents();

    public function getMetadata($key = null);

    public function write($string);
}
