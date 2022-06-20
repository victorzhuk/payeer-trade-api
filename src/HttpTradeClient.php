<?php

namespace Zhuk\Payeer\TradeApi;

use LogicException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Zhuk\Payeer\TradeApi\Enums\MethodsEnumInterface;
use Zhuk\Payeer\TradeApi\Enums\OrderActionsEnum;
use Zhuk\Payeer\TradeApi\Enums\OrderTypesEnum;
use Zhuk\Payeer\TradeApi\Enums\PairsEnum;
use Zhuk\Payeer\TradeApi\Enums\PrivateMethodsEnum;
use Zhuk\Payeer\TradeApi\Enums\PublicMethodsEnum;

final class HttpTradeClient implements ApiClientInterface
{
    private const BASE_URI = 'https://payeer.com/api/trade/';

    /**
     * @param ClientInterface         $client
     * @param RequestInterface        $baseRequest
     * @param RequestFactoryInterface $requestFactory
     * @param UriFactoryInterface     $uriFactory
     * @param StreamFactoryInterface  $streamFactory
     * @param SignerInterface         $signer
     * @param string|null             $apiUserId
     */
    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private UriFactoryInterface $uriFactory,
        private StreamFactoryInterface $streamFactory,
        private ?SignerInterface $signer = null,
        private ?string $apiUserId = null,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getInfo(): array
    {
        $request = $this->createPublicRequest(PublicMethodsEnum::INFO);

        return $this->send($request);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllOrders(array $pairs = []): array
    {
        if (empty($pairs)) {
            $request = $this->createPublicRequest(PublicMethodsEnum::INFO);
        } else {
            $data = \implode(',', \array_map(fn (PairsEnum $p) => $p->value, $pairs));
            $request = $this->createPublicRequest(PublicMethodsEnum::INFO, [
                'pair' => $data,
            ]);
        }

        return $this->send($request);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountBalance(): array
    {
        $request = $this->createPrivateRequest(PrivateMethodsEnum::ACCOUNT);

        return $this->send($request);
    }

    /**
     * {@inheritDoc}
     */
    public function createOrder(OrderActionsEnum $action, PairsEnum $pair, OrderTypesEnum $type, int $amount, float|int $price): array
    {
        $request = $this->createPrivateRequest(PrivateMethodsEnum::ORDER_CREATE, [
            'action' => $action->value,
            'pair' => $pair->value,
            'type' => $type->value,
            'amount' => $amount,
            'price' => (float) $price,
        ]);

        return $this->send($request);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderStatus(int $orderId): array
    {
        $request = $this->createPrivateRequest(PrivateMethodsEnum::ORDER_STATUS, [
            'order_id' => $orderId,
        ]);

        return $this->send($request);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountOrders(?array $pairs = null, ?OrderActionsEnum $action = null): array
    {
        $data = [];

        if (!empty($pairs)) {
            $data['pair'] = \implode(',', \array_map(fn (PairsEnum $p) => $p->value, $pairs));
        }

        if ($action !== null) {
            $data['action'] = $action->value;
        }

        $request = $this->createPrivateRequest(PrivateMethodsEnum::MY_ORDERS, $data);

        return $this->send($request);
    }

    /**
     * @param MethodsEnumInterface $method
     * @param array|null           $data
     *
     * @return RequestInterface
     */
    private function createPublicRequest(MethodsEnumInterface $method, ?array $data = null): RequestInterface
    {
        $uri = $this->uriFactory->createUri(self::BASE_URI . $method->getPath());
        $httpMethod = $data === null ? 'GET' : 'POST';

        return $this->requestFactory->createRequest($httpMethod, $uri);
    }

    private function createPrivateRequest(MethodsEnumInterface $method, array $data = []): RequestInterface
    {
        if ($this->apiUserId === null || $this->signer === null) {
            throw new LogicException('Call private api with empty user ID or empty signer');
        }

        $request = $this->createPublicRequest($method, $data);

        $stream = $this->streamFactory->createStream(\json_encode([
            'ts' => \round(\microtime(true) * 1000),
            ...$data,
        ]));

        return $request
            ->withBody($stream)
            ->withHeader('API-ID', $this->apiUserId)
            ->withHeader('API-SIGN', $this->createSign($method, $stream->getContents()));
    }

    /**
     * @param MethodsEnumInterface $method
     * @param string               $data
     *
     * @return string
     */
    private function createSign(MethodsEnumInterface $method, string $data): string
    {
        if ($this->signer === null) {
            throw new LogicException('Expect signer not to be null');
        }

        return $this->signer->sign($method->getPath() . $data);
    }

    /**
     * @param RequestInterface $request
     *
     * @return array
     */
    private function send(RequestInterface $request): array
    {
        $response = $this->client->sendRequest($request);

        return \json_decode($response->getBody()->getContents(), true);
    }
}
