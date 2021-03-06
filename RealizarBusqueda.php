<?php
session_start();

include_once("clases/class_conexion_pg.php");

if (isset($_POST["post_accion"])){
    
    if ($_POST["post_denunciaid"]!= '')
    {
    
        $_SESSION['generales']= 't';
        $_SESSION['denunciante']= 't';
        $_SESSION['denunciado']= 't';   
        $_SESSION['ofendido']= 't';
        $_SESSION['relaciones']= 't';

        $_SESSION["estado"]= "Completando";
        $_SESSION["denunciaid"]= $_POST["post_denunciaid"];

        //agregar registro a la tabla control de estados
        //NOTA: estado: Modificando, se usa para indicar en los proc almac de la bd para no modificar las 
        //tablas pdf del las denuncias
        $sql= "insert into mini_sedi.tbl_controlestados (usr, fecha, denuncia, estado, generales, denunciante, denunciado, ofendido) "
               ."values ('".$_SESSION['usuario']."', now(),".$_SESSION["denunciaid"].",'Modificando', 't', 't', 't', 't');";

        $con= new Conexion();
        $rs= $con->ejecutarComando($sql);

        $_SESSION['CambiarTab']= 1;

        if ($_POST["post_accion"] == "modificar") {
        header("location: ./denuncia/frmExpediente.php?CambiarEstado=NO");  }
        else
            if ($_POST["post_accion"] == "accion")
                header("location: ./actividad/actividadb.php?CambiarEstado=NO");
            else
                header("location: ./reportes/denuncia_pdf.php?denunciaprn=".$_SESSION['denunciaid']);
    }
}
?>

<!-- crea pagina html 95 15 52 03-->
<html>
    <link type="text/css" rel="stylesheet" href="css/Estilos.css"> 
<head>

<!--valida resume del formulario -->
<script type='text/javascript'>
function validar(form)
{
    //si la denunciaid esta vacia es que se presiono botn sig o ant
    if (form.post_denunciaid.value== '')
    {        
        if (form.ant.value== "ant") {form.act.value = parseInt(form.act.value) - 1;}
        if (form.sig.value== "sig") {form.act.value = parseInt(form.act.value) + 1;}
    }
    else
    {
        //saber la accion a tomar
        if (form.accion[0].checked== true){
            //modificar: form.accion[0].checked
            form.post_accion.value= "modificar";
        }
        else{
            if (form.accion[1].checked== true){
                //diligencia: form.accion[1].checked
                form.post_accion.value= "accion";
            }
            else{
                //imprimir: form.accion[2].checked
                form.post_accion.value= "imprimir";                
            }
        }    
    }
    return true;
}
</script>

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
<body>

<!--formulario para invocar la accion -->
<form action="RealizarBusqueda.php" method="POST" id="frm" name="frm" onsubmit="return validar(this)">
    <input type="hidden" id="post_accion" name="post_accion">
    <input type="hidden" id="post_denunciaid" name="post_denunciaid">

    <input type="hidden" id="sql" name="sql">
    <input type="hidden" id="total" name="total">
    
    <input type="hidden" id="sig" name="sig">
    <input type="hidden" id="ant" name="ant">
    
    <input type="hidden" id="act" name="act">
    
    <?php
    //kitar los caracteres \' ke vienen en el like
    $sql= $_POST['sql'];
    $sqltotal= $_POST['total'];
    $Buscar= "\\'";
    $Reemplazar= "'";
    $sql= str_replace($Buscar,$Reemplazar,$sql);
    $sqltotal= str_replace($Buscar,$Reemplazar,$sqltotal);
    
//exit($sql);
    ?>

    <!--guardar valores del sql para los siguientes submit -->
    <script type='text/javascript'>
        document.getElementById('sql').value= "<?php echo $sql; ?>";
        document.getElementById('total').value= "<?php echo $sqltotal; ?>";
        
        <?php 
        if (!isset($_POST['act']))
        {
        ?>
            document.getElementById('act').value= 0;
        <?php
        }
        else
        {
        ?>
            document.getElementById('act').value= <?php echo $_POST['act']; ?>;
        <?php
        }
        ?>
            
    </script>
    
    <!--titulo de la pagina -->
    <br>
    <div align='center'><h2><strong>Resultado de la Búsqueda</strong></h2></div>

    <!-- la accion a realizar con el resultado -->
    <div align='center'>
    <table align='center' border='0' name='marco' class="TablaCaja">
        <tr><td>
        <table align='center' border='0' name='opciones'>
            <tr class="SubTituloCentro"><th align='center'><strong>Acción a realizar con el resultado</strong></th></tr>
            <tr><td>
            <input type='radio' name='accion' value='modificar' checked='checked'>Modificar expediente 
            <input type='radio' name='accion' value='accion'>Agregar diligencia fiscal
            <input type='radio' name='accion' value='imprimir'>Imprimir denuncia
            </td></tr>
            </td></tr>
        </table> <!-- opciones -->
        </tr></td>
    </table> <!-- /marco -->
    </div>

    <br>

    <?php
    //ejecutar el sql
    $objConexion= new Conexion();
    
    //total de registros retornados
    $Cursor= $objConexion->ejecutarComando($sqltotal);
    $registro= pg_fetch_array($Cursor);
    $TotalRegistros= $registro[0];

