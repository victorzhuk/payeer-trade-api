<?php

namespace Zhuk\Payeer\TradeApi;

use Zhuk\Payeer\TradeApi\Enums\OrderActionsEnum;
use Zhuk\Payeer\TradeApi\Enums\OrderTypesEnum;
use Zhuk\Payeer\TradeApi\Enums\PairsEnum;

interface ApiClientInterface
{
    /**
     * Get available pairs, their limits and params.
     *
     * @return array
     */
    public function getInfo(): array;

    /**
     * Get available orders by pairs.
     *
     * @param PairsEnum[] $pairs
     *
     * @return array
     */
    public function getAllOrders(array $pairs = []): array;

    /**
     * Get account balance details.
     *
     * @return array
     */
    public function getAccountBalance(): array;

    /**
     * Create order.
     *
     * @param OrderActionsEnum $action
     * @param PairsEnum        $pair
     * @param OrderTypesEnum   $type
     * @param int              $amount
     * @param float|int        $price
     *
     * @return array
     */
    public function createOrder(OrderActionsEnum $action, PairsEnum $pair, OrderTypesEnum $type, int $amount, float|int $price): array;

    /**
     * Get order status by ID.
     *
     * @param int $orderId
     *
     * @return array
     */
    public function getOrderStatus(int $orderId): array;

    /**
     * Get account orders.
     *
     * @param PairsEnum[]|null      $pairs
     * @param OrderActionsEnum|null $action
     *
     * @return array
     */
    public function getAccountOrders(?array $pairs = null, ?OrderActionsEnum $action = null): array;
}
