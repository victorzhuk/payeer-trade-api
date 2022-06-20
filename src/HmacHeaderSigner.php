<?php

namespace Zhuk\Payeer\TradeApi;

final class HmacHeaderSigner implements SignerInterface
{
    /**
     * @param string $apiSecret
     */
    public function __construct(
        private string $apiSecret
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function sign(string $data): string
    {
        return \hash_hmac('sha256', $data, $this->apiSecret);
    }
}
