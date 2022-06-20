<?php

namespace Zhuk\Payeer\TradeApi\Enums;

enum PrivateMethodsEnum: string implements MethodsEnumInterface
{
    case ACCOUNT = 'account';
    case ORDER_CREATE = 'order_create';
    case ORDER_STATUS = 'order_status';
    case ORDER_CANCEL = 'order_cancel';
    case ORDERS_CANCEL = 'orders_cancel';
    case MY_ORDERS = 'my_orders';
    case MY_HISTORY = 'my_history';
    case MY_TRADES = 'my_trades';

    /**
     * {@inheritDoc}
     */
    public function isPublic(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->value;
    }
}
