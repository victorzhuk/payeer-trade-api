<?php

namespace Zhuk\Payeer\TradeApi\Enums;

enum OrderTypesEnum: string
{
    case LIMIT = 'limit';
    case MARKET = 'market';
    case STOP_LIMIT = 'stop_limit';
}
