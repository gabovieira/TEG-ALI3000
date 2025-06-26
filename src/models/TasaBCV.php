<?php
// src/models/TasaBCV.php
class TasaBCV {
    public $id;
    public $tasa;
    public $fecha_registro;
    public $origen;

    public function __construct($id, $tasa, $fecha_registro, $origen) {
        $this->id = $id;
        $this->tasa = $tasa;
        $this->fecha_registro = $fecha_registro;
        $this->origen = $origen;
    }
}
