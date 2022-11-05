<?php
namespace Web3Tool;
require_once '../vendor/autoload.php';
require_once '../src/Utils.php';
// 输出真实的钱包地址
var_dump(Utils::toChecksumAddress('0xD15e6102125cf45C36515F978EC68E5b81316666'));