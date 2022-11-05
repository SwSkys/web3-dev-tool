<?php

namespace Web3Tool;


use kornrunner\Keccak;

class FastWeb3
{

    const BSC_MAIN_NODE = [
        'https://bsc-dataseed.binance.org',
        'https://bsc-dataseed1.binance.org',
        'https://bsc-dataseed2.binance.org',
        'https://bsc-dataseed3.binance.org',
        'https://bsc-dataseed4.binance.org',
        'https://bsc.mytokenpocket.vip',
        'https://bsc-dataseed3.binance.org',
        'https://bsc-dataseed1.ninicoin.io',
        'https://bsc-dataseed1.ninicoin.io',
        'https://bsc-dataseed3.ninicoin.io',
        'https://bsc-dataseed4.ninicoin.io',
        'https://binance.nodereal.io',
        'https://bsc-dataseed4.defibit.io',
        'https://bsc-dataseed3.defibit.io',
        'https://rpc.ankr.com/bsc',
        'https://bsc-mainnet.rpcfast.com',
        'https://bsc-mainnet.rpcfast.com?api_key=S3X5aFCCW9MobqVatVZX93fMtWCzff0MfRj9pvjGKSiX5Nas7hz33HwwlrT5tXRM',
        'https://bsc-mainnet.public.blastapi.io',
        'https://rpc-bsc.bnb48.club',
        'https://bsc-dataseed2.defibit.io',
        'https://bsc-dataseed2.ninicoin.io',
    ];

    const BSC_TEST_NODE = [
        'https://data-seed-prebsc-1-s3.binance.org:8545',
        'https://data-seed-prebsc-2-s2.binance.org:8545',
        'https://data-seed-prebsc-1-s1.binance.org:8545',
        'https://data-seed-prebsc-1-s2.binance.org:8545',
        'https://bsctestapi.terminet.io/rpc',
        'https://bsc-testnet.public.blastapi.io'
    ];

    const USDT_ABI = '[{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[],"name":"_decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burn","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getOwner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"mint","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"renounceOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"}]';

    /**
     * @param int $selectNode
     * @return Web3
     */
    public static function getBscMainWeb3(int $selectNode = 0): Web3
    {
        return new Web3(self::BSC_MAIN_NODE[$selectNode]);
    }

    /**
     * @param int $selectNode
     * @return Web3
     */
    public static function getBscTestWeb3(int $selectNode = 0): Web3
    {
        return new Web3(self::BSC_TEST_NODE[$selectNode]);
    }

    /**
     * @param string $node
     * @return Web3
     */
    public static function getEvmWb3(string $node): Web3
    {
        return new Web3($node);
    }

