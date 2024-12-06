<?php

class RgAgente extends ObjectModel
{
    public $id_agente;
    public $nombre;
    public $telefono;
    public $categoria;

    public static $definition = [
        'table' => 'rg_agentes',
        'primary' => 'id_agente',
        'fields' => [
            'nombre' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255],
            'telefono' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 20],
            'categoria' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255],
        ],
    ];

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }
}
