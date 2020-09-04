<?php
	require_once("menus/menu_reporteria.php");

	$denuncia = $_POST["denuncia"];
	$nombresOfendido = $_POST["nombreOfendido"];
  $apellidosOfendido = $_POST["apellidoOfendido"];

	$vista = "SELECT DISTINCT actividad, dfecha, etapa, nombres, apellidos FROM mini_sedi.vw_reporte_ofendidos
						WHERE tdenunciaid = '$denuncia' AND cnombres = '$nombresOfendido' AND capellidos = '$apellidosOfendido';";
  $resultado = ejecutarQuery($vista);
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Reporte actividades ofendido</title>
 	<h2 align="center"><b>Ofendido:</b> <?php echo $nombresOfendido." ".$apellidosOfendido; ?>
    </h2> <br><br>
 </head>
 <body>

 	<!-- Advanced Tables -->
    <div align="left" class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>ACTIVIDAD</th>
                        <th>FECHA DE REGISTRO</th>
                        <th>ETAPA</th>
                        <th>FISCAL</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                	while ($row = pg_fetch_array($resultado)) {

                		echo "
                			<tr>
                			<td align=center>".$row["actividad"]."</td>
                			<td align=center>".$row["dfecha"]."</td>
                			<td align=center>".$row["etapa"]."</td>
                			<td align=center>".$row["nombres"]." ".$row["apellidos"]."</td>
                			</tr>
                		";
                	}
                ?>
                </tbody>
            </table>
        </div>
    </div>

 	<div align="center">
 		<h4>
 	 		<a href="javascript:window.history.back();"><b>Regresar</b></a>
 	 	</h4>

 	</div>
 	<br>

 </body>
 </html>
