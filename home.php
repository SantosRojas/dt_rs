<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
	// Si el usuario no está logueado, redirígelo a index.php
	header('Location: index.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Dirección Técnica</title>
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
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/searchBuilder.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/dataTables.dateTime.min.css">
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
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>
			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" data-widget="fullscreen" href="#" role="button">
						<i class="fas fa-expand-arrows-alt"></i>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->
		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-1">
			<!-- Brand Logo -->
			<a href="home.php" class="brand-link elevation-3">
				<img src="dist/img/bbraun.png" alt="B Logo" class="brand-image" width="60%" height="auto"
					style="opacity: .8">
				<span class="brand-text font-weight-light">Direcci&oacute;n T&eacute;cnica</span-->
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
							<a href="home.php" class="nav-link active">
								<i class="nav-icon fas fa-home"></i>
								<p>
									Inicio
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="importaciones.php" class="nav-link">
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
								<li class="nav-item">
									<a href="#" class="nav-link">
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
												<i class="far fa-dot-circle nav-icon"></i>
												<p>Producto L&iacute;nea HC BC</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC CN" class="nav-link">
												<i class="far fa-dot-circle nav-icon"></i>
												<p>Producto L&iacute;nea HC CN</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC PC VA" class="nav-link">
												<i class="far fa-dot-circle nav-icon"></i>
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
									<a href="#" class="nav-link">
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
												<p>Producto AESCULAP </p>
											</a>
										</li>

									</ul>
								</li>
								<!--li class="nav-item">
										<a href="#" class="nav-link">
											<i class="far fa-circle nav-icon"></i>
											<p>Productos</p>
										</a>
									</li-->
							</ul>
						</li>
						<?php if ($_SESSION['nivel'] == 'ADMIN'): ?>
					<li class="nav-header">ADMINISTRACI&Oacute;N</li>
					<li class="nav-item">
						<a href="usuarios.php" class="nav-link">
							<i class="nav-icon fas fa-users-cog"></i>
							<p>Gesti&oacute;n de Usuarios</p>
						</a>
					</li>
					<?php endif; ?>
					<li class="nav-header">USUARIO</li>
						<!--li class="nav-item">
								<a href="#" class="nav-link">
									<i class="nav-icon far fa-circle text-info"></i>
									<p>Perfil</p>
								</a>
							</li-->
						<li class="nav-item">
							<a href="cnx/logout.php" class="nav-link">
								<i class="nav-icon far fa-circle text-danger"></i>
								<p class="text">Cerrar Sesi&oacute;n</p>
							</a>
						</li>
					</ul>
				</nav>
			</div>
			<!-- /.sidebar -->
		</aside>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>Sistema de Direcci&oacute;n T&eacute;cnica</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
							</ol>
						</div>
					</div>
				</div>
				<!-- /.container-fluid -->
			</section>
			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<!-- Tarjetas de totales -->
					<div class="row">
						<div class="col-lg-4 col-md-6 col-12">
							<div class="info-box bg-gradient-info">
								<span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">Total Registros Sanitarios</span>
									<span class="info-box-number" id="total-general">-</span>
									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>
									<span class="progress-description">
										HC + Aesculap
									</span>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12">
							<div class="info-box bg-gradient-success">
								<span class="info-box-icon"><i class="fas fa-hospital"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">Registros Sanitarios HC</span>
									<span class="info-box-number" id="total-hc">-</span>
									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>
									<span class="progress-description" id="porcentaje-hc">
										- % del total
									</span>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12">
							<div class="info-box bg-gradient-primary">
								<span class="info-box-icon"><i class="fas fa-medkit"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">Registros Sanitarios Aesculap</span>
									<span class="info-box-number" id="total-ae">-</span>
									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>
									<span class="progress-description" id="porcentaje-ae">
										- % del total
									</span>
								</div>
							</div>
						</div>
					</div>

					<!-- Estadísticas por estado -->
					<div class="row">
						<!-- RS HC -->
						<div class="col-md-6">
							<div class="card card-success">
								<div class="card-header">
									<h3 class="card-title"><i class="fas fa-hospital"></i> Registros Sanitarios HC por
										Estado</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div id="stats-hc" class="row">
										<div class="col-12 text-center">
											<i class="fas fa-spinner fa-spin fa-3x"></i>
											<p>Cargando estadísticas...</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- RS AE -->
						<div class="col-md-6">
							<div class="card card-primary">
								<div class="card-header">
									<h3 class="card-title"><i class="fas fa-medkit"></i> Registros Sanitarios Aesculap
										por Estado</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div id="stats-ae" class="row">
										<div class="col-12 text-center">
											<i class="fas fa-spinner fa-spin fa-3x"></i>
											<p>Cargando estadísticas...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/. container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
				<b>Version</b> 1.0.0
			</div>
			<strong>B. Braun Medical Per&uacute;.</strong> All rights reserved.
		</footer>
	</div>
	<!-- REQUIRED SCRIPTS -->
	<!-- jQuery -->
	<script src="plugins/dt/js/jquery-3.7.0.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- Bootstrap 5.3.2 -->
	<script src="plugins/btp/js/bootstrap.bundle.min.js"></script>
	<!-- Select2 -->
	<script src="plugins/select2/js/select2.full.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- DataTables Scripts-->
	<script src="plugins/dt/js/jquery.dataTables.min.js"></script>
	<script src="plugins/dt/js/dataTables.bootstrap5.min.js"></script>
	<script src="plugins/dt/js/dataTables.dateTime.min.js"></script>
	<script src="plugins/dt/js/dataTables.responsive.min.js"></script>
	<script src="plugins/dt/js/responsive.bootstrap5.min.js"></script>
	<script src="plugins/dt/js/dataTables.buttons.min.js"></script>
	<script src="plugins/dt/js/buttons.bootstrap5.min.js"></script>
	<script src="plugins/dt/js/jszip.min.js"></script>
	<script src="plugins/dt/js/pdfmake.min.js"></script>
	<script src="plugins/dt/js/vfs_fonts.js"></script>
	<script src="plugins/dt/js/buttons.html5.min.js"></script>
	<script src="plugins/dt/js/buttons.print.min.js"></script>
	<script src="plugins/dt/js/buttons.colVis.min.js"></script>
	<script>
		$(document).ready(function () {

			//Generación del avatar
			generarAvatar('<?php echo $_SESSION['nombres']; ?>', '<?php echo $_SESSION['apellidos']; ?>');

			// Cargar estadísticas del dashboard
			cargarEstadisticas();

		});

		function cargarEstadisticas() {
			$.ajax({
				url: 'scripts/obtener_estadisticas_dashboard.php',
				type: 'GET',
				dataType: 'json',
				success: function (data) {
					// Actualizar totales
					$('#total-general').text(data.totales.general.toLocaleString());
					$('#total-hc').text(data.totales.rs_hc.toLocaleString());
					$('#total-ae').text(data.totales.rs_ae.toLocaleString());

					// Calcular porcentajes
					var porcentajeHC = ((data.totales.rs_hc / data.totales.general) * 100).toFixed(1);
					var porcentajeAE = ((data.totales.rs_ae / data.totales.general) * 100).toFixed(1);

					$('#porcentaje-hc').text(porcentajeHC + '% del total');
					$('#porcentaje-ae').text(porcentajeAE + '% del total');

					// Generar cards de estados HC
					generarCardsEstados(data.rs_hc, '#stats-hc', 'rs.php');

					// Generar cards de estados AE
					generarCardsEstados(data.rs_ae, '#stats-ae', 'rs_ae.php?arttipo=PRODUCTO AE');
				},
				error: function (xhr, status, error) {
					console.error('Error al cargar estadísticas:', error);
					$('#stats-hc').html('<div class="col-12"><div class="alert alert-danger">Error al cargar estadísticas HC</div></div>');
					$('#stats-ae').html('<div class="col-12"><div class="alert alert-danger">Error al cargar estadísticas AE</div></div>');
				}
			});
		}

		function generarCardsEstados(estados, contenedor, link) {
			var html = '';
			var coloresEstados = {
				'VIGENTE': 'bg-success',
				'VENCE 1 MES': 'bg-warning',
				'VENCE 2 MESES': 'bg-warning',
				'VENCE 3 MESES': 'bg-warning',
				'VENCIDO': 'bg-danger',
				'PRORROGADO': 'bg-info',
				'RENOVACION': 'bg-teal',
				'TRAMITE': 'bg-lightblue',
				'DESCONTINUADO': 'bg-secondary',
				'NO APLICA': 'bg-gray',
				'NO SE RENUEVA': 'bg-dark'
			};

			var iconosEstados = {
				'VIGENTE': 'fa-check-circle',
				'VENCE 1 MES': 'fa-exclamation-triangle',
				'VENCE 2 MESES': 'fa-exclamation-triangle',
				'VENCE 3 MESES': 'fa-exclamation-triangle',
				'VENCIDO': 'fa-times-circle',
				'PRORROGADO': 'fa-clock',
				'RENOVACION': 'fa-sync-alt',
				'TRAMITE': 'fa-hourglass-half',
				'DESCONTINUADO': 'fa-ban',
				'NO APLICA': 'fa-minus-circle',
				'NO SE RENUEVA': 'fa-hand-paper'
			};

			if (Object.keys(estados).length === 0) {
				html = '<div class="col-12"><div class="alert alert-info">No hay registros disponibles</div></div>';
			} else {
				for (var estado in estados) {
					var total = estados[estado];
					var color = coloresEstados[estado] || 'bg-secondary';
					var icono = iconosEstados[estado] || 'fa-question-circle';

					html += `
						<div class="col-lg-6 col-md-6 col-12 mb-3">
							<div class="small-box ${color}">
								<div class="inner">
									<h3>${total}</h3>
									<p>${estado}</p>
								</div>
								<div class="icon">
									<i class="fas ${icono}"></i>
								</div>
							</div>
						</div>
					`;
				}
			}

			$(contenedor).html(html);
		}

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

		// Selecciono el elemento que quieres observar
		var elemento = document.querySelector('#body');

		// Creo una instancia de MutationObserver
		var observer = new MutationObserver(function (mutations) {
			mutations.forEach(function (mutation) {
				if (mutation.attributeName === "class") {
					var clases = mutation.target.className.split(/\s+/);
					if (clases.includes("sidebar-collapse")) {
						console.log("La clase 'sidebar-collapse' se ha añadido.");
					} else {
						console.log("La clase 'sidebar-collapse' se ha eliminado.");
					}
				}
			});
		});

		// Configuro el observer para que observe los cambios en los atributos
		observer.observe(elemento, { attributes: true });

	</script>
</body>

</html>