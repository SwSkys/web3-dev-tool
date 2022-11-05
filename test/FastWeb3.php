<?php
namespace Web3Tool;
require_once '../vendor/autoload.php';

require_once '../src/Web3.php';
require_once '../src/FastWeb3.php';
require_once '../src/Utils.php';
require_once '../src/Wallet.php';
require_once '../src/Contract.php';
require_once '../src/Quantity.php';
require_once '../src/RLP/RLP.php';
require_once '../src/RLP/Buffer.php';
// 输出真实的钱包地址
//dd(FastWeb3::getBscTestWeb3());
//dd(FastWeb3::transferERC20(FastWeb3::getBscTestWeb3(),'par_key','0xd34a41d8d91ae9a078b64e97221251c50d309477',0.66,'0x7ef95a0fee0dd31b22626fa2e10ee6a223f8a684',FastWeb3::USDT_ABI));
dd(FastWeb3::transfer(FastWeb3::getBscTestWeb3(),'par_key','0xd34a41d8d91ae9a078b64e97221251c50d309477',0.01));