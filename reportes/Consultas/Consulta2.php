<?php 
    session_start();
   
    
    include("../../clases/class_conexion_pg.php");
    include_once "../../funciones/php_funciones.php"; 
    
  
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="css/Estilos.css">
        <link type="text/css" rel="stylesheet" href="./css/smoothness/jquery-ui-1.8.12.custom.css"> 
        <script type="text/javascript" src="java_script/jquery-1.5.1.min.js"></script>
        <script type="text/javascript" src="java_script/jquery-ui-1.8.12.custom.min.js"></script>        
        <title>Consulta</title>
        
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
           
            
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    </head>
    
    <style type="text/css">
            body,html{background-color:transparent;}
            .Fila_0{
                background-color: #FFFFFF;
            }
            .Fila_1{
                background-color: #F8F8FF;
            }              
    </style>    
    
<body class="Fondo">
<div class="" style="margin-left:10em; margin-right:10em">
<section style="background-color: #F9FAFA;">
  <div class="center container">
    <form method="POST" >


      <div  class="form-group">
        <label>Seleccione fecha Minima </label>
        <input type="date" class="form-control" name="fechaMinima">

      </div>

      <div  class="form-group">
        <label>Seleccione fecha Maxima</label>
         <input type="date" class="form-control" name="fechaMaxima">
      </div>
      
      <div style="text-align: center" class="form-group"> 
        <button type="text" name="BtnAceptar" class="btn btn-primary" style="padding-left:80px;padding-right:80px  ">Aceptar</button>
      </div>
    </form>
  </div>


</section>

</div>
<div style="background-color:#FFF;">
  
   <div id="example_wrapper" class="dataTables_wrapper table-responsive">
  
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
   if (isset($_POST['BtnAceptar'])) {
    
          $fechaminima=$_POST['fechaMinima'];
          $fechamaxima=$_POST['fechaMaxima'];
  


          echo '<div class="row">
        <div class="col text-center">
          <a href="excel2.php?fecha1='.$fechaminima.'&fecha2='.$fechamaxima.'">
            Generar XLS
          </a>
        </div>
      </div>';          

        

          if (strtotime($fechamaxima) < strtotime($fechaminima)) {
             echo "<script>";
    echo "alert('ASEGURESE QUE LAS FECHAS 


    -ESTEN CORRECTAS');";
    echo "window.location = 'Consulta2.php';";
    echo "</script>";
          }

          

          
         $consultarNuevo= Busquedaconsultar_imputado_arma($_POST['fechaMinima'], $_POST['fechaMaxima']);
          while ($fila= pg_fetch_array($consultarNuevo)) {
          
           ?>

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
       }
           ?>


     </tbody>
   </table>

 














  <script type="text/javascript">
     $(document).ready(function(){
    $('#myexample').DataTable(
        {
            "searching": true,
            paging: true


    });
    
   $(".tr-show").click(function(){
        // $("#myexample > tbody").append('<tr><td scope="col" colspan="13" rowspan="1">Rozwinięcie / dodanie dodatkowej treśći</td></tr>');
        
        $("#updated_contacts").append($(".addElement span").clone());        
    

    });
    
});
   </script>


    <!-- Dialog help -->
 <script>  
 $(document).ready(function(){  
      $('#employee_data').DataTable();  
 });  
 </script>  
  <script >
    
function habilitar(form)
{
if(form.opcion.options[1].selected || form.opcion.options[2].selected==true || form.opcion.options[3].selected==true || form.opcion.options[4].selected==true)
  {
     document.getElementById('valor').disabled=false;
     
   }
else
   {
     document.getElementById('valor').disabled=true;
    
     
   }
}
  </script>
  <script>
  $(document).ready(function(){
   var val= document.getElementById('valor').value;
  $('#valor').on('keyup', function(){
   
    if (val=="") {
      document.getElementById('buscar').disabled=false;
    }
  })
  }); 
 </script>

            <!--====== Scripts -->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/material.min.js"></script>
    <script src="../js/ripples.min.js"></script>
    <script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
        $.material.init();
    </script>

    </body>
</html>
