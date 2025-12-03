<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
	// Si el usuario no está logueado, redirígelo a index.php
	header('Location: index.php');
	exit;
}
$arttipo = "IMPORTACIONES_AE";
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Importaciones - Dirección Técnica</title>
	<link rel="icon" href="dist/img/favicon.ico">
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<!-- Bootstrap 5.3.2 -->
	<link rel="stylesheet" type="text/css" href="plugins/btp/css/bootstrap.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- DataTables Styles -->
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/responsive.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/buttons.bootstrap5.min.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<style>
		.code-input-area {
			min-height: 200px;
			font-family: 'Courier New', monospace;
			border: 2px dashed #007bff;
			border-radius: 10px;
			background-color: #f8f9fa;
		}

		.code-input-area:focus {
			border-color: #0056b3;
			background-color: #ffffff;
			box-shadow: 0 0 10px rgba(0, 123, 255, 0.25);
		}

		.processing-spinner {
			display: none;
		}

		.results-container {
			margin-top: 20px;
		}

		.code-count {
			font-size: 0.9em;
			color: #6c757d;
		}

		.producto-vencido {
			background-color: #f8d7da !important;
			border-color: #f5c6cb !important;
		}

		.producto-vencido:hover {
			background-color: #f1b0b7 !important;
		}

		.fecha-vencida {
			color: #721c24 !important;
			font-weight: bold;
		}

		.badge-vencido {
			background-color: #dc3545;
			animation: pulse-red 2s infinite;
		}

		@keyframes pulse-red {
			0% {
				opacity: 1;
			}

			50% {
				opacity: 0.7;
			}

			100% {
				opacity: 1;
			}
		}

		/* Estilos para que la tabla ocupe todo el ancho */
		.table-responsive {
			width: 100%;
		}

		#resultadosTable {
			width: 100% !important;
			table-layout: fixed;
		}

		#resultadosTable th,
		#resultadosTable td {
			word-wrap: break-word;
			overflow-wrap: break-word;
		}

		/* Asegurar que DataTable ocupe todo el ancho */
		.dataTables_wrapper {
			width: 100%;
		}

		.dataTables_filter {
			text-align: right;
			margin-bottom: 10px;
		}
	</style>
</head>

