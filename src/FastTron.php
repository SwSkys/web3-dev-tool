<?php
namespace Web3Tool;


use GuzzleHttp\Client;
use IEXBase\TronAPI\Exception\TronException;
use Tron\Address;
use Tron\Api;
use Tron\Exceptions\TransactionException;
use Tron\Exceptions\TronErrorException;
use Tron\Transaction;

class FastTron{
    // Trx

    const URI = 'https://api.trongrid.io'; // shasta testnet
    //const ADDRESS = 'TGytofNKuSReFmFxsgnNx19em3BAVBTpVB';

    /**
     * @throws TronErrorException
     * 获取Trx的句柄
     */
    public static function getTrx($node = ''): \Tron\TRX
    {
        // 获取Trx的句柄
        $api = new Api(new Client(['base_uri' => $node ?? self::URI]));
        return new \Tron\TRX($api);
    }

    /**
     * @throws TronException
     * 创建钱包地址
     */
    public static function createAddress(): array
    {
        return (array)((new \IEXBase\TronAPI\Tron())->generateAddress())->getRawData();
    }

    /**
     * @throws TronErrorException
     * 私钥转地址
     */
    public static function privateKeyToAddress(string $privateKey,bool $onlyAddress = false)
    {
        if ($onlyAddress){
            return (self::getTrx()->privateKeyToAddress($privateKey))->address;
        }else{
            return self::getTrx()->privateKeyToAddress($privateKey);
        }
    }

    /**
     * @throws TronErrorException
     * 获取主币Trx的数量
     */
    public static function balance(string $address) :float
    {
        $addressObj = new Address(
            $address,
            '',
            self::getTRX()->tron->address2HexString($address)
        );
        return self::getTRX()->balance($addressObj);
    }

    /**
     * @param $fromPrivateKey string 来源地址 私钥
     * @param $toAddress string 接收地址
     * @param $amount float 数量
     * @param $memo string|null 备注
     * @return Transaction
     * @throws TronErrorException
     * @throws TransactionException
     * 小王定制转帐方法
     */
    public static function transfer(string $fromPrivateKey, string $toAddress, float $amount, string $memo = null): Transaction
    {
        $from = self::getTRX()->privateKeyToAddress($fromPrivateKey);
        $to = new Address(
            $toAddress,
            '',
            self::getTRX()->tron->address2HexString($toAddress)
        );
        if ($memo) $memo =  Utils::decToHex($memo);;
        return self::getTRX()->transfer($from, $to, $amount);
    }

    /**
     * @throws TransactionException
     * @throws TronErrorException
     * @return int
     * 获取当前的区块高度
     */
    public static function blockNumber(): int
    {
        $blockData = self::getTRX()->blockNumber();
        return $blockData->block_header['raw_data']['number'];
    }

    /**
     * @throws TransactionException
     * @throws TronErrorException
     */
    public static function blockByNumber(int $blockID): \Tron\Block
    {
        return self::getTRX()->blockByNumber($blockID);
    }

    /**
     * @param string $txHash
     * @return Transaction
     * @throws TransactionException
     * @throws TronErrorException
     * 获取转帐Hash
     */
    public static function transactionReceipt(string $txHash): Transaction
    {
        return self::getTRX()->transactionReceipt($txHash);
    }

    /**
     * @throws TronException
     * @throws TronErrorException
     */
    public static function accountsTransactionsOnlyTo(string $address, int $limit=20, int $fingerprint=1, string $order_by ='desc', int $min_timestamp=0, $max_timestamp = null): array
    {
        $manager = self::getTrx()->tron->getManager();
        return $manager->request("v1/accounts/{$address}/transactions",[
            'only_to'=>true,
            'limit'=>$limit,
            'fingerprint'=>$fingerprint,
            'order_by'=>$order_by,
            'min_timestamp'=>$min_timestamp,
            'max_timestamp'=>$max_timestamp ?? time(),
        ],'get');
    }

    /**
     * @throws TronException
     * @throws TronErrorException
     */
    public static function accountsTransactionsOnlyFrom(string $address, int $limit=20, int $fingerprint=1, string $order_by ='desc', int $min_timestamp=0, $max_timestamp = null): array
    {
        $manager = self::getTrx()->tron->getManager();
        return $manager->request("v1/accounts/{$address}/transactions",[
            'only_from'=>true,
            'limit'=>$limit,
            'fingerprint'=>$fingerprint,
            'order_by'=>$order_by,
            'min_timestamp'=>$min_timestamp,
            'max_timestamp'=>$max_timestamp ?? time(),
        ],'get');
    }

}
