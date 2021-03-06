<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- inclusion de archivos -->
<!--controles para los campos del formulario y conexion-->
<?php 	
    include "../clases/Usuario.php";
    include("../clases/controles/funct_text.php");
    include("../clases/controles/funct_select.php");	
    include("../clases/controles/funct_radio.php");
    include("../clases/controles/funct_check.php");

    include("../clases/class_conexion_pg.php");

    //funciones genericas
    include "../funciones/php_funciones.php";            
        
    session_start();

    if (isset($_SESSION['objUsuario'])){
        $objUsuario= $_SESSION['objUsuario'];  
    }else{
        header("location:index.php");
    }        

?>
<head>
  <title>Asignar Fiscalia</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link type="text/css" rel="stylesheet" href="../css/Estilos.css"> 
    <script type="text/javascript" src="../java_script/funciones.js"></script>

    <!-- jquery -->
    <link href="../java_script/css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <script src="../java_script/js/jquery-1.10.2.js"></script>
    <script src="../java_script/js/jquery-ui-1.10.4.custom.js"></script>
              

    <style type="text/css">
        .ui-datepicker {
            font-size: 11px;
            margin-left:10px
         }
    </style>

    <!-- para validar datos e inicializar variable contador de input en tablas -->
    <script type="text/javascript">
        var i= 0;
        function Validar(Tabla){
            var filas2= document.getElementById("tblAsignar").rows.length
            error= 0;
            
            //validar fechas
            if (Tabla.txtFechaAsignacion.value== "")
            {
                alert("ERROR: Ingrese fecha de asignación");
                error= 2;
            }
            
            
            filas2--;
            //se sabe que la primer fila con datos del imputado es la 4
            while (filas2 > 3)
            {   
                if (document.getElementById("CodImputado").value== '')
                {
                    document.getElementById("CodImputado").value= document.getElementById("txtDenunciadoid"+filas2).value;
                }
                else
                {
                    document.getElementById("CodImputado").value= 
                        document.getElementById("CodImputado").value + "," +
                        document.getElementById("txtDenunciadoid"+filas2).value;
                }
                
                if (document.getElementById("cboFiscal"+filas2).value== 0)
                {
                    error= 1;
                    alert("ERROR: Existe un denunciado sin fiscal.");
                }
                
                if(document.getElementById("CodFiscal").value== '')
                {
                    document.getElementById("CodFiscal").value= " '"+document.getElementById("cboFiscal"+filas2).value+"'";
                }
                else
                {
                document.getElementById("CodFiscal").value= 
                    document.getElementById("CodFiscal").value + ",'" +
                    document.getElementById("cboFiscal"+filas2).value + "'";
                }
                
                filas2--; 
            }

            if (error== 1 || error== 2)
            {
                document.getElementById("CodImputado").value= "";
                document.getElementById("CodFiscal").value= "";
                return false; //existen errores
            }                
            else
                return true;
        }
    </script>
    
    <script type="text/javascript">   
        function InsertarFila(Tabla, Fila, Denunciadotxt, Denunciadoid, Fiscal){
//            alert(Tabla+Fila+Fiscalia+Denunciado);
            var fil=document.getElementById(Tabla).insertRow(Fila);            

            //imputado nombre
            col1= document.createElement("td");             
            txtDenunciado= document.createElement("input");
            txtDenunciado.type= "text";
            txtDenunciado.name= "txtDenunciado"+Fila;
            txtDenunciado.id= "txtDenunciado"+Fila;
            txtDenunciado.size= 30;
            txtDenunciado.value= Denunciadotxt;

            col1.appendChild(txtDenunciado); 
            fil.appendChild(col1);    
            
            //imputado codigo       
            txtDenunciadoid= document.createElement("input");
            txtDenunciadoid.type= "hidden";
            txtDenunciadoid.name= "txtDenunciadoid"+Fila;
            txtDenunciadoid.id= "txtDenunciadoid"+Fila;
            txtDenunciadoid.value= Denunciadoid;

            col1.appendChild(txtDenunciadoid); 
            fil.appendChild(col1);                
            
            //fiscal actual
            col2= document.createElement("td"); 
            txtFiscal= document.createElement("input");
            txtFiscal.type= "text";
            txtFiscal.name= "txtFiscal"+Fila;
            txtFiscal.id= "txtFiscal"+Fila;
            txtFiscal.value= Fiscal;

            col2.appendChild(txtFiscal); 
            fil.appendChild(col2);           
            
            //subbandeja u oficina
            col4= document.createElement("td");
            cbo= document.createElement("select");
            cbo.id="cboOficina"+Fila;
            cbo.name="cboOficina"+Fila;
            opt=document.createElement("option");
            opt.value= "0";
            opt.text= "Seleccion...";
            opt.selected= "selected";
            cbo.appendChild(opt);
            
            col4.appendChild(cbo);                        
            fil.appendChild(col4);            
                //lista de sub-bandejas
                <?php 
                    $data= $objUsuario->getBandejaid();
                    $curSubBandeja= CargarSubBandejas($data);
                    while ($regSubBandeja= pg_fetch_array($curSubBandeja)){ ?>
                        opt= document.createElement("option");
                        opt.value= "<?php echo($regSubBandeja[isubbandejaid]); ?>";
                        opt.text= "<?php echo($regSubBandeja[cdescripcion]); ?>";
                        cbo.appendChild(opt);
                <?php
                    }
                ?>
            //fiscal nueva
            col3= document.createElement("td");
            cbo= document.createElement("select");
            cbo.id="cboFiscal"+Fila;
            cbo.name="cboFiscal"+Fila;
            opt=document.createElement("option");
            opt.value= "0";
            opt.text= "Seleccione...";
            opt.selected= "selected";
            cbo.appendChild(opt);
            
            //cargar todos los fiscales de la fiscalia en la que está
            //el usuario, pues se asume que es el jefe.
            //payphone
            <?php
            $curFiscales= CargarFiscalFiscalia($objUsuario->getSubBandejaid());            
            while ($regFiscales= pg_fetch_array($curFiscales)){
            ?>
                opt=document.createElement("option");
                opt.value= "<?php echo($regFiscales[identidad]); ?>";
                opt.text= "<?php echo($regFiscales[nombrecompleto]); ?>";
                cbo.appendChild(opt);                    
            <?php
            }
            ?>
            col3.appendChild(cbo);                        
            fil.appendChild(col3); 
        }
    </script>    

