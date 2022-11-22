<?php
include("conexion.php");
date_default_timezone_set("America/Bogota");
$condicion = $_POST["condicion"];
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('H:i:s');

if($condicion=='table1'){
	$pagina = $_POST["pagina"];
	$consultasporpagina = $_POST["consultasporpagina"];
	$filtrado = $_POST["filtrado"];

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (ciudad LIKE "%'.$filtrado.'%" or hora_creacion LIKE "%'.$filtrado.'%" or fecha_creacion LIKE "%'.$filtrado.'%")';
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT * FROM humedad WHERE id != 0 ".$filtrado." ORDER BY id DESC";
	$sql2 = "SELECT * FROM humedad WHERE id != 0 ".$filtrado." ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset;

	$proceso1 = mysqli_query($conexion,$sql1);
	$proceso2 = mysqli_query($conexion,$sql2);
	$conteo1 = mysqli_num_rows($proceso1);
	$paginas = ceil($conteo1 / $consultasporpagina);

	$html = '';

	$html .= '
		<div class="col-xs-12">
	        <table class="table table-bordered">
	            <thead>
		            <tr>
		                <th class="text-center">Ciudad</th>
		                <th class="text-center">Longitud</th>
		                <th class="text-center">Latitud</th>
		                <th class="text-center">Humedad</th>
		                <th class="text-center">Hora</th>
		                <th class="text-center">Fecha</th>
		                <th class="text-center">Opciones</th>
		            </tr>
	            </thead>
				<tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {
			$html .= '
					<tr id="tr_'.$row2["id"].'">
						<td style="text-align:center;">'.$row2["ciudad"].'</td>
						<td style="text-align:center;">'.$row2["lon"].'</td>
						<td style="text-align:center;">'.$row2["lat"].'</td>
						<td style="text-align:center;">'.$row2["humedad"].'</td>
						<td style="text-align:center;">'.$row2["hora_creacion"].'</td>
						<td style="text-align:center;">'.$row2["fecha_creacion"].'</td>
						<td class="text-center" nowrap="nowrap">
							<a href="https://www.google.com/maps/@'.$row2["lat"].','.$row2["lon"].',11z" target="_blank">
								<button type="button" class="btn btn-info">Ver Mapa</button>
							</a>
						</td>
					</tr>
		   ';
		}
	}else{
		$html .= '<tr><td colspan="7" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
	}

	$html .= '
	            </tbody>
	        </table>
	        <nav>
	            <div class="row">
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Mostrando '.$conteo1.' Datos disponibles</p>
	                </div>
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Página '.$pagina.' de '.$paginas.' </p>
	                </div> 
	                <div class="col-xs-12 col-sm-4">
			            <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
							<ul class="pagination">
	';
	
	if ($pagina > 1) {
		$html .= '
								<li class="page-item">
									<a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
										<span aria-hidden="true">Anterior</span>
									</a>
								</li>
		';
	}

	$diferenciapagina = 3;
	
	/*********MENOS********/
	if($pagina==2){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	}else if($pagina==3){
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
	';
	}else if($pagina>=4){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-3).');" href="#"">
			                            '.($pagina-3).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	} 

	/*********MAS********/
	$opcionmas = $pagina+3;
	if($paginas==0){
		$opcionmas = $paginas;
	}else if($paginas>=1 and $paginas<=4){
		$opcionmas = $paginas;
	}
	
	for ($x=$pagina;$x<=$opcionmas;$x++) {
		$html .= '
			                    <li class="page-item 
		';

		if ($x == $pagina){ 
			$html .= '"active"';
		}

		$html .= '">';

		$html .= '
			                        <a class="page-link" onclick="paginacion1('.($x).');" href="#"">'.$x.'</a>
			                    </li>
		';
	}

	if ($pagina < $paginas) {
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina+1).');" href="#"">
			                            <span aria-hidden="true">Siguiente</span>
			                        </a>
			                    </li>
		';
	}

	$html .= '

						</ul>
					</nav>
				</div>
	        </nav>
	    </div>
	';

	$datos = [
		"estatus"	=> "ok",
		"html"	=> $html,
		"sql2"	=> $sql2,
		"sql1"	=> $sql1,
	];
	echo json_encode($datos);
}

if($condicion=='guardar1'){
	$ciudad = $_POST["ciudad"];
	$lon = $_POST["lon"];
	$lat = $_POST["lat"];
	$humedad = $_POST["humedad"];
	if($ciudad=="4166232"){
		$ciudad = "Miami";
	}else if($ciudad=="4167147"){
		$ciudad = "Orlando";
	}else if($ciudad=="5128638"){
		$ciudad = "New York";
	}
	$sql1 = "INSERT INTO humedad (ciudad,lon,lat,humedad,hora_creacion,fecha_creacion) VALUES ('$ciudad','$lon','$lat','$humedad','$hora_creacion','$fecha_creacion')";
	$proceso1 = mysqli_query($conexion,$sql1);
	$datos = [
		"estatus"	=> "ok",
		"sql"	=> $sql1,
	];
	echo json_encode($datos);
}
