<?php

namespace ccxt;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception as Exception; // a common import

class kucoin2 extends Exchange {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'kucoin2',
            'name' => 'KuCoin',
            'countries' => array ( 'SC' ),
            'rateLimit' => 334,
            'version' => 'v2',
            'certified' => true,
            'comment' => 'Platform 2.0',
            'has' => array (
                'fetchMarkets' => true,
                'fetchCurrencies' => true,
                'fetchTicker' => true,
                'fetchOrderBook' => true,
                'fetchOrder' => true,
                'fetchClosedOrders' => true,
                'fetchOpenOrders' => true,
                'fetchDepositAddress' => true,
                'withdraw' => true,
                'fetchDeposits' => true,
                'fetchWithdrawals' => true,
                'fetchBalance' => true,
                'fetchTrades' => true,
                'fetchMyTrades' => true,
                'createOrder' => true,
                'cancelOrder' => true,
                'fetchAccounts' => true,
                'fetchFundingFee' => true,
                'fetchOHLCV' => true,
            ),
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/51909432-b0a72780-23dd-11e9-99ba-73d23c8d4eed.jpg',
                'referral' => 'https://www.kucoin.com/ucenter/signup?rcode=E5wkqe',
                'api' => array (
                    'public' => 'https://openapi-v2.kucoin.com',
                    'private' => 'https://openapi-v2.kucoin.com',
                ),
                'test' => array (
                    'public' => 'https://openapi-sandbox.kucoin.com',
                    'private' => 'https://openapi-sandbox.kucoin.com',
                ),
                'www' => 'https://www.kucoin.com',
                'doc' => array (
                    'https://docs.kucoin.com',
                ),
            ),
            'requiredCredentials' => array (
                'apiKey' => true,
                'secret' => true,
                'password' => true,
            ),
            'api' => array (
                'public' => array (
                    'get' => array (
                        'timestamp',
                        'symbols',
                        'market/orderbook/level{level}',
                        'market/histories',
                        'market/candles',
                        'market/stats',
                        'currencies',
                        'currencies/{currency}',
                    ),
                    'post' => array (
                        'bullet-public',
                    ),
                ),
                'private' => array (
                    'get' => array (
                        'accounts',
                        'accounts/{accountId}',
                        'accounts/{accountId}/ledgers',
                        'accounts/{accountId}/holds',
                        'deposit-addresses',
                        'deposits',
                        'withdrawals',
                        'withdrawals/quotas',
                        'orders',
                        'orders/{orderId}',
                        'fills',
                    ),
                    'post' => array (
                        'accounts',
                        'accounts/inner-transfer',
                        'deposit-addresses',
                        'withdrawals',
                        'orders',
                        'bullet-private',
                    ),
                    'delete' => array (
                        'withdrawals/{withdrawalId}',
                        'orders/{orderId}',
                    ),
                ),
            ),
            'timeframes' => array (
                '1m' => '1min',
                '3m' => '3min',
                '5m' => '5min',
                '15m' => '15min',
                '30m' => '30min',
                '1h' => '1hour',
                '2h' => '2hour',
                '4h' => '4hour',
                '6h' => '6hour',
                '8h' => '8hour',
                '12h' => '12hour',
                '1d' => '1day',
                '1w' => '1week',
            ),
            'exceptions' => array (
                '400' => '\\ccxt\\BadRequest',
                '401' => '\\ccxt\\AuthenticationError',
                '403' => '\\ccxt\\NotSupported',
                '404' => '\\ccxt\\NotSupported',
                '405' => '\\ccxt\\NotSupported',
                '429' => '\\ccxt\\DDoSProtection',
                '500' => '\\ccxt\\ExchangeError',
                '503' => '\\ccxt\\ExchangeNotAvailable',
                '200004' => '\\ccxt\\InsufficientFunds',
                '300000' => '\\ccxt\\InvalidOrder',
                '400001' => '\\ccxt\\AuthenticationError',
                '400002' => '\\ccxt\\InvalidNonce',
                '400003' => '\\ccxt\\AuthenticationError',
                '400004' => '\\ccxt\\AuthenticationError',
                '400005' => '\\ccxt\\AuthenticationError',
                '400006' => '\\ccxt\\AuthenticationError',
                '400007' => '\\ccxt\\AuthenticationError',
                '400008' => '\\ccxt\\NotSupported',
                '400100' => '\\ccxt\\ArgumentsRequired',
                '411100' => '\\ccxt\\AccountSuspended',
                '500000' => '\\ccxt\\ExchangeError',
                'order_not_exist' => '\\ccxt\\OrderNotFound',  // array ("code":"order_not_exist","msg":"order_not_exist") ¯\_(ツ)_/¯
            ),
            'options' => array (
                'version' => 'v1',
                'symbolSeparator' => '-',
            ),
        ));
    }

    public function nonce () {
        return $this->milliseconds ();
    }

    public function load_time_difference () {
        $response = $this->publicGetTimestamp ();
        $after = $this->milliseconds ();
        $kucoinTime = $this->safe_integer($response, 'data');
        $this->options['timeDifference'] = intval ($after - $kucoinTime);
        return $this->options['timeDifference'];
    }

    public function fetch_markets ($params = array ()) {
        $response = $this->publicGetSymbols ($params);
        //
        // { quoteCurrency => 'BTC',
        //   $symbol => 'KCS-BTC',
        //   quoteMaxSize => '9999999',
        //   quoteIncrement => '0.000001',
        //   baseMinSize => '0.01',
        //   quoteMinSize => '0.00001',
        //   enableTrading => true,
        //   $priceIncrement => '0.00000001',
        //   name => 'KCS-BTC',
        //   baseIncrement => '0.01',
        //   baseMaxSize => '9999999',
        //   baseCurrency => 'KCS' }
        //
        $responseData = $response['data'];
        $result = array ();
        for ($i = 0; $i < count ($responseData); $i++) {
            $entry = $responseData[$i];
            $id = $entry['name'];
            $baseId = $entry['baseCurrency'];
            $quoteId = $entry['quoteCurrency'];
            $base = $this->common_currency_code($baseId);
            $quote = $this->common_currency_code($quoteId);
            $symbol = $base . '/' . $quote;
            $active = $entry['enableTrading'];
            $baseMax = $this->safe_float($entry, 'baseMaxSize');
            $baseMin = $this->safe_float($entry, 'baseMinSize');
            $quoteMax = $this->safe_float($entry, 'quoteMaxSize');
            $quoteMin = $this->safe_float($entry, 'quoteMinSize');
            $priceIncrement = $this->safe_float($entry, 'priceIncrement');
            $precision = array (
                'amount' => -log10 ($this->safe_float($entry, 'quoteIncrement')),
                'price' => -log10 ($priceIncrement),
            );
            $limits = array (
                'amount' => array (
                    'min' => $quoteMin,
                    'max' => $quoteMax,
                ),
                'price' => array (
                    'min' => max ($baseMin / $quoteMax, $priceIncrement),
                    'max' => $baseMax / $quoteMin,
                ),
            );
            $result[$symbol] = array (
                'id' => $id,
                'symbol' => $symbol,
                'baseId' => $baseId,
                'quoteId' => $quoteId,
                'base' => $base,
                'quote' => $quote,
                'active' => $active,
                'precision' => $precision,
                'limits' => $limits,
                'info' => $entry,
            );
        }
        return $result;
    }

    public function fetch_currencies ($params = array ()) {
        $response = $this->publicGetCurrencies ($params);
        //
        // { $precision => 10,
        //   $name => 'KCS',
        //   fullName => 'KCS shares',
        //   currency => 'KCS' }
        //
        $responseData = $response['data'];
        $result = array ();
        for ($i = 0; $i < count ($responseData); $i++) {
            $entry = $responseData[$i];
            $id = $this->safe_string($entry, 'name');
            $name = $entry['fullName'];
            $code = $this->common_currency_code($id);
            $precision = $this->safe_integer($entry, 'precision');
            $result[$code] = array (
                'id' => $id,
                'name' => $name,
                'code' => $code,
                'precision' => $precision,
                'info' => $entry,
            );
        }
        return $result;
    }

    public function fetch_accounts ($params = array ()) {
        $response = $this->privateGetAccounts ($params);
        //
        //     { $code =>   "200000",
        //       $data => array ( array (   balance => "0.00009788",
        //                 available => "0.00009788",
        //                     holds => "0",
        //                  currency => "BTC",
        //                        id => "5c6a4fd399a1d81c4f9cc4d0",
        //                      $type => "trade"                     ),
        //               ...,
        //               {   balance => "0.00000001",
        //                 available => "0.00000001",
        //                     holds => "0",
        //                  currency => "ETH",
        //                        id => "5c6a49ec99a1d819392e8e9f",
        //                      $type => "trade"                     }  ) }
        //
        $data = $this->safe_value($response, 'data');
        $result = array ();
        for ($i = 0; $i < count ($data); $i++) {
            $account = $data[$i];
            $accountId = $this->safe_string($account, 'id');
            $currencyId = $this->safe_string($account, 'currency');
            $code = $this->common_currency_code($currencyId);
            $type = $this->safe_string($account, 'type');  // main or trade
            $result[] = array (
                'id' => $accountId,
                'type' => $type,
                'currency' => $code,
                'info' => $account,
            );
        }
        return $result;
    }

    public function fetch_funding_fee ($code, $params = array ()) {
        $currencyId = $this->currencyId ($code);
        $request = array (
            'currency' => $currencyId,
        );
        $response = $this->privateGetWithdrawalsQuotas (array_merge ($request, $params));
        $data = $response['data'];
        $withdrawFees = array ();
        $withdrawFees[$code] = $this->safe_float($data, 'withdrawMinFee');
        return array (
            'info' => $response,
            'withdraw' => $withdrawFees,
            'deposit' => array (),
        );
    }

    public function parse_ticker ($ticker, $market = null) {
        //
        //     {
        //         "$symbol" => "ETH-BTC",
        //         "$high" => "0.1",
        //         "vol" => "3891.5909166",
        //         "$low" => "0.024",
        //         "changePrice" => "0.031809",
        //         "changeRate" => "31809",
        //         "close" => "0.03181",
        //         "volValue" => "119.5545894397034",
        //         "$open" => "0.000001",
        //     }
        //
        $change = $this->safe_float($ticker, 'changePrice');
        $percentage = $this->safe_float($ticker, 'changeRate');
        $open = $this->safe_float($ticker, 'open');
        $last = $this->safe_float($ticker, 'close');
        $high = $this->safe_float($ticker, 'high');
        $low = $this->safe_float($ticker, 'low');
        $baseVolume = $this->safe_float($ticker, 'vol');
        $quoteVolume = $this->safe_float($ticker, 'volValue');
        $symbol = null;
        if ($market)
            $symbol = $market['symbol'];
        return array (
            'symbol' => $symbol,
            'timestamp' => null,
            'datetime' => null,
            'high' => $high,
            'low' => $low,
            'bid' => $this->safe_float($ticker, 'bid'),
            'bidVolume' => null,
            'ask' => $this->safe_float($ticker, 'ask'),
            'askVolume' => null,
            'vwap' => null,
            'open' => $open,
            'close' => $last,
            'last' => $last,
            'previousClose' => null,
            'change' => $change,
            'percentage' => $percentage,
            'average' => null,
            'baseVolume' => $baseVolume,
            'quoteVolume' => $quoteVolume,
            'info' => $ticker,
        );
    }

    public function fetch_ticker ($symbol, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'symbol' => $market['id'],
        );
        $response = $this->publicGetMarketStats (array_merge ($request, $params));
        //
        //     {
        //         "code" => "200000",
        //         "data" => array (
        //             "$symbol" => "ETH-BTC",
        //             "high" => "0.1",
        //             "vol" => "3891.5909166",
        //             "low" => "0.024",
        //             "changePrice" => "0.031809",
        //             "changeRate" => "31809",
        //             "close" => "0.03181",
        //             "volValue" => "119.5545894397034",
        //             "open" => "0.000001",
        //         ),
        //     }
        //
        return $this->parse_ticker($response['data'], $market);
    }

    public function parse_ohlcv ($ohlcv, $market = null, $timeframe = '1m', $since = null, $limit = null) {
        //
        //     array (
        //         "1545904980",             // Start time of the candle cycle
        //         "0.058",                  // opening price
        //         "0.049",                  // closing price
        //         "0.058",                  // highest price
        //         "0.049",                  // lowest price
        //         "0.018",                  // base volume
        //         "0.000945",               // quote volume
        //     )
        //
        return [
            intval ($ohlcv[0]) * 1000,
            floatval ($ohlcv[1]),
            floatval ($ohlcv[3]),
            floatval ($ohlcv[2]),
            floatval ($ohlcv[4]),
            floatval ($ohlcv[5]),
        ];
    }

    public function fetch_ohlcv ($symbol, $timeframe = '15m', $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $marketId = $market['id'];
        $request = array (
            'symbol' => $marketId,
            'endAt' => $this->seconds (), // required param
            'type' => $this->timeframes[$timeframe],
        );
        if ($since !== null) {
            $request['startAt'] = (int) floor ($since / 1000);
        }
        $response = $this->publicGetMarketCandles (array_merge ($request, $params));
        $responseData = $response['data'];
        return $this->parse_ohlcvs($responseData, $market, $timeframe, $since, $limit);
    }

    public function fetch_deposit_address ($code, $params = array ()) {
        $this->load_markets();
        $currencyId = $this->currencyId ($code);
        $request = array ( 'currency' => $currencyId );
        $response = $this->privateGetDepositAddresses (array_merge ($request, $params));
        $data = $this->safe_value($response, 'data');
        $address = $this->safe_string($data, 'address');
        $tag = $this->safe_string($data, 'memo');
        $this->check_address($address);
        return array (
            'info' => $response,
            'currency' => $code,
            'address' => $address,
            'tag' => $tag,
        );
    }

    public function fetch_order_book ($symbol, $limit = null, $params = array ()) {
        $this->load_markets();
        $marketId = $this->market_id($symbol);
        $request = array ( 'symbol' => $marketId, 'level' => 3 );
        $response = $this->publicGetMarketOrderbookLevelLevel (array_merge ($request, $params));
        //
        // { sequence => '1547731421688',
        //   asks => array ( array ( '5c419328ef83c75456bd615c', '0.9', '0.09' ), ... ),
        //   bids => array ( array ( '5c419328ef83c75456bd615c', '0.9', '0.09' ), ... ), }
        //
        $responseData = $response['data'];
        $timestamp = $this->safe_integer($responseData, 'sequence');
        return $this->parse_order_book($responseData, $timestamp, 'bids', 'asks', 1, 2);
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        $this->load_markets();
        $marketId = $this->market_id($symbol);
        // required param, cannot be used twice
        $clientOid = $this->uuid ();
        $request = array (
            'clientOid' => $clientOid,
            'price' => $price,
            'side' => $side,
            'size' => $amount,
            'symbol' => $marketId,
            'type' => $type,
        );
        $response = $this->privatePostOrders (array_merge ($request, $params));
        $responseData = $response['data'];
        return array (
            'id' => $responseData['orderId'],
            'symbol' => $symbol,
            'type' => $type,
            'side' => $side,
            'status' => 'open',
            'clientOid' => $clientOid,
            'info' => $responseData,
        );
    }

    public function cancel_order ($id, $symbol = null, $params = array ()) {
        $request = array ( 'orderId' => $id );
        $response = $this->privateDeleteOrdersOrderId (array_merge ($request, $params));
        return $response;
    }

    public function fetch_orders_by_status ($status, $symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $request = array (
            'status' => $status,
        );
        $market = null;
        if ($symbol !== null) {
            $market = $this->market ($symbol);
            $request['symbol'] = $market['id'];
        }
        if ($since !== null) {
            $request['startAt'] = $since;
        }
        if ($limit !== null) {
            $request['pageSize'] = $limit;
        }
        $response = $this->privateGetOrders (array_merge ($request, $params));
        $responseData = $this->safe_value($response, 'data', array ());
        $orders = $this->safe_value($responseData, 'items', array ());
        return $this->parse_orders($orders, $market, $since, $limit);
    }

    public function fetch_closed_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        return $this->fetch_orders_by_status ('done', $symbol, $since, $limit, $params);
    }

    public function fetch_open_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        return $this->fetch_orders_by_status ('active', $symbol, $since, $limit, $params);
    }

    public function fetch_order ($id, $symbol = null, $params = array ()) {
        $request = array (
            'orderId' => $id,
        );
        $market = null;
        if ($symbol !== null) {
            $market = $this->market ($symbol);
        }
        $response = $this->privateGetOrdersOrderId (array_merge ($request, $params));
        $responseData = $response['data'];
        return $this->parse_order($responseData, $market);
    }

    public function parse_order ($order, $market = null) {
        //
        //   { "id" => "5c35c02703aa673ceec2a168",
        //     "$symbol" => "BTC-USDT",
        //     "opType" => "DEAL",
        //     "$type" => "limit",
        //     "$side" => "buy",
        //     "$price" => "10",
        //     "size" => "2",
        //     "funds" => "0",
        //     "dealFunds" => "0.166",
        //     "dealSize" => "2",
        //     "$fee" => "0",
        //     "$feeCurrency" => "USDT",
        //     "stp" => "",
        //     "stop" => "",
        //     "stopTriggered" => false,
        //     "stopPrice" => "0",
        //     "timeInForce" => "GTC",
        //     "postOnly" => false,
        //     "hidden" => false,
        //     "iceberge" => false,
        //     "visibleSize" => "0",
        //     "cancelAfter" => 0,
        //     "channel" => "IOS",
        //     "clientOid" => "",
        //     "remark" => "",
        //     "tags" => "",
        //     "isActive" => false,
        //     "cancelExist" => false,
        //     "createdAt" => 1547026471000 }
        //
        $symbol = null;
        $marketId = $this->safe_string($order, 'symbol');
        if ($marketId !== null) {
            if (is_array ($this->markets_by_id) && array_key_exists ($marketId, $this->markets_by_id)) {
                $market = $this->markets_by_id[$marketId];
                $symbol = $market['symbol'];
            } else {
                list ($baseId, $quoteId) = explode ('-', $marketId);
                $base = $this->common_currency_code($baseId);
                $quote = $this->common_currency_code($quoteId);
                $symbol = $base . '/' . $quote;
            }
            $market = $this->safe_value($this->markets_by_id, $marketId);
        }
        if ($symbol === null) {
            if ($market !== null) {
                $symbol = $market['symbol'];
            }
        }
        $orderId = $this->safe_string($order, 'id');
        $type = $this->safe_string($order, 'type');
        $timestamp = $this->safe_string($order, 'createdAt');
        $datetime = $this->iso8601 ($timestamp);
        $price = $this->safe_float($order, 'price');
        $side = $this->safe_string($order, 'side');
        $feeCurrencyId = $this->safe_string($order, 'feeCurrency');
        $feeCurrency = $this->common_currency_code($feeCurrencyId);
        $fee = $this->safe_float($order, 'fee');
        $amount = $this->safe_float($order, 'size');
        $filled = $this->safe_float($order, 'dealSize');
        $remaining = $amount - $filled;
        // bool
        $status = $order['isActive'] ? 'open' : 'closed';
        $fees = array (
            'currency' => $feeCurrency,
            'cost' => $fee,
        );
        return array (
            'id' => $orderId,
            'symbol' => $symbol,
            'type' => $type,
            'side' => $side,
            'amount' => $amount,
            'price' => $price,
            'filled' => $filled,
            'remaining' => $remaining,
            'timestamp' => $timestamp,
            'datetime' => $datetime,
            'fee' => $fees,
            'status' => $status,
            'info' => $order,
        );
    }

    public function fetch_my_trades ($symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $request = array ();
        $market = null;
        if ($symbol !== null) {
            $market = $this->market ($symbol);
            $request['symbol'] = $market['id'];
        }
        if ($since !== null) {
            $request['startAt'] = $since;
        }
        if ($limit !== null) {
            $request['pageSize'] = $limit;
        }
        $response = $this->privateGetFills (array_merge ($request, $params));
        $data = $this->safe_value($response, 'data', array ());
        $trades = $this->safe_value($data, 'items', array ());
        return $this->parse_trades($trades, $market, $since, $limit);
    }

    public function fetch_trades ($symbol, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'symbol' => $market['id'],
        );
        if ($since !== null) {
            $request['startAt'] = (int) floor ($since / 1000);
        }
        if ($limit !== null) {
            $request['pageSize'] = $limit;
        }
        $response = $this->publicGetMarketHistories (array_merge ($request, $params));
        //     {
        //         "code" => "200000",
        //         "data" => array (
        //             {
        //                 "sequence" => "1548764654235",
        //                 "side" => "sell",
        //                 "size":"0.6841354",
        //                 "price":"0.03202",
        //                 "time":1548848575203567174
        //             }
        //         )
        //     }
        //
        $trades = $this->safe_value($response, 'data', array ());
        return $this->parse_trades($trades, $market, $since, $limit);
    }

    public function parse_trade ($trade, $market = null) {
        //
        // fetchTrades (public)
        //
        //     {
        //         "sequence" => "1548764654235",
        //         "$side" => "sell",
        //         "size":"0.6841354",
        //         "$price":"0.03202",
        //         "time":1548848575203567174
        //     }
        //
        // fetchMyTrades (private)
        //
        //     {
        //         "$symbol":"BTC-USDT",
        //         "tradeId":"5c35c02709e4f67d5266954e",
        //         "$orderId":"5c35c02703aa673ceec2a168",
        //         "counterOrderId":"5c1ab46003aa676e487fa8e3",
        //         "$side":"buy",
        //         "liquidity":"taker",
        //         "forceTaker":true,
        //         "$price":"0.083",
        //         "size":"0.8424304",
        //         "funds":"0.0699217232",
        //         "$fee":"0",
        //         "feeRate":"0",
        //         "feeCurrency":"USDT",
        //         "stop":"",
        //         "$type":"limit",
        //         "createdAt":1547026472000
        //     }
        //
        $symbol = null;
        $marketId = $this->safe_string($trade, 'symbol');
        if ($marketId !== null) {
            if (is_array ($this->markets_by_id) && array_key_exists ($marketId, $this->markets_by_id)) {
                $market = $this->markets_by_id[$marketId];
                $symbol = $market['symbol'];
            } else {
                list ($baseId, $quoteId) = explode ('-', $marketId);
                $base = $this->common_currency_code($baseId);
                $quote = $this->common_currency_code($quoteId);
                $symbol = $base . '/' . $quote;
            }
            $market = $this->safe_value($this->markets_by_id, $marketId);
        }
        if ($symbol === null) {
            if ($market !== null) {
                $symbol = $market['symbol'];
            }
        }
        $id = $this->safe_string($trade, 'tradeId');
        if ($id !== null) {
            $id = (string) $id;
        }
        $orderId = $this->safe_string($trade, 'orderId');
        $amount = $this->safe_float($trade, 'size');
        $timestamp = $this->safe_integer($trade, 'time');
        if ($timestamp !== null) {
            $timestamp = intval ($timestamp / 1000000);
        } else {
            $timestamp = $this->safe_integer($trade, 'createdAt');
        }
        $price = $this->safe_float($trade, 'price');
        $side = $this->safe_string($trade, 'side');
        $fee = array (
            'cost' => $this->safe_float($trade, 'fee'),
            'rate' => $this->safe_float($trade, 'feeRate'),
            'feeCurrency' => $this->safe_string($trade, 'feeCurrency'),
        );
        $type = $this->safe_string($trade, 'type');
        $cost = $this->safe_float($trade, 'funds');
        if ($amount !== null) {
            if ($price !== null) {
                $cost = $amount * $price;
            }
        }
        return array (
            'info' => $trade,
            'id' => $id,
            'order' => $orderId,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'symbol' => $symbol,
            'type' => $type,
            'side' => $side,
            'price' => $price,
            'amount' => $amount,
            'cost' => $cost,
            'fee' => $fee,
        );
    }

    public function withdraw ($code, $amount, $address, $tag = null, $params = array ()) {
        $this->load_markets();
        $this->check_address($address);
        $currency = $this->currencyId ($code);
        $request = array (
            'currency' => $currency,
            'address' => $address,
        );
        if ($tag !== null) {
            $request['memo'] = $tag;
        }
        $response = $this->privatePostWithdrawal (array_merge ($request, $params));
        //
        // array ( "withdrawalId" => "5bffb63303aa675e8bbe18f9" )
        //
        $responseData = $response['data'];
        return array (
            'id' => $this->safe_string($responseData, 'withdrawalId'),
            'info' => $responseData,
        );
    }

    public function parse_transaction_status ($status) {
        $statuses = array (
            'SUCCESS' => 'ok',
            'PROCESSING' => 'ok',
            'FAILURE' => 'failed',
        );
        return $this->safe_string($statuses, $status);
    }

    public function parse_transaction ($transaction, $currency = null) {
        //
        // Deposits
        //   { "$address" => "0x5f047b29041bcfdbf0e4478cdfa753a336ba6989",
        //     "memo" => "5c247c8a03aa677cea2a251d",
        //     "$amount" => 1,
        //     "fee" => 0.0001,
        //     "$currency" => "KCS",
        //     "isInner" => false,
        //     "walletTxId" => "5bbb57386d99522d9f954c5a@test004",
        //     "$status" => "SUCCESS",
        //     "createdAt" => 1544178843000,
        //     "updatedAt" => 1544178891000 }
        // Withdrawals
        //   { "id" => "5c2dc64e03aa675aa263f1ac",
        //     "$address" => "0x5bedb060b8eb8d823e2414d82acce78d38be7fe9",
        //     "memo" => "",
        //     "$currency" => "ETH",
        //     "$amount" => 1.0000000,
        //     "fee" => 0.0100000,
        //     "walletTxId" => "3e2414d82acce78d38be7fe9",
        //     "isInner" => false,
        //     "$status" => "FAILURE",
        //     "createdAt" => 1546503758000,
        //     "updatedAt" => 1546504603000 }
        //
        $code = null;
        $currencyId = $this->safe_string($transaction, 'currency');
        $currency = $this->safe_value($this->currencies_by_id, $currencyId);
        if ($currency !== null) {
            $code = $currency['code'];
        } else {
            $code = $this->common_currency_code($currencyId);
        }
        $address = $this->safe_string($transaction, 'address');
        $amount = $this->safe_float($transaction, 'amount');
        $txid = $this->safe_string($transaction, 'walletTxId');
        $type = $txid === null ? 'withdrawal' : 'deposit';
        $rawStatus = $this->safe_string($transaction, 'status');
        $status = $this->parse_transaction_status ($rawStatus);
        $fees = array (
            'cost' => $this->safe_float($transaction, 'fee'),
        );
        if ($fees['cost'] !== null && $amount !== null) {
            $fees['rate'] = $fees['cost'] / $amount;
        }
        $tag = $this->safe_string($transaction, 'memo');
        $timestamp = $this->safe_integer_2($transaction, 'updatedAt', 'createdAt');
        $datetime = $this->iso8601 ($timestamp);
        return array (
            'address' => $address,
            'tag' => $tag,
            'currency' => $code,
            'amount' => $amount,
            'txid' => $txid,
            'type' => $type,
            'status' => $status,
            'fee' => $fees,
            'timestamp' => $timestamp,
            'datetime' => $datetime,
            'info' => $transaction,
        );
    }

    public function fetch_deposits ($code = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $request = array ();
        $currency = null;
        if ($code !== null) {
            $currency = $this->currency ($code);
            $request['currency'] = $currency['id'];
        }
        if ($since !== null) {
            $request['startAt'] = $since;
        }
        if ($limit !== null) {
            $request['pageSize'] = $limit;
        }
        $response = $this->privateGetDeposits (array_merge ($request, $params));
        //
        // paginated
        // { $code => '200000',
        //   data:
        //    { totalNum => 0,
        //      totalPage => 0,
        //      pageSize => 10,
        //      currentPage => 1,
        //      items => [...]
        //     } }
        //
        $responseData = $response['data']['items'];
        return $this->parseTransactions ($responseData, $currency, $since, $limit);
    }

    public function fetch_withdrawals ($code = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $request = array ();
        $currency = null;
        if ($code !== null) {
            $currency = $this->currency ($code);
            $request['currency'] = $currency['id'];
        }
        if ($since !== null) {
            $request['startAt'] = $since;
        }
        if ($limit !== null) {
            $request['pageSize'] = $limit;
        }
        $response = $this->privateGetWithdrawals (array_merge ($request, $params));
        //
        // paginated
        // { $code => '200000',
        //   data:
        //    { totalNum => 0,
        //      totalPage => 0,
        //      pageSize => 10,
        //      currentPage => 1,
        //      items => [...] } }
        //
        $responseData = $response['data']['items'];
        return $this->parseTransactions ($responseData, $currency, $since, $limit);
    }

    public function fetch_balance ($params = array ()) {
        $this->load_markets();
        $request = array (
            'type' => 'trade',
        );
        $response = $this->privateGetAccounts (array_merge ($request, $params));
        $responseData = $response['data'];
        $result = array ( 'info' => $responseData );
        for ($i = 0; $i < count ($responseData); $i++) {
            $entry = $responseData[$i];
            $currencyId = $entry['currency'];
            $code = $this->common_currency_code($currencyId);
            $account = array ();
            $account['total'] = $this->safe_float($entry, 'balance', 0);
            $account['free'] = $this->safe_float($entry, 'available', 0);
            $account['used'] = $this->safe_float($entry, 'holds', 0);
            $result[$code] = $account;
        }
        return $this->parse_balance($result);
    }

    public function sign ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        //
        // the v2 URL is https://openapi-v2.kucoin.com/api/v1/endpoint
        //                                †                 ↑
        //
        $endpoint = '/api/' . $this->options['version'] . '/' . $this->implode_params($path, $params);
        $query = $this->omit ($params, $this->extract_params($path));
        $endpart = '';
        $headers = $headers !== null ? $headers : array ();
        if ($query) {
            if ($method !== 'GET') {
                $body = $this->json ($query);
                $endpart = $body;
                $headers['Content-Type'] = 'application/json';
            } else {
                $endpoint .= '?' . $this->urlencode ($query);
            }
        }
        $url = $this->urls['api'][$api] . $endpoint;
        if ($api === 'private') {
            $this->check_required_credentials();
            $timestamp = (string) $this->nonce ();
            $headers = array_merge (array (
                'KC-API-KEY' => $this->apiKey,
                'KC-API-TIMESTAMP' => $timestamp,
                'KC-API-PASSPHRASE' => $this->password,
            ), $headers);
            $payload = $timestamp . $method . $endpoint . $endpart;
            $signature = $this->hmac ($this->encode ($payload), $this->encode ($this->secret), 'sha256', 'base64');
            $headers['KC-API-SIGN'] = $this->decode ($signature);
        }
        return array ( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function handle_errors ($code, $reason, $url, $method, $headers, $body, $response) {
        if (!$response) {
            return;
        }
        //
        // bad
        // array ("$code":"400100","msg":"validation.createOrder.clientOidIsRequired")
        // good
        // { $code => '200000',
        //   data => array (...), }
        //
        $errorCode = $this->safe_string($response, 'code');
        if (is_array ($this->exceptions) && array_key_exists ($errorCode, $this->exceptions)) {
            $Exception = $this->exceptions[$errorCode];
            $message = $this->safe_string($response, 'msg', '');
            throw new $Exception ($this->id . ' ' . $message);
        }
    }
}
