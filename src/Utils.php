<?php

namespace Web3Tool;

use Exception;
use InvalidArgumentException;
use kornrunner\Keccak;
use phpseclib\Math\BigInteger as BigNumber;
use stdClass;
use Web3Tool\CryptoCurrencyPHP\Signature;

class Utils
{

    const SHA3_NULL_HASH = 'c5d2460186f7233c927e7db2dcc703c0e500b653ca82273b7bfad8045d85a470';

    /**
     * UNITS
     * from ethjs-unit
     *
     * @const array
     */
    const UNITS = [
        'noether' => '0',
        'wei' => '1',
        'kwei' => '1000',
        'Kwei' => '1000',
        'babbage' => '1000',
        'femtoether' => '1000',
        'mwei' => '1000000',
        'Mwei' => '1000000',
        'lovelace' => '1000000',
        'picoether' => '1000000',
        'gwei' => '1000000000',
        'Gwei' => '1000000000',
        'shannon' => '1000000000',
        'nanoether' => '1000000000',
        'nano' => '1000000000',
        'szabo' => '1000000000000',
        'microether' => '1000000000000',
        'micro' => '1000000000000',
        'finney' => '1000000000000000',
        'milliether' => '1000000000000000',
        'milli' => '1000000000000000',
        'ether' => '1000000000000000000',
        'kether' => '1000000000000000000000',
        'grand' => '1000000000000000000000',
        'mether' => '1000000000000000000000000',
        'gether' => '1000000000000000000000000000',
        'tether' => '1000000000000000000000000000000'
    ];

    public static function remove0x($value)
    {
        if (strtolower(substr($value, 0, 2)) == '0x') {
            return substr($value, 2);
        }
        return $value;
    }

    public static function add0x($value): string
    {
        return '0x' . self::remove0x($value);
    }

    public static function pubKeyToAddress($pubkey): string
    {
        return '0x' . substr(Keccak::hash(substr(hex2bin($pubkey), 1), 256), 24);
    }

    /**
     * RLPencode
     */
    public static function rawEncode(array $input): string
    {
        $rlp = new RLP\RLP;
        $data = [];
        foreach ($input as $item) {
            // If the value is invalid: 0, 0x0, list it as an empty string
            $data[] = $item && hexdec(self::remove0x($item)) != 0 ? self::add0x($item) : '';
        }
        return $rlp->encode($data)->toString('hex');
    }

    /**
     *
     * @param string $str
     * @param int $bit
     * @return string
     */
    public static function fill0(string $str, int $bit = 64): string
    {
        $str_len = strlen($str);
        $zero = '';
        for ($i = $str_len; $i < $bit; $i++) {
            $zero .= "0";
        }
        $real_str = $zero . $str;
        return $real_str;
    }

    /**
     * ether to wei
     */
    public static function ethToWei($value, $hex = false): string
    {
        $value = bcmul($value, '1000000000000000000');
        if ($hex) {
            return self::decToHex($value, $hex);
        }
        return $value;
    }

    /**
     * wei to ether
     */
    public static function weiToEth($value, $hex = false): ?string
    {
        if (strtolower(substr($value, 0, 2)) == '0x') {
            $value = self::hexToDec(self::remove0x($value));
        }
        $value = bcdiv($value, '1000000000000000000', 18);
        if ($hex) {
            return '0x' . self::decToHex($value);
        }
        return $value;
    }

    /**
     * change to hex(0x)
     * @param string|number $value
     * @param boolean $mark
     * @return string
     */
    public static function decToHex($value, $mark = true): string
    {
        $hexvalues = [
            '0', '1', '2', '3', '4', '5', '6', '7',
            '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'
        ];
        $hexval = '';
        while ($value != '0') {
            $hexval = $hexvalues[bcmod($value, '16')] . $hexval;
            $value = bcdiv($value, '16', 0);
        }

        return ($mark ? '0x' . $hexval : $hexval);
    }

