<?php
		include("../clases/class_conexion_pg.php");
		//iniciar e instanciar conexion
		$conexion=new Conexion();
		$rol=$_GET['rol'];
		
		$consulta="select mini_sedi.agregarrol('".$rol."')";                

		$sqlp=$conexion->ejecutarProcedimiento($consulta);
?>