    /**
     * @param Web3 $web3
     * @param string $address
     * @param bool $toEth
     * @return string|null
     */
    public static function getBalance(Web3 $web3, string $address, bool $toEth =  false): ?string
    {
        try {
            if ($toEth){
                return Utils::weiToEth(Utils::hexToDec($web3->getBalance($address)));
            }
            return Utils::hexToDec($web3->getBalance($address));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param Web3 $web3 Web3 对象
     * @param string $address 钱包地址
     * @param string $contractAddress 合约地址
     * @param string $abi 合约ABI
     * @return mixed
     */
    public static function getBalanceOf(Web3 $web3,string $address,string $contractAddress,string $abi)
    {
        $contract = Contract::at($web3,$abi,$contractAddress);
        return $contract->call('balanceOf',[$address]);
    }

    /**
     * @return array
     */
    public static function createWallet(): array
    {
        $wallet = Wallet::create();//生成一个新的钱包
        return ['address'=>$wallet->getAddress(),'private_key'=>$wallet->getPrivateKey()];
    }

    /**
     * @param Web3 $web3
     * @return string|null
     */
    public static function getBlockNumber(Web3 $web3): ?string
    {
        try {
            return Utils::hexToDec($web3->blockNumber());
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param Web3 $web3
     * @param string $hash
     * @param string $from
     * @param string $to
     * @param float|null $amount
     * @param string $contract
     * @param int $decimal
     * @return bool
     */
    public static function checkTransferHash(Web3 $web3,string $hash,string $from,string $to,?float $amount,string $contract,int $decimal = 18): bool
    {
        if (strlen($hash) < 64) return false;
        if (strlen($from) < 40) return false;
        if (strlen($amount) <= 0) return false;
        if (strlen($contract) < 40) return false;
        try {
            $ret = $web3->getTransactionReceipt($hash);
            if (strtolower($contract) != strtolower($ret->logs[0]->address)){
                return false;
            }
            if (strtolower($from) != strtolower($ret->from)){
                return false;
            }
            if (Utils::hexToDec($to) != Utils::hexToDec($ret->logs[0]->topics[2])){
                return false;
            }
            $amount_str = Utils::hexToDec($ret->logs[0]->data);
            $amount_str = ($amount_str * (10**$decimal));
            if ($amount_str < $amount){
                return false;
            }
            return true;
        }catch (\Exception $e){
            return false;
        }
    }


    /**
     * @param Web3 $web3
     * @param string $key
     * @param string $toAccount
     * @param float|null $amount
     * @param string $contractAddress
     * @param string $abi
     * @param string $memo
     * @return array
     */
    public static function transferERC20(Web3 $web3,string $key,string $toAccount,?float $amount,string $contractAddress='',string $abi='',string $memo = ''): array
    {
        if (!$key){
            return ['code'=>0,'msg'=>'Private key cannot be empty'];
        }
        if (!$toAccount){
            return ['code'=>0,'msg'=>'Transfer address cannot be blank'];
        }
        if ($amount <= 0){
            return ['code'=>0,'msg'=>'Incorrect transfer quantity'];
        }
        if (!$contractAddress){
            return ['code'=>0,'msg'=>'contract cannot be empty'];
        }
        if (!$abi){
            return ['code'=>0,'msg'=>'abi cannot be empty'];
        }
        try {
            $wallet = Wallet::createByPrivate($key);
            $amount = Utils::ethToWei($amount);
            try {
                $contract = Contract::at($web3,$abi,$contractAddress);
                $hash = $contract->send($wallet, 'transfer', [$toAccount, $amount]);

            } catch (\Exception $e) {
                return ['code'=>0,'msg'=>$e->getMessage()];
            }
            return ['code'=>1,'hash'=>$hash,'msg'=>'Successful'];
        }catch (\Exception $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }

    public static function transfer(Web3 $web3,string $key,string $toAccount,?float $amount,string $memo = '')
    {
        if (!$key){
            return ['code'=>0,'msg'=>'Private key cannot be empty'];
        }
        if (!$toAccount){
            return ['code'=>0,'msg'=>'Transfer address cannot be blank'];
        }
        if ($amount <= 0){
            return ['code'=>0,'msg'=>'Incorrect transfer quantity'];
        }
        $toAccount = Utils::toChecksumAddress($toAccount);
        $amount = Utils::decToHex(Utils::ethToWei($amount));
        $wallet = Wallet::createByPrivate($key);
        try {

            $data = [
                'nonce'=>$web3->getTransactionCount($wallet->getAddress()),
                'gasPrice' => $web3->gasPrice(),
                'gas' => dechex(hexdec(
                        $web3->estimateGas($toAccount, 0x0, $wallet->getAddress(), null, $amount
                        )) * 1.5),
                'to' => $toAccount,
                'value' => $amount,
                'data' => '0x',
            ];
            $signature = $wallet->sign(Utils::rawEncode($data));
            //dd($signature);
            $chainId = 0;
            $data['v'] = dechex($signature->recoveryParam + 27 + ($chainId ? $chainId * 2 + 8 : 0));
            $data['r'] = $signature->r->toString('hex');
            $data['s'] = $signature->s->toString('hex');
            $signRaw = Utils::add0x(Utils::rawEncode($data));

            $hash = $web3->sendRawTransaction($signRaw);
            return ['code'=>1,'hash'=>$hash,'msg'=>'Successful'];
        } catch (\Exception $e) {
            return ['code'=>0,'msg'=>$e->getMessage()];
        }


    }








}