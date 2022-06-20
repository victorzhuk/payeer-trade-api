<?php

namespace Zhuk\Payeer\TradeApi\Enums;

interface MethodsEnumInterface
{
    /**
     * Determine if current method is public.
     *
     * @return bool
     */
    public function isPublic(): bool;

    /**
     * Get method URI path.
     *
     * @return string
     */
    public function getPath(): string;
}
