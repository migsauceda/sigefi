<?php

class Geopocision extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $descripcion;

     public $lat;
	 
     public $lon;
	 
	 public $direccion;

    /**
     *
     * @var integer
    

    /**
     * Initialize method for model.
     */
   


    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Descuentos[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Descuentos
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
