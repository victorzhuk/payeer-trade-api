<?php

namespace Zhuk\Payeer\TradeApi\Enums;

enum PublicMethodsEnum: string implements MethodsEnumInterface
{
    case INFO = 'info';
    case TIME = 'time';
    case TICKER = 'ticker';
    case ORDERS = 'orders';
    case TRADES = 'trades';

    /**
     * {@inheritDoc}
     */
    public function isPublic(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->value;
    }
}
