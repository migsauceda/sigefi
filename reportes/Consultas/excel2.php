<?php 

header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=Reporte2.xls');

	  include("../../clases/class_conexion_pg.php");
    include_once "../../funciones/php_funciones.php"; 
	

$fechaminima=$_GET['fecha1'];
$fechamaxima=$_GET['fecha2'];




 ?>

  <table id="myexample" class="display nowrap dataTable dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
      <thead class="bg-gray">
        <tr role="row">
               <th style="text-align: center">tdenunciaid</th>
            <th style="text-align: center">fecha_denuncia</th>
            <th style="text-align: center">fecha_hecho</th>
            <th style="text-align: center">depto_denuncia</th>
            <th style="text-align: center">municipio_denuncia</th>
            <th style="text-align: center">imputado</th>
            <th style="text-align: center">edad_imputado</th>
            <th style="text-align: center">sexo_imputado</th>
            <th style="text-align: center">documento_imputado</th>
            <th style="text-align: center">delito</th>
            <th style="text-align: center">arma</th>
            

            
         </tr>
      </thead>
      
      <tbody>
 
          <?php 
  


          
         $consultarNuevo= Busquedaconsultar_imputado_arma($fechaminima, $fechamaxima);
          while ($fila= pg_fetch_array($consultarNuevo)) {
          
           ?>

           <tr>
              <tr>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['n_denuncia'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['fecha_denuncia'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['fecha_hecho'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['depto_denuncia'];?></td>
               <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['municipio_denuncia'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['imputado'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['edad_imputado'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['sexo_imputado'];?></td>
               <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['documento_imputado'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['delito'];?></td>
             <td style="text-align: center; background-color:<?php echo $Color?>"><?php echo $fila['arma'];?></td>
           

             

           </tr>

           <?php 
         }
       
           ?>
     </tbody>
   </table>