    /**
     * change to hex(0x)
     * @param string $number hex number
     * @return string
     */
    public static function hexToDec(string $number): string
    {
        // have 0x,remove it
        $number = self::remove0x(strtolower($number));
        $decvalues = [
            '0' => '0', '1' => '1', '2' => '2',
            '3' => '3', '4' => '4', '5' => '5',
            '6' => '6', '7' => '7', '8' => '8',
            '9' => '9', 'a' => '10', 'b' => '11',
            'c' => '12', 'd' => '13', 'e' => '14',
            'f' => '15'];
        $decval = '0';
        $number = strrev($number);
        for ($i = 0; $i < strlen($number); $i++) {
            $decval = bcadd(bcmul(bcpow('16', $i, 0), $decvalues[$number[$i]]), $decval);
        }
        return $decval;
    }

    /**
     * jsonMethodToString
     *
     * @param stdClass|array $json
     * @return string
     */
    public static function jsonMethodToString($json): string
    {
        if ($json instanceof stdClass) {
            // one way to change whole json stdClass to array type
            // $jsonString = json_encode($json);

            // if (JSON_ERROR_NONE !== json_last_error()) {
            //     throw new InvalidArgumentException('json_decode error: ' . json_last_error_msg());
            // }
            // $json = json_decode($jsonString, true);

            // another way to change whole json to array type but need the depth
            // $json = self::jsonToArray($json, $depth)

            // another way to change json to array type but not whole json stdClass
            $json = (array)$json;
            $typeName = [];

            foreach ($json['inputs'] as $param) {
                if (isset($param->type)) {
                    $typeName[] = $param->type;
                }
            }
            return $json['name'] . '(' . implode(',', $typeName) . ')';
        } elseif (!is_array($json)) {
            throw new \Exception('jsonMethodToString json must be array or stdClass.');
        }
        if (isset($json['name']) && strpos($json['name'], '(') > 0) {
            return $json['name'];
        }
        $typeName = [];

        foreach ($json['inputs'] as $param) {
            if (isset($param['type'])) {
                $typeName[] = $param['type'];
            }
        }
        return $json['name'] . '(' . implode(',', $typeName) . ')';
    }

    /**
     * sha3
     * keccak256
     *
     * @param string $value
     * @return string
     */
    public static function sha3(string $value): ?string
    {
        if (strpos($value, '0x') === 0) {
            $value = self::hexToBin($value);
        }
        $hash = Keccak::hash($value, 256);
        if ($hash === self::SHA3_NULL_HASH) {
            return null;
        }
        return '0x' . $hash;
    }

    /**
     * hexToBin
     *
     * @param string
     * @return string
     * @throws \Exception
     */
    public static function hexToBin($value): string
    {
        if (!is_string($value)) {
            throw new \Exception('The value to hexToBin function must be string.');
        }
        if (self::isZeroPrefixed($value)) {
            $count = 1;
            $value = str_replace('0x', '', $value, $count);
        }
        return pack('H*', $value);
    }

    /**
     * isZeroPrefixed
     *
     * @param string
     * @return bool
     */
    public static function isZeroPrefixed($value): bool
    {
        if (!is_string($value)) {
            throw new \Exception('The value to isZeroPrefixed function must be string.');
        }
        return (strpos($value, '0x') === 0);
    }

    public static function hexToString($value){
        return pack("H*",$value);
    }

    /**
     * toHex
     * Encoding string or integer or numeric string(is not zero prefixed) or big number to hex.
     *
     * @param string|int|BigNumber $value
     * @param bool $isPrefix
     * @return string
     */
    public static function toHex($value, bool $isPrefix=false): string
    {
        if (is_numeric($value)) {
            // turn to hex number
            $bn = self::toBn($value);
            $hex = $bn->toHex(true);
            $hex = preg_replace('/^0+(?!$)/', '', $hex);
        } elseif (is_string($value)) {
            $value = self::stripZero($value);
            $hex = implode('', unpack('H*', $value));
        } elseif ($value instanceof BigNumber) {
            $hex = $value->toHex(true);
            $hex = preg_replace('/^0+(?!$)/', '', $hex);
        } else {
            throw new InvalidArgumentException('The value to toHex function is not support.');
        }
        if ($isPrefix) {
            return '0x' . $hex;
        }
        return $hex;
    }

    /**
     * stripZero
     *
     * @param string $value
     * @return string
     */
    public static function stripZero($value): string
    {
        if (self::isZeroPrefixed($value)) {
            $count = 1;
            return str_replace('0x', '', $value, $count);
        }
        return $value;
    }

