<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<title>BrowserTravel</title>
</head>
<body>

<div class="container" style="margin-top:2rem;">
	<div class="row">
		<div class="col-12 text-center" style="font-weight:bold; font-size: 22px;">Consulta de la humedad</div>
		<div class="col-12 mt-2 mb-2">
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_nuevo1">Nueva Consulta</button>
		</div>
		<input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-sede="">
		<div class="col-6 form-group form-check">
			<label for="consultasporpagina" style="color:black; font-weight: bold;">Consultas por Página</label>
			<select class="form-control" id="consultasporpagina" name="consultasporpagina">
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
		</div>
		<div class="col-6 form-group form-check">
			<label for="buscarfiltro" style="color:black; font-weight: bold;">Buscar</label>
			<input type="text" class="form-control" id="buscarfiltro" name="buscarfiltro" autocomplete="off">
		</div>	
		<div class="col-12" id="resultado_table1"></div>
	</div>
</div>

</body>
</html>

<div class="modal fade" id="modal_nuevo1" tabindex="-1" role="dialog" aria-labelledby="modal_nuevo1" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva Consulta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="formulario1">
					<div class="row">
						<div class="col-12 mb-3">
							<label for="ciudad" style="font-weight:bold;">Ciudad de Consulta</label>
							<select class="form-control" id="ciudad" name="ciudad" required>
								<option value="">Seleccione</option>
								<option value="4166232">Miami</option>
								<option value="4167147">Orlando</option>
								<option value="5128638">New York</option>
							</select>
						</div>
						<div class="col-12 text-right">
							<button type="submit" class="btn btn-success">Consultar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="hidden_id" name="hidden_id">

<script src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/popper.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script type="text/javascript">
	$(document).ready(function() {
		filtrar1();
		setInterval('filtrar1()',1000);
	} );

	function filtrar1(){
		var input_consultasporpagina = $('#consultasporpagina').val();
		var input_buscarfiltro = $('#buscarfiltro').val();
		
		$('#datatables').attr({'data-consultasporpagina':input_consultasporpagina})
		$('#datatables').attr({'data-filtrado':input_buscarfiltro})

		var pagina = $('#datatables').attr('data-pagina');
		var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
		var filtrado = $('#datatables').attr('data-filtrado');

		$.ajax({
			type: 'POST',
			url: 'script/crud1.php',
			dataType: "JSON",
			data: {
				"pagina": pagina,
				"consultasporpagina": consultasporpagina,
				"filtrado": filtrado,
				"condicion": "table1",
			},

			success: function(respuesta) {
				//console.log(respuesta);
				if(respuesta["estatus"]=="ok"){
					$('#resultado_table1').html(respuesta["html"]);
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}
		});
	}

	function paginacion1(value){
		$('#datatables').attr({'data-pagina':value})
		filtrar1();
	}

	$('#myModal').on('shown.bs.modal', function () {
	  	$('#myInput').trigger('focus')
	});

	$("#formulario1").on("submit", function(e){
		e.preventDefault();
		var ciudad = $('#ciudad').val();
  		var appid = '2b046cca0526e2522e3a5f4dc6ec9648';
		$.ajax({
			type: 'GET',
			url: 'https://api.openweathermap.org/data/2.5/weather?id='+ciudad+'&appid='+appid,
			dataType: "JSON",
			data: {},

			success: function(respuesta) {
				guardar1(ciudad,respuesta["coord"]["lon"],respuesta["coord"]["lat"],respuesta["main"]["humidity"]);
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}
		});
  	});

	function guardar1(ciudad,lon,lat,humidity){
		$.ajax({
			type: 'POST',
			url: 'script/crud1.php',
			dataType: "JSON",
			data: {
				"ciudad": ciudad,
				"lon": lon,
				"lat": lat,
				"humedad": humidity,
				"condicion": "guardar1",
			},

			success: function(respuesta) {
				console.log(respuesta);
				$('#modal_nuevo1').modal('hide');
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}
		});
	}

</script>