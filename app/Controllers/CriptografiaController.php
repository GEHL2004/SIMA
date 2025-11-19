<?php

namespace App\Controllers;

class CriptografiaController{

    private $llave;
    private $metodo;

    public function __construct()
    {
        $this->llave = 'ABCD-1234.aer';
        $this->metodo = 'AES-256-CBC';
    }

    public function encriptacion($cadena){
        $ivTamaño = openssl_cipher_iv_length($this->metodo);
        $iv = openssl_random_pseudo_bytes($ivTamaño);
        $clave_encriptada = openssl_encrypt($cadena, $this->metodo, $this->llave, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $clave_encriptada);
    }

    public function desencriptacion($cadena_cifrada){
        $clave_desencriptada = '';
        $cadenamasiv = base64_decode($cadena_cifrada);
        $ivTamaño = openssl_cipher_iv_length($this->metodo);
        $iv = substr($cadenamasiv, 0, $ivTamaño);
        $cadena = substr($cadenamasiv, $ivTamaño);
        $clave_desencriptada = openssl_decrypt($cadena, $this->metodo, $this->llave, OPENSSL_RAW_DATA, $iv);
        return $clave_desencriptada;
    }

}

?>