    /**
     * isNegative
     *
     * @param string
     * @return bool
     */
    public static function isNegative($value): bool
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isNegative function must be string.');
        }
        return (strpos($value, '-') === 0);
    }

    /**
     * isAddress
     *
     * @param string $value
     * @return bool
     */
    public static function isAddress($value): bool
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isAddress function must be string.');
        }
        if (preg_match('/^(0x|0X)?[a-f0-9A-F]{40}$/', $value) !== 1) {
            return false;
        } elseif (preg_match('/^(0x|0X)?[a-f0-9]{40}$/', $value) === 1 || preg_match('/^(0x|0X)?[A-F0-9]{40}$/', $value) === 1) {
            return true;
        }
        return self::isAddressChecksum($value);
    }

    /**
     * isAddressChecksum
     *
     * @param string $value
     * @return bool
     */
    public static function isAddressChecksum($value): bool
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isAddressChecksum function must be string.');
        }
        $value = self::stripZero($value);
        $hash = self::stripZero(self::sha3(mb_strtolower($value)));

        for ($i = 0; $i < 40; $i++) {
            if (
                (intval($hash[$i], 16) > 7 && mb_strtoupper($value[$i]) !== $value[$i]) ||
                (intval($hash[$i], 16) <= 7 && mb_strtolower($value[$i]) !== $value[$i])
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * isHex
     *
     * @param string $value
     * @return bool
     */
    public static function isHex($value): bool
    {
        return (is_string($value) && preg_match('/^(0x)?[a-f0-9]*$/', $value) === 1);
    }

    /**
     * toString
     *
     * @param mixed $value
     * @return string
     */
    public static function toString($value): string
    {
        $value = (string) $value;

        return $value;
    }

    /**
     * toWei
     * Change number from unit to wei.
     * For example:
     * $wei = Utils::toWei('1', 'kwei');
     * $wei->toString(); // 1000
     *
     * @param BigNumber|string|int $number
     * @param string $unit
     * @return BigNumber
     */
    public static function toWei($number, string $unit): BigNumber
    {
        $bn = self::toBn($number);

        if (!is_string($unit)) {
            throw new InvalidArgumentException('toWei unit must be string.');
        }
        if (!isset(self::UNITS[$unit])) {
            throw new InvalidArgumentException('toWei doesn\'t support ' . $unit . ' unit.');
        }
        $bnt = new BigNumber(self::UNITS[$unit]);

        if (is_array($bn)) {
            // fraction number
            list($whole, $fraction, $fractionLength, $negative1) = $bn;

            if ($fractionLength > strlen(self::UNITS[$unit])) {
                throw new InvalidArgumentException('toWei fraction part is out of limit.');
            }
            $whole = $whole->multiply($bnt);

            // There is no pow function in phpseclib 2.0, only can see in dev-master
            // Maybe implement own biginteger in the future
            // See 2.0 BigInteger: https://github.com/phpseclib/phpseclib/blob/2.0/phpseclib/Math/BigInteger.php
            // See dev-master BigInteger: https://github.com/phpseclib/phpseclib/blob/master/phpseclib/Math/BigInteger.php#L700
            // $base = (new BigNumber(10))->pow(new BigNumber($fractionLength));

            // So we switch phpseclib special global param, change in the future
            switch (MATH_BIGINTEGER_MODE) {
                case $whole::MODE_GMP:
                    static $two;
                    $powerBase = gmp_pow(gmp_init(10), (int) $fractionLength);
                    break;
                case $whole::MODE_BCMATH:
                    $powerBase = bcpow('10', (string) $fractionLength, 0);
                    break;
                default:
                    $powerBase = pow(10, (int) $fractionLength);
                    break;
            }
            $base = new BigNumber($powerBase);
            $fraction = $fraction->multiply($bnt)->divide($base)[0];

            if ($negative1 !== false) {
                return $whole->add($fraction)->multiply($negative1);
            }
            return $whole->add($fraction);
        }

        return $bn->multiply($bnt);
    }

    /**
     * toEther
     * Change number from unit to ether.
     * For example:
     * list($bnq, $bnr) = Utils::toEther('1', 'kether');
     * $bnq->toString(); // 1000
     *
     * @param BigNumber|string|int $number
     * @param string $unit
     * @return array
     */
    public static function toEther($number, $unit): array
    {
        // if ($unit === 'ether') {
        //     throw new InvalidArgumentException('Please use another unit.');
        // }
        $wei = self::toWei($number, $unit);
        $bnt = new BigNumber(self::UNITS['ether']);

        return $wei->divide($bnt);
    }

    /**
     * fromWei
     * Change number from wei to unit.
     * For example:
     * list($bnq, $bnr) = Utils::fromWei('1000', 'kwei');
     * $bnq->toString(); // 1
     *
     * @param BigNumber|string|int $number
     * @param string $unit
     * @return BigNumber
     */
    public static function fromWei($number, $unit): BigNumber
    {
        $bn = self::toBn($number);

        if (!is_string($unit)) {
            throw new InvalidArgumentException('fromWei unit must be string.');
        }
        if (!isset(self::UNITS[$unit])) {
            throw new InvalidArgumentException('fromWei doesn\'t support ' . $unit . ' unit.');
        }
        $bnt = new BigNumber(self::UNITS[$unit]);

        return $bn->divide($bnt);
    }


    /**
     * jsonToArray
     *
     * @param stdClass|array|string $json
     * @param int $depth
     * @return array
     */
    public static function jsonToArray($json, $depth=1): array
    {
        if (!is_int($depth) || $depth <= 0) {
            throw new InvalidArgumentException('jsonToArray depth must be int and depth must bigger than 0.');
        }
        if ($json instanceof stdClass) {
            $json = (array) $json;
            $typeName = [];

            if ($depth > 1) {
                foreach ($json as $key => $param) {
                    if (is_array($param)) {
                        foreach ($param as $subKey => $subParam) {
                            $json[$key][$subKey] = self::jsonToArray($subParam, $depth-1);
                        }
                    } elseif ($param instanceof stdClass) {
                        $json[$key] = self::jsonToArray($param, $depth-1);
                    }
                }
            }
            return $json;
        } elseif (is_array($json)) {
            if ($depth > 1) {
                foreach ($json as $key => $param) {
                    if (is_array($param)) {
                        foreach ($param as $subKey => $subParam) {
                            $json[$key][$subKey] = self::jsonToArray($subParam, $depth-1);
                        }
                    } elseif ($param instanceof stdClass) {
                        $json[$key] = self::jsonToArray($param, $depth-1);
                    }
                }
            }
        } elseif (is_string($json)) {
            $json = json_decode($json, true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new InvalidArgumentException('json_decode error: ' . json_last_error_msg());
            }
            return $json;
        } else {
            throw new InvalidArgumentException('The json param to jsonToArray must be array or stdClass or string.');
        }
        return $json;
    }

    /**
     * toBn
     * Change number or number string to bignumber.
     *
     * @param BigNumber|string|int $number
     * @return array|BigNumber
     */
    public static function toBn($number)
    {
        if ($number instanceof BigNumber){
            $bn = $number;
        } elseif (is_int($number)) {
            $bn = new BigNumber($number);
        } elseif (is_numeric($number)) {
            $number = (string) $number;

            if (self::isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigNumber(-1);
            }
            if (strpos($number, '.') > 0) {
                $comps = explode('.', $number);

                if (count($comps) > 2) {
                    throw new InvalidArgumentException('toBn number must be a valid number.');
                }
                $whole = $comps[0];
                $fraction = $comps[1];

                return [
                    new BigNumber($whole),
                    new BigNumber($fraction),
                    strlen($comps[1]),
                    isset($negative1) ? $negative1 : false
                ];
            } else {
                $bn = new BigNumber($number);
            }
            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }
        } elseif (is_string($number)) {
            $number = mb_strtolower($number);

            if (self::isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigNumber(-1);
            }
            if (self::isZeroPrefixed($number) || preg_match('/[a-f]+/', $number) === 1) {
                $number = self::stripZero($number);
                $bn = new BigNumber($number, 16);
            } elseif (empty($number)) {
                $bn = new BigNumber(0);
            } else {
                throw new InvalidArgumentException('toBn number must be valid hex string.');
            }
            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }
        } else {
            throw new InvalidArgumentException('toBn number must be BigNumber, string or int.');
        }
        return $bn;
    }

    /**
     * @param string $address
     * @return string
     * @throws \Exception
     */
    protected static function checksumEncode(string $address): string
    {
        $checksum = Keccak::hash($address, 256);

        for ($i = 0; $i < strlen($address); $i++) {
            if (intval($checksum[$i], 16) >= 8) {
                $address[$i] = strtoupper($address[$i]);
            }
        }

        return $address;
    }

    /**
     * @param string $pubKey
     * @param bool $checksum
     * @return string
     * @throws \Exception
     */
    public static function encode(string $pubKey, bool $checksum = true): string
    {
        if (ctype_xdigit($pubKey)) {
            $pubKey = hex2bin($pubKey);
        }

        $length = strlen($pubKey);

        if ($length == 65 && $pubKey[0] == "\x04") {
            $pubKey = substr($pubKey, 1);
        } elseif ($length != 64) {
            throw new Exception('Invalid public key.');
        }

        $hash = Keccak::hash($pubKey, 256);
        $address = substr($hash, -40);

        if ($checksum) {
            $address = static::checksumEncode($address);
        }

        return '0x' . $address;
    }

    /**
     * @param string $address
     * @return bool
     * @throws \Exception
     */
    public static function isValid(string $address): bool
    {
        if (strlen($address) != 42 || strpos($address, '0x') !== 0) {
            return false;
        }

        $address = substr($address, 2);

        if (!ctype_xdigit($address)) {
            return false;
        }

        $lower = strtolower($address);

        if ($lower == $address) {
            return true;
        }

        return $address == static::checksumEncode($lower);
    }

    /**
     * @param string $address
     * @return string
     * @throws \Exception
     */
    public static function toChecksumAddress(string $address): string
    {
        $address = strtolower(substr($address, 2));

        return '0x' . static::checksumEncode($address);
    }

    public static function parseAddressArrDataInfo($info)
    {
        $info = self::remove0x($info);
        if (strlen($info) % 64 !== 0){
            return [];
        }
        $arr = str_split($info,64);
        if (count($arr) < 2){
            return [];
        }
        $arr = array_splice($arr,2);
        foreach ($arr as $k=>$hash){
            $arr[$k] = self::hexToDec($hash);
        }
        return $arr;
    }

    public static function personal_ecRecover($msg, $signed) {
        $personal_prefix_msg = "\x19Ethereum Signed Message:\n". strlen($msg). $msg;
        $hex = Utils::keccak256($personal_prefix_msg);
        return Utils::ecRecover($hex, $signed);
    }

    public static function ecRecover($hex, $signed) {
        $rHex   = substr($signed, 2, 64);
        $sHex   = substr($signed, 66, 64);
        $vValue = hexdec(substr($signed, 130, 2));
        $messageHex       = substr($hex, 2);
        $messageByteArray = unpack('C*', hex2bin($messageHex));
        $messageGmp       = gmp_init("0x" . $messageHex);
        $r = $rHex;		//hex string without 0x
        $s = $sHex; 	//hex string without 0x
        $v = $vValue; 	//27 or 28

        //with hex2bin it gives the same byte array as the javascript
        $rByteArray = unpack('C*', hex2bin($r));
        $sByteArray = unpack('C*', hex2bin($s));
        $rGmp = gmp_init("0x" . $r);
        $sGmp = gmp_init("0x" . $s);

        if ($v != 27 && $v != 28) {
            $v += 27;
        }

        $recovery = $v - 27;
        if ($recovery !== 0 && $recovery !== 1) {
            throw new Exception('Invalid signature v value');
        }

        $publicKey = Signature::recoverPublicKey($rGmp, $sGmp, $messageGmp, $recovery);
        $publicKeyString = $publicKey["x"] . $publicKey["y"];

        return '0x'. substr(Utils::keccak256(hex2bin($publicKeyString)), -40);
    }

    public static function strToHex($string)
    {
        $hex = unpack('H*', $string);
        return '0x' . array_shift($hex);
    }

    public static function keccak256($str) {
        return '0x'. Keccak::hash($str, 256);
    }

    public static function parseInput(string $hash): array
    {
        if (empty($hash)) return [];
        if (strlen($hash) == 138){
            $funcName = substr($hash,0,10);
            $address = '0x'.strtolower(substr($hash,34,40));
            $amount = substr($hash,74);
            return [
                'funcName'=>$funcName,
                'address'=>Utils::toChecksumAddress($address),
                'amount'=>Utils::hexToDec($amount),
            ];
        }
        return [];
    }

}
