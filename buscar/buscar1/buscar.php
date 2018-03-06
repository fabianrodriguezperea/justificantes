<?php
//Archivo de conexión a la base de datos
require ('conexion.php');


//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";


//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda)) {

	//Selecciona todo de la tabla mmv001 
	//donde el nombre sea igual a $consultaBusqueda, 
	//o el apellido sea igual a $consultaBusqueda, 
	//o $consultaBusqueda sea igual a nombre + (espacio) + apellido
	error_reporting(0);
	$consulta = mysqli_query($conexion, "SELECT * FROM 1er_semestre
	WHERE nombre COLLATE UTF8_SPANISH_CI LIKE '%$consultaBusqueda%' 
	OR No_control COLLATE UTF8_SPANISH_CI LIKE '%$consultaBusqueda%'
	OR grupo LIKE '%$consultaBusqueda%'
	OR CONCAT(nombre,' ',No_control, motivo) COLLATE UTF8_SPANISH_CI LIKE '%$consultaBusqueda%'
	");

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysqli_num_rows($consulta);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas === 0) {
		$mensaje = "<p>No hay ningún usuario con ese nombre o No. de control</p>";
	} else {
		//Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
		echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';

		
	$mensaje .= '	<table border="2px" width=70%>
  <tr>
    <th scope="col" width=25%> nombre  </th>
    <th scope="col" width=25%> grupo </th>
    <th scope="col" width=25%> motivo </th>
    <th scope="col" width=15%> No_control </th>
  </tr>
 </table> ';
		
		
		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($resultados = mysqli_fetch_array($consulta)) {
			$nombre = $resultados['nombre'];
			$grupo = $resultados['grupo'];
			$motivo= $resultados['motivo'];
			$No_control = $resultados['No_control'];

			//Output
			$mensaje .= '
			
			<table border="2px" width=70%  >
   <tr>
    <td width=25%>' . $nombre . '</td>
    <td width=25%>' . $grupo . '</td>
    <td width=25%>' . $motivo . '</td>
    <td width=25%>' . $No_control . '</td>
  </tr>
 </table>
			';

		};//Fin while $resultados

	}; //Fin else $filas

};//Fin isset $consultaBusqueda

//Devolvemos el mensaje que tomará jQuery
echo $mensaje;
?>