<body id="body" class="hold-transition sidebar-mini sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<!-- Site wrapper -->
	<div class="wrapper">
		<!-- Preloader -->
		<div class="preloader flex-column justify-content-center align-items-center">
			<img class="animation__wobble" src="dist/img/bbraun.png" alt="B Logo" height="60" width="60">
		</div>

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button">
						<i class="fas fa-bars"></i>
					</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="home.php" class="nav-link">Inicio</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="#" class="nav-link active">Importaciones AE</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="rs_ae.php?arttipo=PRODUCTO AE" class="nav-link">Aesculap</a>
				</li>
			</ul>

			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" data-widget="fullscreen" href="#" role="button">
						<i class="fas fa-expand-arrows-alt"></i>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="cnx/logout.php">
						<i class="fas fa-sign-out-alt"></i> Cerrar Sesión
					</a>
				</li>
			</ul>
		</nav>

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="home.php" class="brand-link">
				<img src="dist/img/bbraun.png" alt="B Logo" class="brand-image img-circle elevation-3"
					style="opacity: .8">
				<span class="brand-text font-weight-light">Dirección Técnica</span>
			</a>
			<!-- Sidebar -->
			<div class="sidebar">
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img id="avatar" name="avatar" src="" class="img-circle elevation-1" alt="User Image">
					</div>
					<div class="info">
						<a href="#"
							class="d-block"><?php echo $_SESSION['nombres'] . ' ' . $_SESSION['apellidos']; ?><br><small><?php echo $_SESSION['area']; ?></small></a>
					</div>
				</div>
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
						data-accordion="false">
						<li class="nav-header">MENU PRINCIPAL</li>
						<li class="nav-item">
							<a href="home.php" class="nav-link">
								<i class="nav-icon fas fa-home"></i>
								<p>
									Inicio
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="importaciones.php" class="nav-link active">
								<i class="nav-icon fas fa-upload"></i>
								<p>Importaciones AE</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-chart-pie"></i>
								<p>
									Maestros
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item ">
									<a href="#" class="nav-link active">
										<i class="far fa-circle nav-icon"></i>
										<p>
											Registros Sanitarios HC
											<i class="right fas fa-angle-left"></i>
										</p>
									</a>
									<ul class="nav nav-treeview">
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA AVITUM" class="nav-link">
												<i class="far fa-dot-circle nav-icon"></i>
												<p>Producto L&iacute;nea AVITUM</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC AIS" class="nav-link">
												<i class="far fa-dot-circle nav-icon"></i>
												<p>Producto L&iacute;nea HC AIS</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC BC" class="nav-link">
												<i class="far fa-dot-circle nav-icon "></i>
												<p>Producto L&iacute;nea HC BC</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC CN" class="nav-link" <?php if ($arttipo == "PRODUCTO LINEA HC CN") {
												echo "active";
											} ?>>
												<i class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO LINEA HC CN") {
													echo "text-success";
												} ?>"></i>
												<p>Producto L&iacute;nea HC CN</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC PC VA" class="nav-link">
												<i class="far fa-dot-circle nav-icon "></i>
												<p>Producto L&iacute;nea HP PC VA</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA OPM" class="nav-link">
												<i class="far fa-dot-circle nav-icon"></i>
												<p>Producto L&iacute;nea OPM</p>
											</a>
										</li>
									</ul>
								</li>

								<li class="nav-item">
									<a href="#" class="nav-link active">
										<i class="far fa-circle nav-icon"></i>
										<p>
											Registros Sanitarios AE
											<i class="right fas fa-angle-left"></i>
										</p>
									</a>
									<ul class="nav nav-treeview">
										<li class="nav-item">
											<a href="rs_ae.php?arttipo=PRODUCTO AE" class="nav-link">
												<i class="far fa-dot-circle nav-icon"></i>
												<p>Producto AE</p>
											</a>
										</li>

									</ul>
								</li>

							</ul>
						</li>
						<li class="nav-header">USUARIO</li>
						<li class="nav-item">
							<a href="cnx/logout.php" class="nav-link">
								<i class="nav-icon far fa-circle text-danger"></i>
								<p class="text">Cerrar Sesi&oacute;n</p>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>Sistema de Importaciones</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
								<li class="breadcrumb-item active">Importaciones</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">
										<i class="fas fa-clipboard-list mr-2"></i>
										Procesar Códigos de Excel
									</h3>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="codigosInput">Pegue los códigos aquí (uno por
													línea):</label>
												<textarea id="codigosInput" class="form-control code-input-area"
													placeholder="Pegue aquí los códigos copiados de Excel...&#10;Ejemplo:&#10;ABC123&#10;DEF456&#10;GHI789"
													rows="10"></textarea>
												<small class="form-text text-muted">
													<i class="fas fa-info-circle"></i>
													Copie la columna de códigos desde Excel y péguela aquí. El sistema
													procesará automáticamente cada línea como un código separado.
												</small>
												<div class="code-count mt-2">
													<span id="contadorCodigos">0 códigos detectados</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<button type="button" id="procesarBtn" class="btn btn-primary btn-lg">
												<i class="fas fa-cogs mr-2"></i>
												Procesar Códigos
											</button>
											<button type="button" id="limpiarBtn" class="btn btn-secondary btn-lg ml-2">
												<i class="fas fa-eraser mr-2"></i>
												Limpiar
											</button>
										</div>
										<div class="col-md-6 text-right">
											<div class="processing-spinner">
												<div class="spinner-border text-primary" role="status">
													<span class="sr-only">Procesando...</span>
												</div>
												<span class="ml-2">Procesando códigos...</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Resultados -->
					<div class="row results-container" id="resultadosContainer" style="display: none;">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">
										<i class="fas fa-search-plus mr-2"></i>
										Resultados de la Búsqueda
									</h3>
									<div class="card-tools">
										<button type="button" id="exportarBtn" class="btn btn-success btn-sm">
											<i class="fas fa-file-excel mr-1"></i>
											Exportar Vista Actual
										</button>
										<button type="button" id="exportarCompletoBtn"
											class="btn btn-primary btn-sm ml-2">
											<i class="fas fa-download mr-1"></i>
											Exportar Todo
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row mb-3">
										<div class="col-md-3">
											<div class="info-box bg-info">
												<span class="info-box-icon"><i class="fas fa-list"></i></span>
												<div class="info-box-content">
													<span class="info-box-text">Total Procesados</span>
													<span class="info-box-number" id="totalProcesados">0</span>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="info-box bg-success">
												<span class="info-box-icon"><i class="fas fa-check"></i></span>
												<div class="info-box-content">
													<span class="info-box-text">Encontrados</span>
													<span class="info-box-number" id="totalEncontrados">0</span>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="info-box bg-warning">
												<span class="info-box-icon"><i class="fas fa-exclamation"></i></span>
												<div class="info-box-content">
													<span class="info-box-text">No Encontrados</span>
													<span class="info-box-number" id="totalNoEncontrados">0</span>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="info-box bg-danger">
												<span class="info-box-icon"><i class="fas fa-times"></i></span>
												<div class="info-box-content">
													<span class="info-box-text">Errores</span>
													<span class="info-box-number" id="totalErrores">0</span>
												</div>
											</div>
										</div>
									</div>
									<div class="table-responsive">
										<table id="resultadosTable"
											class="table table-bordered table-striped table-hover w-100">
											<thead class="thead-dark">
												<tr>
													<th>Código Buscado</th>
													<th>ArtDescripcion</th>
													<th>RegistroSanitario</th>
													<th>FechaVencimiento</th>
													<th>Estado</th>
													<th>PaisFabricacion</th>
													<th>FactoryLocation</th>
												</tr>
											</thead>
											<tbody id="resultadosBody">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<!-- Card para códigos no encontrados -->
						<div class="col-12" id="codigosNoEncontradosContainer" style="display: none;">
							<div class="card card-warning">
								<div class="card-header">
									<h3 class="card-title">
										<i class="fas fa-exclamation-triangle mr-2"></i>Códigos No Encontrados
										<span class="badge badge-light ml-2" id="badgeNoEncontrados">0</span>
									</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="alert alert-warning">
										<i class="fas fa-info-circle mr-2"></i>
										Los siguientes códigos no fueron encontrados en la base de datos:
									</div>
									<div id="listaCodigosNoEncontrados" class="row">
										<!-- Los códigos se llenarán dinámicamente -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<!-- Footer -->
		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
				<b>Versión</b> 2.0.0
			</div>
			<strong>Copyright &copy; 2025 <a href="#">B. Braun</a>.</strong> Todos los derechos reservados.
		</footer>
	</div>

	<!-- Modal para mostrar estructura de la vista -->
	<div class="modal fade" id="modalEstructura" tabindex="-1" role="dialog" aria-labelledby="modalEstructuraLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header bg-info">
					<h5 class="modal-title" id="modalEstructuraLabel">
						<i class="fas fa-table mr-2"></i>Estructura de la Vista vw_importaciones
					</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="contenidoEstructura">
						<div class="text-center">
							<div class="spinner-border" role="status">
								<span class="sr-only">Cargando...</span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/btp/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>
	<!-- DataTables  & Plugins -->
	<script src="plugins/dt/js/jquery.dataTables.min.js"></script>
	<script src="plugins/dt/js/dataTables.bootstrap5.min.js"></script>
	<script src="plugins/dt/js/dataTables.responsive.min.js"></script>
	<script src="plugins/dt/js/responsive.bootstrap5.min.js"></script>
	<script src="plugins/dt/js/dataTables.buttons.min.js"></script>
	<script src="plugins/dt/js/buttons.bootstrap5.min.js"></script>
	<script src="plugins/dt/js/jszip.min.js"></script>
	<script src="plugins/dt/js/buttons.html5.min.js"></script>
	<script src="plugins/dt/js/buttons.print.min.js"></script>
	<script src="plugins/dt/js/buttons.colVis.min.js"></script>
	<!-- SweetAlert2 -->
	<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
	<!-- Toastr -->
	<script src="plugins/toastr/toastr.min.js"></script>

	<script>
		$(document).ready(function () {
			let dataTable;
			let estructuraVista = null;

			// Cargar información de la vista al iniciar
			cargarInfoVista();

			// Contador de códigos en tiempo real
			$('#codigosInput').on('input', function () {
				const texto = $(this).val().trim();
				const lineas = texto ? texto.split('\n').filter(line => line.trim() !== '') : [];
				$('#contadorCodigos').text(lineas.length + ' códigos detectados');
			});

			// Función para cargar información de la vista
			function cargarInfoVista() {
				$.ajax({
					url: 'scripts/obtener_estructura_vista.php',
					method: 'GET',
					dataType: 'json',
					success: function (response) {
						if (response.success) {
							estructuraVista = response;
							const info = `
								<strong>Vista:</strong> vw_importaciones<br>
								<strong>Total de registros:</strong> ${response.total_registros.toLocaleString()}<br>
								<strong>Columnas disponibles:</strong> ${response.columnas.length}<br>
								<strong>Primera columna (códigos):</strong> ${response.primera_columna}
							`;
							$('#infoVista').html(info);
						} else {
							$('#infoVista').html(`
								<span class="text-danger">
									<i class="fas fa-exclamation-triangle mr-1"></i>
									Error al cargar información: ${response.message}
								</span>
							`);
						}
					},
					error: function () {
						$('#infoVista').html(`
							<span class="text-warning">
								<i class="fas fa-exclamation-triangle mr-1"></i>
								No se pudo cargar la información de la vista
							</span>
						`);
					}
				});
			}

			// Ver estructura de la vista
			$('#verEstructuraBtn').on('click', function () {
				if (!estructuraVista) {
					toastr.warning('Primero debe cargar la información de la vista.');
					return;
				}

				let contenido = `
					<div class="row mb-3">
						<div class="col-md-4">
							<div class="card bg-primary">
								<div class="card-body text-center text-white">
									<h4>${estructuraVista.total_registros.toLocaleString()}</h4>
									<p>Total de Registros</p>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card bg-success">
								<div class="card-body text-center text-white">
									<h4>${estructuraVista.columnas.length}</h4>
									<p>Columnas</p>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card bg-info">
								<div class="card-body text-center text-white">
									<h4>${estructuraVista.primera_columna}</h4>
									<p>Campo de Búsqueda</p>
								</div>
							</div>
						</div>
					</div>

					<h6><i class="fas fa-columns mr-2"></i>Columnas disponibles:</h6>
					<div class="row">
				`;

				estructuraVista.columnas.forEach((columna, index) => {
					const esPrimera = index === 0;
					contenido += `
						<div class="col-md-3 mb-2">
							<span class="badge ${esPrimera ? 'badge-primary' : 'badge-secondary'} p-2 d-block">
								${esPrimera ? '<i class="fas fa-key mr-1"></i>' : ''}
								${columna}
							</span>
						</div>
					`;
				});

				contenido += `
					</div>
					<h6 class="mt-4"><i class="fas fa-eye mr-2"></i>Datos de muestra:</h6>
					<div class="table-responsive">
						<table class="table table-bordered table-sm">
							<thead class="thead-dark">
								<tr>
				`;

				estructuraVista.columnas.forEach(columna => {
					contenido += `<th>${columna}</th>`;
				});

				contenido += '</tr></thead><tbody>';

				estructuraVista.datos_muestra.forEach(fila => {
					contenido += '<tr>';
					estructuraVista.columnas.forEach(columna => {
						contenido += `<td>${fila[columna] || ''}</td>`;
					});
					contenido += '</tr>';
				});

				contenido += '</tbody></table></div>';

				$('#contenidoEstructura').html(contenido);
				$('#modalEstructura').modal('show');
			});

			// Procesar códigos
			$('#procesarBtn').on('click', function () {
				const codigos = $('#codigosInput').val().trim();

				if (!codigos) {
					toastr.warning('Por favor, pegue los códigos a procesar.');
					return;
				}

				// Procesar códigos (separar por líneas y limpiar)
				const codigosArray = codigos.split('\n')
					.map(codigo => codigo.trim())
					.filter(codigo => codigo !== '');

				if (codigosArray.length === 0) {
					toastr.warning('No se detectaron códigos válidos.');
					return;
				}

				// Mostrar spinner
				$('.processing-spinner').show();
				$('#procesarBtn').prop('disabled', true);

				// Guardar códigos para server-side processing
				window.codigosActuales = codigosArray;

				// Enviar códigos al servidor y luego inicializar DataTable
				$.ajax({
					url: 'scripts/server_side_processing_importaciones.php',
					method: 'POST',
					data: {
						codigos: codigosArray
					},
					success: function () {
						// Códigos enviados exitosamente, ahora inicializar DataTable
						window.firstLoad = true; // Marcar como primera carga
						inicializarDataTableServerSide();
						$('#resultadosContainer').show();
						toastr.success('Códigos procesados exitosamente.');
					},
					error: function (xhr, status, error) {
						console.error('Error:', error);
						toastr.error('Error de conexión al procesar los códigos.');
					},
					complete: function () {
						$('.processing-spinner').hide();
						$('#procesarBtn').prop('disabled', false);
					}
				});
			});

			// Limpiar formulario
			$('#limpiarBtn').on('click', function () {
				$('#codigosInput').val('');
				$('#contadorCodigos').text('0 códigos detectados');
				$('#resultadosContainer').hide();
				if (dataTable) {
					dataTable.destroy();
					dataTable = null;
				}
				// Limpiar códigos de la sesión
				window.codigosActuales = null;
				window.ultimasEstadisticas = null;

				// Limpiar estadísticas
				$('#totalProcesados').text('0');
				$('#totalEncontrados').text('0');
				$('#totalNoEncontrados').text('0');
				$('#totalErrores').text('0');
			});

			// Función para inicializar DataTable con Server-Side Processing
			function inicializarDataTableServerSide() {
				// Limpiar tabla anterior si existe
				if (dataTable) {
					dataTable.destroy();
				}

				// Limpiar contenido de la tabla
				$('#resultadosBody').empty();

				// Inicializar DataTable con server-side processing
				dataTable = $('#resultadosTable').DataTable({
					dom: '<"top"f>rt<"bottom"lip><"clear">',
					lengthMenu: [
						[10, 25, 50, 100],
						['10 filas', '25 filas', '50 filas', '100 filas']
					],
					processing: true,
					serverSide: true,
					ajax: {
						url: 'scripts/server_side_processing_importaciones.php',
						type: 'GET',
						dataSrc: function (json) {
							console.log('Respuesta del servidor:', json);
							// Actualizar estadísticas cuando se reciban los datos
							if (json.estadisticas) {
								// Almacenar estadísticas para uso posterior
								window.ultimasEstadisticas = json.estadisticas;

								actualizarEstadisticas(json.estadisticas);
								if (window.firstLoad !== false) {
									mostrarNotificacionesVencimiento(json.estadisticas);
									window.firstLoad = false;
								}

								// Siempre actualizar códigos no encontrados con los datos del servidor
								setTimeout(() => mostrarCodigosNoEncontrados(json), 300);
							}
							if (json.error) {
								console.error('Error del servidor:', json.error);
								toastr.error('Error del servidor: ' + json.error);
							}
							return json.data || [];
						},
						error: function (xhr, error, code) {
							console.error('Error AJAX:', error);
							console.log('Respuesta completa:', xhr.responseText);
							toastr.error('Error al cargar los datos de la tabla: ' + error);
						}
					},
					columns: [
						{ data: 'codigo_buscado' },
						{ data: 'ArtDescripcion' },
						{ data: 'RegistroSanitario' },
						{ data: 'FechaVencimiento' },
						{ data: 'Estado' },
						{ data: 'PaisFabricacion' },
						{ data: 'FactoryLocation' }
					],
					pageLength: 25,
					order: [[0, 'asc']],
					language: {
						processing: "Procesando datos...",
						search: "Buscar:",
						lengthMenu: "Mostrar _MENU_ registros",
						info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
						infoFiltered: "(filtrado de un total de _MAX_ registros)",
						loadingRecords: "Cargando...",
						zeroRecords: "No se encontraron resultados",
						emptyTable: "No hay datos disponibles en la tabla",
						paginate: {
							first: "Primero",
							previous: "Anterior",
							next: "Siguiente",
							last: "Último"
						},
						aria: {
							sortAscending: ": Activar para ordenar la columna de manera ascendente",
							sortDescending: ": Activar para ordenar la columna de manera descendente"
						}
					},
					dom: 'Bfrtip',
					buttons: [
						{
							extend: 'excelHtml5',
							text: '<i class="fas fa-file-excel"></i> Excel',
							className: 'btn btn-success btn-sm',
							title: 'Resultados_Importacion_' + new Date().toISOString().slice(0, 10),
							exportOptions: {
								columns: ':visible'
							}
						},
						{
							extend: 'print',
							text: '<i class="fas fa-print"></i> Imprimir',
							className: 'btn btn-info btn-sm',
							exportOptions: {
								columns: ':visible'
							}
						}
					],
					autoWidth: false,
					columnDefs: [
						{ width: "15%", targets: 0 }, // Código Buscado
						{ width: "30%", targets: 1 }, // ArtDescripcion
						{ width: "15%", targets: 2 }, // RegistroSanitario
						{ width: "12%", targets: 3 }, // FechaVencimiento
						{ width: "10%", targets: 4 }, // Estado
						{ width: "10%", targets: 5 }, // PaisFabricacion
						{ width: "8%", targets: 6 }   // FactoryLocation
					],
					createdRow: function (row, data, dataIndex) {
						// Aplicar clases CSS basadas en DT_RowClass
						if (data.DT_RowClass) {
							$(row).addClass(data.DT_RowClass);
						}
					}
				});
			}

			// Función para actualizar estadísticas
			function actualizarEstadisticas(estadisticas) {
				$('#totalProcesados').text(estadisticas.total);
				$('#totalEncontrados').text(estadisticas.encontrados);
				$('#totalNoEncontrados').text(estadisticas.no_encontrados);
				$('#totalErrores').text(estadisticas.errores || 0);

				// Guardar estadísticas para exportación
				window.ultimasEstadisticas = estadisticas;
			}

			// Función para mostrar notificaciones de vencimiento
			function mostrarNotificacionesVencimiento(estadisticas) {
				if (estadisticas.vencidos > 0) {
					toastr.warning(`Se encontraron ${estadisticas.vencidos} producto(s) vencido(s) marcados en rojo.`, 'Productos Vencidos', {
						timeOut: 10000
					});
				}

				if (estadisticas.vigentes > 0) {
					toastr.success(`${estadisticas.vigentes} producto(s) vigente(s) encontrado(s).`, 'Productos Vigentes');
				}

				// Mostrar códigos no encontrados
				mostrarCodigosNoEncontrados();
			}

			// Función para mostrar códigos no encontrados
			function mostrarCodigosNoEncontrados(dataFromServer = null) {
				if (!window.codigosActuales || !window.ultimasEstadisticas) return;

				// Evitar múltiples ejecuciones simultáneas
				if (window.procesandoCodigosNoEncontrados) return;
				window.procesandoCodigosNoEncontrados = true;

				// Usar las estadísticas del servidor que ya calculan correctamente
				const totalBuscados = window.ultimasEstadisticas.total;
				const totalEncontrados = window.ultimasEstadisticas.encontrados;
				const totalNoEncontrados = window.ultimasEstadisticas.no_encontrados;

				// Actualizar badge
				$('#badgeNoEncontrados').text(totalNoEncontrados);

				if (totalNoEncontrados > 0) {
					// Obtener códigos encontrados directamente de los datos del servidor
					let codigosEncontrados = [];

					if (dataFromServer && dataFromServer.data && dataFromServer.data.length > 0) {
						// Usar los datos que vienen del servidor directamente
						dataFromServer.data.forEach(row => {
							if (row.codigo_buscado) {
								// Extraer el código del HTML (quitar <strong> tags)
								const codigo = $(row.codigo_buscado).text().trim();
								codigosEncontrados.push(codigo);
							}
						});

						console.log('Códigos buscados originales:', window.codigosActuales);
						console.log('Códigos encontrados en datos del servidor:', codigosEncontrados);
					}

					// Encontrar códigos NO encontrados: los que están en los originales pero NO en la tabla
					const codigosNoEncontrados = window.codigosActuales.filter(codigo => {
						const codigoLimpio = codigo.trim();
						const encontrado = codigosEncontrados.some(codigoEncontrado =>
							codigoEncontrado === codigoLimpio
						);
						return !encontrado; // Retornar solo los que NO fueron encontrados
					});

					if (codigosNoEncontrados.length > 0) {
						// Mostrar container
						$('#codigosNoEncontradosContainer').show();

						// Generar HTML para los códigos no encontrados
						let html = '';
						codigosNoEncontrados.forEach(codigo => {
							html += `
								<div class="col-md-2 col-sm-3 col-6 mb-2">
									<span class="badge badge-danger p-2 d-block text-center">
										<i class="fas fa-times mr-1"></i>
										${codigo.trim()}
									</span>
								</div>
							`;
						});

						$('#listaCodigosNoEncontrados').html(html);

						console.log('Códigos NO encontrados (faltantes):', codigosNoEncontrados);
						console.log('Total estadísticas:', window.ultimasEstadisticas);
					}
				} else {
					// Ocultar container si no hay códigos no encontrados
					$('#codigosNoEncontradosContainer').hide();
				}

				// Liberar el flag
				setTimeout(() => {
					window.procesandoCodigosNoEncontrados = false;
				}, 100);
			}

			// Función para mostrar estadísticas de vencimiento
			function mostrarEstadisticasVencimiento(datos) {
				let totalVigentes = 0;
				let totalVencidos = 0;
				let totalSinFecha = 0;

				datos.forEach(function (item) {
					if (item.estado === 'encontrado' && item.datos_vista && item.datos_vista.FechaVencimiento) {
						try {
							const fechaVencimiento = new Date(item.datos_vista.FechaVencimiento);
							if (!isNaN(fechaVencimiento.getTime())) {
								const hoy = new Date();
								hoy.setHours(0, 0, 0, 0);
								fechaVencimiento.setHours(0, 0, 0, 0);
								if (fechaVencimiento < hoy) {
									totalVencidos++;
								} else {
									totalVigentes++;
								}
							} else {
								totalSinFecha++;
							}
						} catch (e) {
							totalSinFecha++;
						}
					} else if (item.estado === 'encontrado') {
						totalSinFecha++;
					}
				});

				if (totalVencidos > 0) {
					toastr.warning(`Se encontraron ${totalVencidos} producto(s) vencido(s) marcados en rojo.`, 'Productos Vencidos', {
						timeOut: 10000
					});
				}

				if (totalVigentes > 0) {
					toastr.success(`${totalVigentes} producto(s) vigente(s) encontrado(s).`, 'Productos Vigentes');
				}
			}

			// Exportar vista actual (botón personalizado)
			$('#exportarBtn').on('click', function () {
				if (!window.codigosActuales || !window.ultimasEstadisticas) {
					toastr.warning('No hay datos para exportar. Primero procese algunos códigos.');
					return;
				}

				if (dataTable) {
					// Usar la funcionalidad de exportación integrada de DataTables
					dataTable.button('.buttons-excel').trigger();
					toastr.success('Iniciando descarga del archivo Excel (vista actual)...');
				} else {
					toastr.warning('No hay tabla para exportar.');
				}
			});

			// Exportar todos los datos completos
			$('#exportarCompletoBtn').on('click', function () {
				if (!window.codigosActuales || !window.ultimasEstadisticas) {
					toastr.warning('No hay datos para exportar. Primero procese algunos códigos.');
					return;
				}

				// Abrir en nueva ventana para descarga
				window.open('scripts/exportar_completo.php', '_blank');
				toastr.success('Iniciando descarga del archivo Excel completo...');
			});

			//Generación del avatar
			generarAvatar('<?php echo $_SESSION['nombres']; ?>', '<?php echo $_SESSION['apellidos']; ?>');
		});

		function generarAvatar(nombre, apellido) {
			let canvas = document.createElement('canvas');
			canvas.style.display = 'none';
			canvas.width = 160;
			canvas.height = 160;
			document.body.appendChild(canvas);
			let context = canvas.getContext('2d');

			// Lista de colores
			let colores = ['#00A97A', '#9E2AB5', '#ECECEC', '#C6C6C6', '#9A9A9A', '#737373', '#545454', '#202020', '#F0EDE6', '#CAC6BF', '#9C9A94', '#74746F', '#555451', '#31312E', '#2DA0D3', '#E40147', '#F29000', '#BFCF00'];

			// Generar color aleatorio de la lista
			let color = colores[Math.floor(Math.random() * colores.length)];

			// Dibujar círculo
			context.beginPath();
			context.arc(80, 80, 80, 0, Math.PI * 2, false);
			context.fillStyle = color;
			context.fill();

			// Dibujar círculo interno
			context.beginPath();
			context.arc(80, 80, 80 * 0.6, 0, Math.PI * 2, false); // 60% del tamaño del círculo principal
			context.fillStyle = colorDarker(color); // color más oscuro
			context.fill();

			// Agregar texto
			context.font = 'bold 70px Arial';
			context.fillStyle = '#ffffff';
			context.textAlign = 'center';
			context.textBaseline = 'middle';
			context.fillText(nombre.charAt(0) + apellido.charAt(0), 80, 85);

			// Setear como src en una etiqueta imagen
			let img = document.getElementById('avatar');
			img.src = canvas.toDataURL('image/png');
			document.body.removeChild(canvas);
		}
		// Función para oscurecer un color
		function colorDarker(color) {
			let num = parseInt(color.replace("#", ""), 16),
				amt = Math.round(2.55 * 5),
				R = (num >> 16) - amt,
				G = (num >> 8 & 0x00FF) - amt,
				B = (num & 0x0000FF) - amt;
			return "#" + (0x1000000 + (R > 0 ? R : 0) * 0x10000 + (G > 0 ? G : 0) * 0x100 + (B > 0 ? B : 0)).toString(16).slice(1);
		}

	</script>
</body>

</html>