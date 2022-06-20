<?php

namespace Zhuk\Payeer\TradeApi;

interface SignerInterface
{
    /**
     * Return data sign.
     *
     * @param string $data
     *
     * @return string
     */
    public function sign(string $data): string;
}