//    exit($sqltotal);
    //resistros por pagina
    $RegistroPagina= 10;
    
    //pagina actual
    if (isset($_POST['act']))
        $PaginaActual= $RegistroPagina * $_POST['act'];
    else
        $PaginaActual= 0;
    
    if ($PaginaActual < $RegistroPagina || $PaginaActual < 0)
        $PaginaActual= 0;
    
    //registros a listar
    $sql= $sql . " limit $RegistroPagina offset $PaginaActual";
    //echo $sql;
    $rsCursor= $objConexion->ejecutarComando($sql);
    ?>

    <!-- barra para mostrar avance en listado de registros -->
    <table id="barra" border="0" align="center">
        <tr>
            <td><input type="submit" value="Anteriores" id="anterior" 
                       onclick='document.getElementById("ant").value="ant"'></td>
            <td><progress value="0" id="progreso" max="<?php echo $TotalRegistros;?>"></progress></td>
            <td><input type="submit" value="Siguientes" id="siguiente"
                       onclick='document.getElementById("sig").value="sig"'></td>
        </tr>
    </table>
    
    <!-- actualiza la barra -->
    <script type='text/javascript'>
        document.getElementById("progreso").value= "<?php echo $RegistroPagina + $PaginaActual; ?>";
    </script>
    
    <!-- lista los registros encontrados -->
    <table id='tblResultados' align='center' border='1' class="TablaCaja">
    <tr class="SubTituloCentro">
        <th><strong>Número denuncia</strong></th>
        <th><strong>Fecha denuncia</strong></th>
        <th><strong>Delito</strong></th>
        <th><strong>Ofendido</strong></th>
        <th><strong>Imputado</strong></th>
        <th><strong>Denunciante</strong></th>
    </tr>
    </table>
    
    <script type='text/javascript'>        
    var i= 0;
    var Anterior= 0;
    var Fondo="";
    <?php
    $i= 1;
    while ($fila=pg_fetch_array($rsCursor))
    {  
    ?> 
        fila= document.createElement("tr");
        fila.id= "fila"+i;
        fila= document.createElement("tr");
        fila.id= "fila"+i;
        if (Anterior !=  <?php echo $fila['tdenunciaid']; ?>){
            //cambiar fondo
            if (Fondo== "#ffffff"){
                Fondo= "#e6f0ef";
            }
            else{
                 Fondo= "#ffffff";
            }                            
        }
        fila.style.background = Fondo;        
        
        tabla= document.getElementById("tblResultados");
        tabla.appendChild(fila);  
        
        //columna numero de expediente o denuncia
        col1= document.createElement("td");
        col1.id= "col1"+i;
        tr= document.getElementById("fila"+i);
        tr.appendChild(col1);
        
        if (Anterior != <?php echo $fila['tdenunciaid']; ?>){
            btn= document.createElement("input");
            btn.id= "btn"+i;
            btn.type= "submit";
            btn.value= "<?php echo $fila['tdenunciaid']; ?>";
            col1.appendChild(btn);
            
            btn.addEventListener("click", function(){
                document.getElementById('post_denunciaid').value='<?php echo $fila['tdenunciaid']; ?>'});
            
            Anterior= <?php echo $fila['tdenunciaid']; ?>;       
        }   
        
        //columna fecha denuncia
        col2= document.createElement("td");
        col2.id= "col2"+i;
        tr= document.getElementById("fila"+i);
        tr.appendChild(col2);
        
        lbl2= document.createElement("label");
        lbl2.id= "lbl2"+i;
        lbl2.innerHTML= "<?php echo $fila['dfechadenuncia']; ?>";
        col2.appendChild(lbl2); 
        
        //columna delito
        col3= document.createElement("td");
        col3.id= "col3"+i;
        tr= document.getElementById("fila"+i);
        tr.appendChild(col3);        

        lbl3= document.createElement("label");
        lbl3.id= "lbl3"+i;
        lbl3.innerHTML=  "<?php echo $fila['cdescripcion']; ?>";
        col3.appendChild(lbl3); 

        //columna nombre del ofendido
        col4= document.createElement("td");
        col4.id= "col4"+i;
        tr= document.getElementById("fila"+i);
        tr.appendChild(col4);
        
        lbl4= document.createElement("label");
        lbl4.id= "lbl4"+i;
        lbl4.innerHTML= "<?php echo $fila['ofendido']; ?>";
        col4.appendChild(lbl4); 

        //columna imputado
        col5= document.createElement("td");
        col5.id= "col5"+i;
        tr= document.getElementById("fila"+i);
        tr.appendChild(col5);

        lbl5= document.createElement("label");
        lbl5.id= "lbl5"+i;
        lbl5.innerHTML= "<?php echo $fila['imputado']; ?>";
        col5.appendChild(lbl5); 
        
        //columna denunciante
        col6= document.createElement("td");
        col6.id= "col6"+i;
        tr= document.getElementById("fila"+i);
        tr.appendChild(col6);
                
        lbl6= document.createElement("label");
        lbl6.id= "lbl6"+i;
        lbl6.innerHTML= "<?php echo $fila['denunciante']; ?>";
        col6.appendChild(lbl6); 

        i++;
    <?php
    }
    ?>
    </script>      
</form>
</body>
</html>
