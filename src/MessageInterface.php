<?php

namespace Mbs\MbsBundle;

interface MessageInterface
{
    public function __invoke($message): string;
}