<?php
class PGEncryption {
    var $skey   = "super_enetviet_2016_ngockieu"; // you can change it

    public function encode($sData)
    {
        $sResult = '';
        for ($i = 0; $i < strlen($sData); $i++) {
            $sChar = substr($sData, $i, 1);
            $skeyChar = substr($this->skey, ($i % strlen($this->skey)) - 1, 1);
            $sChar = chr(ord($sChar) + ord($skeyChar));
            $sResult .= $sChar;
        }
        $result = $this->encode_base64($sResult);
        return $result;
    }

    public function decode($sData)
    {
        $sResult = '';
        $sData = $this->decode_base64($sData);
        for ($i = 0; $i < strlen($sData); $i++) {
            $sChar = substr($sData, $i, 1);
            $skeyChar = substr($this->skey, ($i % strlen($this->skey)) - 1, 1);
            $sChar = chr(ord($sChar) - ord($skeyChar));
            $sResult .= $sChar;
        }
        return $sResult;
    }

    public function encode_base64($sData)
    {
        $sBase64 = base64_encode($sData);
        return strtr($sBase64, '+/', '-_');
    }

    public function decode_base64($sData)
    {
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64);
    }
}