</head>

<body>

<script type="text/javascript">
    $(function() {
        $( "#txtFechaAsignacion" ).datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });
    });

    function CalDenuncia(){
        $( "#txtFechaAsignacion" ).datepicker();

    }
    
    function Imputados(denuncia, destino){
        CargarImputado(denuncia);
//        alert(destino);
    }
</script>
 
<br><br>
<FORM action="procesaasignarfiscal.php" method="POST" id="frmAsignar" onsubmit=" return Validar(this);">
<Table align="center" width="60%" border="0" id="tblAsignar" class="TablaCaja">
<!-- titulo de la tabla -->
<TR class="SubTituloCentro"><TH colspan="4" align="center"><strong>Asignación de Fiscal por Imputado</strong></TH></TR>

<tr><td colspan="4"></td></tr>

<!-- celdas independientes para la fecha -->
<tr>
    <TD width="30%"><strong>Fecha asignación</strong></TD>
<TD colspan="3"><?php cajaTexto("txtFechaAsignacion",10);?>
</TD>
</tr>

<!-- titulos de las columnas-->
<tr>
<TD><strong>Imputado / Niño infractor</strong></TD>
<TD><strong>Fiscal actual</strong></TD>
<TD><strong>Oficina</strong></TD>
<TD><strong>Fiscal nuevo</strong></TD>
</tr>
</table> <!-- fin tabla de datos -->

<!-- campos ocultos para guardar los codigos de: imputado, fiscalia y denunciaid -->
<input type="hidden" id="CodImputado" name="CodImputado">
<input type="hidden" id="CodFiscal" name="CodFiscal">
<input type="hidden" id="txtDenunciaId" name="txtDenunciaId">
<input type="hidden" id="txtBandejaId" name="txtBandejaId">

<!-- conocer el numero de filas de la tabla -->
<script type="text/javascript">
    document.getElementById("txtDenunciaId").value= "<?php echo $_GET['id']; ?>";
    var tbl= document.getElementById("tblAsignar");
    var filas= tbl.rows.length;
//    alert(document.getElementById("txtDenunciaId").value);
</script>

<!-- ciclo para repetir filas por cada denunciado "personaid","nombrecompleto"-->
<?php
$DenunciaId= $_GET['id'];
$resDenunciado= CargarDenunciados($DenunciaId);

while($registro= pg_fetch_array($resDenunciado))
{  
    //cargar fiscal actual
    $curFiscalA= CargarFiscalActual($DenunciaId, $registro[personaid]); 
    $regFiscalA= pg_fetch_array($curFiscalA);
    ?>

    <!-- crear fila dinamicamente -->
    <script type="text/javascript">
        //Tabla, contador de filas, Denunciadotxt, Denunciadoid, Fiscalia txt
        InsertarFila("tblAsignar", filas++, 
            "<?php echo($registro[nombrecompleto]); ?>", 
            "<?php echo($registro[personaid]); ?>", 
            "<?php echo($regFiscalA[nombrecompleto]); ?>");
    </script>

<?php
} //fin del while
?>  

<br>
<!-- tabla para mostrar los botones -->
<table align="center">
  <tbody>
    <tr>
      <td><INPUT type="submit" name="btnSubmit" value="Guardar datos"></td>
      <td><INPUT type="reset" name="btnReset" value="Limpiar campos"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>