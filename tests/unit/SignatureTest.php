<?php

use PHPUnit\Framework\TestCase;
use Zhuk\Payeer\TradeApi\HmacHeaderSigner;

final class SignatureTest extends TestCase
{
    public function testHmacSignerCanCreateSignature(): void
    {
        $signer = new HmacHeaderSigner('test');

        $this->assertEquals(
            '88cd2108b5347d973cf39cdf9053d7dd42704876d8c9a9bd8e2d168259d3ddf7',
            $signer->sign('test')
        );
    }
}
