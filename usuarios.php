<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
	header('Location: index.php');
	exit;
}

// Verificar nivel de acceso (solo administradores)
if ($_SESSION['nivel'] != 'ADMIN') {
	header('Location: home.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestión de Usuarios - Sistema de Dirección Técnica</title>
	<meta name="description" content="Módulo de administración de usuarios del Sistema de Dirección Técnica">
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
	<style>
		/* Custom styles for user management module */
		.badge-estado {
			font-size: 0.85em;
			padding: 0.4em 0.8em;
			border-radius: 50px;
			font-weight: 600;
			letter-spacing: 0.3px;
		}

		.badge-activo {
			background: linear-gradient(135deg, #28a745, #20c997);
			color: #fff;
			box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
		}

		.badge-inactivo {
			background: linear-gradient(135deg, #dc3545, #e74c3c);
			color: #fff;
			box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
		}

		.badge-nivel {
			font-size: 0.85em;
			padding: 0.4em 0.8em;
			border-radius: 50px;
			font-weight: 600;
		}

		.badge-admin {
			background: linear-gradient(135deg, #6f42c1, #5a2d9e);
			color: #fff;
			box-shadow: 0 2px 6px rgba(111, 66, 193, 0.3);
		}

		.badge-editor {
			background: linear-gradient(135deg, #0d6efd, #0b5ed7);
			color: #fff;
			box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
		}

		.badge-consultor {
			background: linear-gradient(135deg, #6c757d, #5a6268);
			color: #fff;
			box-shadow: 0 2px 6px rgba(108, 117, 125, 0.3);
		}

		.btn-action {
			padding: 0.25rem 0.5rem;
			font-size: 0.8rem;
			border-radius: 6px;
			transition: all 0.2s ease;
			margin: 1px;
		}

		.btn-action:hover {
			transform: translateY(-1px);
			box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
		}

		.card-usuarios {
			border: none;
			border-radius: 12px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
			overflow: hidden;
		}

		.card-usuarios .card-header {
			background: linear-gradient(135deg, #00A97A, #00876a);
			border: none;
			padding: 1rem 1.5rem;
		}

		.card-usuarios .card-header h3 {
			color: #fff;
			font-weight: 600;
		}

		.stats-card {
			border: none;
			border-radius: 12px;
			overflow: hidden;
			transition: all 0.3s ease;
		}

		.stats-card:hover {
			transform: translateY(-3px);
			box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
		}

		.stats-card .inner h3 {
			font-size: 2rem;
			font-weight: 700;
		}

		.modal-header {
			border-bottom: 2px solid #f0f0f0;
		}

		.modal-footer {
			border-top: 2px solid #f0f0f0;
		}

		.form-label {
			font-weight: 600;
			color: #495057;
			margin-bottom: 0.3rem;
		}

		.form-control:focus,
		.form-select:focus {
			border-color: #00A97A;
			box-shadow: 0 0 0 0.2rem rgba(0, 169, 122, 0.25);
		}

		.table thead th {
			background: #f8f9fa;
			border-bottom: 2px solid #dee2e6;
			font-weight: 700;
			font-size: 0.85rem;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			color: #495057;
		}

		.swal2-popup {
			border-radius: 12px !important;
		}

		/* Animation for new rows */
		@keyframes fadeInRow {
			from {
				opacity: 0;
				transform: translateY(-10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.dataTables_wrapper .dataTables_filter input {
			border-radius: 20px;
			padding: 0.375rem 1rem;
			border: 1px solid #ced4da;
		}

		.dataTables_wrapper .dataTables_filter input:focus {
			border-color: #00A97A;
			box-shadow: 0 0 0 0.2rem rgba(0, 169, 122, 0.25);
		}

		.btn-nuevo-usuario {
			background: linear-gradient(135deg, #00A97A, #00876a);
			border: none;
			color: #fff;
			padding: 0.5rem 1.5rem;
			font-weight: 600;
			border-radius: 8px;
			transition: all 0.3s ease;
			box-shadow: 0 3px 10px rgba(0, 169, 122, 0.3);
		}

		.btn-nuevo-usuario:hover {
			background: linear-gradient(135deg, #00876a, #006b55);
			color: #fff;
			transform: translateY(-1px);
			box-shadow: 0 5px 15px rgba(0, 169, 122, 0.4);
		}

		.password-toggle {
			cursor: pointer;
			position: absolute;
			right: 10px;
			top: 50%;
			transform: translateY(-50%);
			color: #6c757d;
			z-index: 5;
		}

		.password-toggle:hover {
			color: #00A97A;
		}

		.input-password-wrapper {
			position: relative;
		}
	</style>
</head>

<body id="body"
	class="hold-transition sidebar-mini sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
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
							<a href="home.php" class="nav-link">
								<i class="nav-icon fas fa-home"></i>
								<p>Inicio</p>
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
							</ul>
						</li>
						<?php if ($_SESSION['nivel'] == 'ADMIN'): ?>
						<li class="nav-header">ADMINISTRACI&Oacute;N</li>
						<li class="nav-item">
							<a href="usuarios.php" class="nav-link active">
								<i class="nav-icon fas fa-users-cog"></i>
								<p>Gesti&oacute;n de Usuarios</p>
							</a>
						</li>
						<?php endif; ?>
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
			<!-- /.sidebar -->
		</aside>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1><i class="fas fa-users-cog mr-2"></i>Gesti&oacute;n de Usuarios</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
								<li class="breadcrumb-item active">Gesti&oacute;n de Usuarios</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<!-- Stats Row -->
					<div class="row mb-4">
						<div class="col-lg-3 col-md-6 col-12 mb-3">
							<div class="small-box bg-gradient-info stats-card">
								<div class="inner">
									<h3 id="stat-total">-</h3>
									<p>Total Usuarios</p>
								</div>
								<div class="icon">
									<i class="fas fa-users"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12 mb-3">
							<div class="small-box bg-gradient-success stats-card">
								<div class="inner">
									<h3 id="stat-activos">-</h3>
									<p>Usuarios Activos</p>
								</div>
								<div class="icon">
									<i class="fas fa-user-check"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12 mb-3">
							<div class="small-box bg-gradient-danger stats-card">
								<div class="inner">
									<h3 id="stat-inactivos">-</h3>
									<p>Usuarios Inactivos</p>
								</div>
								<div class="icon">
									<i class="fas fa-user-times"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12 mb-3">
							<div class="small-box bg-gradient-purple stats-card">
								<div class="inner">
									<h3 id="stat-admins">-</h3>
									<p>Administradores</p>
								</div>
								<div class="icon">
									<i class="fas fa-user-shield"></i>
								</div>
							</div>
						</div>
					</div>

					<!-- Users Table -->
					<div class="card card-usuarios">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-list mr-2"></i>Listado de Usuarios</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-nuevo-usuario" id="btn-nuevo-usuario"
									onclick="abrirModalNuevo()">
									<i class="fas fa-user-plus mr-1"></i> Nuevo Usuario
								</button>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="tabla-usuarios" class="table table-hover table-striped" style="width:100%">
									<thead>
										<tr>
											<th>Usuario</th>
											<th>Nombres</th>
											<th>Apellidos</th>
											<th>Cargo</th>
											<th>&Aacute;rea</th>
											<th>Nivel</th>
											<th>Estado</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
				<b>Version</b> 1.0.0
			</div>
			<strong>B. Braun Medical Per&uacute;.</strong> All rights reserved.
		</footer>
	</div>

	<!-- ==================== MODALES ==================== -->

	<!-- Modal Crear/Editar Usuario -->
	<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
				<div class="modal-header" style="background: linear-gradient(135deg, #00A97A, #00876a);">
					<h5 class="modal-title text-white" id="modalUsuarioLabel">
						<i class="fas fa-user-plus mr-2"></i>Nuevo Usuario
					</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
						onclick="cerrarModal()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formUsuario" onsubmit="guardarUsuario(event)">
					<div class="modal-body">
						<input type="hidden" id="form-mode" value="crear">
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="form-usuario" class="form-label">
									<i class="fas fa-user mr-1 text-muted"></i>Usuario <span
										class="text-danger">*</span>
								</label>
								<input type="text" class="form-control" id="form-usuario" name="usuario"
									placeholder="Ej: 4-2-2" required maxlength="50">
								<small class="text-muted">Código único del usuario</small>
							</div>
							<div class="col-md-6 mb-3" id="campo-clave">
								<label for="form-clave" class="form-label">
									<i class="fas fa-lock mr-1 text-muted"></i>Contraseña <span
										class="text-danger">*</span>
								</label>
								<div class="input-password-wrapper">
									<input type="password" class="form-control" id="form-clave" name="clave"
										placeholder="Ingrese contraseña" required>
									<span class="password-toggle" onclick="togglePassword('form-clave', this)">
										<i class="fas fa-eye"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="form-nombres" class="form-label">
									<i class="fas fa-id-card mr-1 text-muted"></i>Nombres <span
										class="text-danger">*</span>
								</label>
								<input type="text" class="form-control" id="form-nombres" name="nombres"
									placeholder="Nombres completos" required maxlength="100">
							</div>
							<div class="col-md-6 mb-3">
								<label for="form-apellidos" class="form-label">
									<i class="fas fa-id-card mr-1 text-muted"></i>Apellidos <span
										class="text-danger">*</span>
								</label>
								<input type="text" class="form-control" id="form-apellidos" name="apellidos"
									placeholder="Apellidos completos" required maxlength="100">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="form-cargo" class="form-label">
									<i class="fas fa-briefcase mr-1 text-muted"></i>Cargo
								</label>
								<input type="text" class="form-control" id="form-cargo" name="cargo"
									placeholder="Cargo del usuario" maxlength="100">
							</div>
							<div class="col-md-6 mb-3">
								<label for="form-area" class="form-label">
									<i class="fas fa-building mr-1 text-muted"></i>&Aacute;rea
								</label>
								<input type="text" class="form-control" id="form-area" name="area"
									placeholder="Área del usuario" maxlength="100">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="form-nivel" class="form-label">
									<i class="fas fa-layer-group mr-1 text-muted"></i>Nivel de Acceso <span
										class="text-danger">*</span>
								</label>
								<select class="form-control" id="form-nivel" name="nivel" required>
									<option value="ADMIN">Administrador</option>
									<option value="EDITOR">Editor</option>
									<option value="VISOR" selected>Visor</option>
								</select>
							</div>
							<div class="col-md-6 mb-3">
								<label for="form-estado" class="form-label">
									<i class="fas fa-toggle-on mr-1 text-muted"></i>Estado
								</label>
								<select class="form-control" id="form-estado" name="estado">
									<option value="1" selected>Activo</option>
									<option value="0">Inactivo</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" onclick="cerrarModal()">
							<i class="fas fa-times mr-1"></i>Cancelar
						</button>
						<button type="submit" class="btn btn-success" id="btn-guardar">
							<i class="fas fa-save mr-1"></i>Guardar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Modal Resetear Clave -->
	<div class="modal fade" id="modalResetClave" tabindex="-1" role="dialog" aria-labelledby="modalResetClaveLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
				<div class="modal-header" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
					<h5 class="modal-title text-white" id="modalResetClaveLabel">
						<i class="fas fa-key mr-2"></i>Resetear Contraseña
					</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
						onclick="cerrarModalReset()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formResetClave" onsubmit="resetearClave(event)">
					<div class="modal-body">
						<input type="hidden" id="reset-usuario" value="">
						<div class="alert alert-info">
							<i class="fas fa-info-circle mr-1"></i>
							Resetear la contraseña del usuario: <strong id="reset-usuario-display"></strong>
						</div>
						<div class="mb-3">
							<label for="reset-nueva-clave" class="form-label">
								<i class="fas fa-lock mr-1 text-muted"></i>Nueva Contraseña <span
									class="text-danger">*</span>
							</label>
							<div class="input-password-wrapper">
								<input type="password" class="form-control" id="reset-nueva-clave"
									name="nueva_clave" placeholder="Ingrese nueva contraseña" required>
								<span class="password-toggle" onclick="togglePassword('reset-nueva-clave', this)">
									<i class="fas fa-eye"></i>
								</span>
							</div>
						</div>
						<div class="mb-3">
							<label for="reset-confirmar-clave" class="form-label">
								<i class="fas fa-lock mr-1 text-muted"></i>Confirmar Contraseña <span
									class="text-danger">*</span>
							</label>
							<div class="input-password-wrapper">
								<input type="password" class="form-control" id="reset-confirmar-clave"
									placeholder="Confirme nueva contraseña" required>
								<span class="password-toggle"
									onclick="togglePassword('reset-confirmar-clave', this)">
									<i class="fas fa-eye"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" onclick="cerrarModalReset()">
							<i class="fas fa-times mr-1"></i>Cancelar
						</button>
						<button type="submit" class="btn btn-warning">
							<i class="fas fa-key mr-1"></i>Resetear Clave
						</button>
					</div>
				</form>
			</div>
		</div>
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
	<!-- DataTables Scripts -->
	<script src="plugins/dt/js/jquery.dataTables.min.js"></script>
	<script src="plugins/dt/js/dataTables.bootstrap5.min.js"></script>
	<script src="plugins/dt/js/dataTables.responsive.min.js"></script>
	<script src="plugins/dt/js/responsive.bootstrap5.min.js"></script>
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		// ========================================
		// VARIABLES GLOBALES
		// ========================================
		var tablaUsuarios;
		var currentUser = '<?php echo $_SESSION['usuario']; ?>';

		// ========================================
		// INICIALIZACIÓN
		// ========================================
		$(document).ready(function () {
			// Generar avatar
			generarAvatar('<?php echo $_SESSION['nombres']; ?>', '<?php echo $_SESSION['apellidos']; ?>');

			// Inicializar DataTable
			tablaUsuarios = $('#tabla-usuarios').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: 'scripts/usuarios_api.php?action=listar',
					type: 'GET',
					dataSrc: function (json) {
						// Actualizar stats
						actualizarStats(json.data);
						return json.data;
					}
				},
				columns: [
					{
						data: 'usuario',
						render: function (data) {
							return '<strong><i class="fas fa-user-circle mr-1 text-muted"></i>' + data + '</strong>';
						}
					},
					{ data: 'nombres' },
					{ data: 'apellidos' },
					{
						data: 'cargo',
						render: function (data) {
							return data || '<span class="text-muted">-</span>';
						}
					},
					{
						data: 'area',
						render: function (data) {
							return data || '<span class="text-muted">-</span>';
						}
					},
					{
						data: 'nivel',
						render: function (data, type, row) {
							var badges = {
								'ADMIN': '<span class="badge badge-nivel badge-admin"><i class="fas fa-shield-alt mr-1"></i>Administrador</span>',
								'EDITOR': '<span class="badge badge-nivel badge-editor"><i class="fas fa-edit mr-1"></i>Editor</span>',
								'VISOR': '<span class="badge badge-nivel badge-consultor"><i class="fas fa-eye mr-1"></i>Visor</span>'
							};
							return badges[data] || '<span class="badge badge-nivel badge-consultor">' + data + '</span>';
						}
					},
					{
						data: 'estado',
						render: function (data) {
							if (data == 1) {
								return '<span class="badge badge-estado badge-activo"><i class="fas fa-check-circle mr-1"></i>Activo</span>';
							} else {
								return '<span class="badge badge-estado badge-inactivo"><i class="fas fa-times-circle mr-1"></i>Inactivo</span>';
							}
						}
					},
					{
						data: null,
						orderable: false,
						searchable: false,
						render: function (data, type, row) {
							var isCurrentUser = row.usuario === currentUser;
							var estadoBtn = '';

							if (!isCurrentUser) {
								if (row.estado == 1) {
									estadoBtn = '<button class="btn btn-outline-danger btn-action" onclick="toggleEstado(\'' + row.usuario + '\', 0)" title="Eliminar (desactivar)"><i class="fas fa-trash-alt"></i></button>';
								} else {
									estadoBtn = '<button class="btn btn-outline-success btn-action" onclick="toggleEstado(\'' + row.usuario + '\', 1)" title="Reactivar usuario"><i class="fas fa-user-check"></i></button>';
								}
							}

							return '<div class="btn-group" role="group">' +
								'<button class="btn btn-outline-primary btn-action" onclick="editarUsuario(\'' + row.usuario + '\')" title="Editar"><i class="fas fa-edit"></i></button>' +
								'<button class="btn btn-outline-info btn-action" onclick="abrirModalReset(\'' + row.usuario + '\')" title="Resetear Clave"><i class="fas fa-key"></i></button>' +
								estadoBtn +
								'</div>';
						}
					}
				],
				language: {
					"processing": '<i class="fas fa-spinner fa-spin fa-2x"></i> <br>Cargando...',
					"lengthMenu": "Mostrar _MENU_ registros",
					"zeroRecords": "No se encontraron resultados",
					"info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
					"infoEmpty": "Mostrando 0 a 0 de 0 registros",
					"infoFiltered": "(filtrado de _MAX_ registros en total)",
					"search": '<i class="fas fa-search"></i> Buscar:',
					"paginate": {
						"first": '<i class="fas fa-angle-double-left"></i>',
						"last": '<i class="fas fa-angle-double-right"></i>',
						"next": '<i class="fas fa-angle-right"></i>',
						"previous": '<i class="fas fa-angle-left"></i>'
					}
				},
				responsive: true,
				pageLength: 10,
				order: [[0, 'asc']],
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
			});
		});

		// ========================================
		// FUNCIONES DE STATS
		// ========================================
		function actualizarStats(data) {
			var total = data.length;
			var activos = 0;
			var inactivos = 0;
			var admins = 0;

			// Si es server-side, necesitamos hacer una consulta adicional
			// Por ahora usamos los datos del DataTable en la página actual
			$.ajax({
				url: 'scripts/usuarios_api.php?action=listar&start=0&length=9999',
				type: 'GET',
				dataType: 'json',
				async: false,
				success: function (response) {
					var allData = response.data;
					total = allData.length;
					allData.forEach(function (row) {
						if (row.estado == 1) activos++;
						else inactivos++;
						if (row.nivel == 'ADMIN') admins++;
					});
					$('#stat-total').text(total);
					$('#stat-activos').text(activos);
					$('#stat-inactivos').text(inactivos);
					$('#stat-admins').text(admins);
				}
			});
		}

		// ========================================
		// CRUD FUNCTIONS
		// ========================================

		/**
		 * Abre el modal para crear un nuevo usuario
		 */
		function abrirModalNuevo() {
			$('#form-mode').val('crear');
			$('#modalUsuarioLabel').html('<i class="fas fa-user-plus mr-2"></i>Nuevo Usuario');
			$('#formUsuario')[0].reset();
			$('#form-usuario').prop('readonly', false);
			$('#campo-clave').show();
			$('#form-clave').prop('required', true);
			$('#form-nivel').val('VISOR');
			$('#form-estado').val('1');
			$('#modalUsuario').modal('show');
		}

		/**
		 * Abre el modal para editar un usuario existente
		 */
		function editarUsuario(usuario) {
			$.ajax({
				url: 'scripts/usuarios_api.php',
				type: 'GET',
				data: { action: 'obtener', usuario: usuario },
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						var data = response.data;
						$('#form-mode').val('editar');
						$('#modalUsuarioLabel').html('<i class="fas fa-user-edit mr-2"></i>Editar Usuario');
						$('#form-usuario').val(data.usuario).prop('readonly', true);
						$('#form-nombres').val(data.nombres);
						$('#form-apellidos').val(data.apellidos);
						$('#form-cargo').val(data.cargo);
						$('#form-area').val(data.area);
						$('#form-nivel').val(data.nivel);
						$('#form-estado').val(data.estado);
						$('#campo-clave').hide();
						$('#form-clave').prop('required', false);
						$('#modalUsuario').modal('show');
					} else {
						showAlert('error', 'Error', response.message);
					}
				},
				error: function () {
					showAlert('error', 'Error', 'No se pudo obtener los datos del usuario');
				}
			});
		}

		/**
		 * Guarda un usuario (crear o editar)
		 */
		function guardarUsuario(e) {
			e.preventDefault();
			var mode = $('#form-mode').val();
			var action = mode === 'crear' ? 'crear' : 'editar';

			var formData = {
				action: action,
				usuario: $('#form-usuario').val(),
				nombres: $('#form-nombres').val(),
				apellidos: $('#form-apellidos').val(),
				cargo: $('#form-cargo').val(),
				area: $('#form-area').val(),
				nivel: $('#form-nivel').val(),
				estado: $('#form-estado').val()
			};

			if (mode === 'crear') {
				formData.clave = $('#form-clave').val();
			}

			$('#btn-guardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Guardando...');

			$.ajax({
				url: 'scripts/usuarios_api.php',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function (response) {
					$('#btn-guardar').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Guardar');
					if (response.success) {
						cerrarModal();
						tablaUsuarios.ajax.reload(null, false);
						showAlert('success', '¡Éxito!', response.message);
					} else {
						showAlert('error', 'Error', response.message);
					}
				},
				error: function (xhr) {
					$('#btn-guardar').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Guardar');
					var errorMsg = 'Error al guardar el usuario';
					try {
						var resp = JSON.parse(xhr.responseText);
						errorMsg = resp.message || errorMsg;
					} catch (e) { }
					showAlert('error', 'Error', errorMsg);
				}
			});
		}

		/**
		 * Cambia el estado de un usuario (eliminar = desactivar, reactivar = activar)
		 */
		function toggleEstado(usuario, nuevoEstado) {
			var esEliminar = nuevoEstado == 0;
			var titulo = esEliminar ? '¿Eliminar usuario?' : '¿Reactivar usuario?';
			var mensaje = esEliminar 
				? 'El usuario <strong>"' + usuario + '"</strong> será desactivado y no podrá iniciar sesión.<br><br><small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Puede reactivarlo en cualquier momento.</small>'
				: '¿Desea reactivar al usuario <strong>"' + usuario + '"</strong>? Podrá iniciar sesión nuevamente.';
			var icono = esEliminar ? 'warning' : 'question';
			var color = esEliminar ? '#dc3545' : '#28a745';
			var btnTexto = esEliminar 
				? '<i class="fas fa-trash-alt mr-1"></i>Sí, eliminar'
				: '<i class="fas fa-user-check mr-1"></i>Sí, reactivar';

			Swal.fire({
				title: titulo,
				html: mensaje,
				icon: icono,
				showCancelButton: true,
				confirmButtonColor: color,
				cancelButtonColor: '#6c757d',
				confirmButtonText: btnTexto,
				cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
				reverseButtons: true
			}).then(function (result) {
				if (result.isConfirmed) {
					$.ajax({
						url: 'scripts/usuarios_api.php',
						type: 'POST',
						data: { action: 'cambiar_estado', usuario: usuario, estado: nuevoEstado },
						dataType: 'json',
						success: function (response) {
							if (response.success) {
								tablaUsuarios.ajax.reload(null, false);
								showAlert('success', '¡Éxito!', response.message);
							} else {
								showAlert('error', 'Error', response.message);
							}
						},
						error: function () {
							showAlert('error', 'Error', 'No se pudo completar la acción');
						}
					});
				}
			});
		}

		/**
		 * Abre el modal para resetear contraseña
		 */
		function abrirModalReset(usuario) {
			$('#reset-usuario').val(usuario);
			$('#reset-usuario-display').text(usuario);
			$('#formResetClave')[0].reset();
			$('#modalResetClave').modal('show');
		}

		/**
		 * Resetea la contraseña de un usuario
		 */
		function resetearClave(e) {
			e.preventDefault();

			var nuevaClave = $('#reset-nueva-clave').val();
			var confirmarClave = $('#reset-confirmar-clave').val();

			if (nuevaClave !== confirmarClave) {
				showAlert('error', 'Error', 'Las contraseñas no coinciden');
				return;
			}

			var usuario = $('#reset-usuario').val();

			$.ajax({
				url: 'scripts/usuarios_api.php',
				type: 'POST',
				data: { action: 'resetear_clave', usuario: usuario, nueva_clave: nuevaClave },
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						cerrarModalReset();
						showAlert('success', '¡Éxito!', response.message);
					} else {
						showAlert('error', 'Error', response.message);
					}
				},
				error: function () {
					showAlert('error', 'Error', 'No se pudo resetear la contraseña');
				}
			});
		}

		// ========================================
		// FUNCIONES AUXILIARES
		// ========================================

		function cerrarModal() {
			$('#modalUsuario').modal('hide');
		}

		function cerrarModalReset() {
			$('#modalResetClave').modal('hide');
		}

		function togglePassword(inputId, el) {
			var input = document.getElementById(inputId);
			var icon = el.querySelector('i');
			if (input.type === 'password') {
				input.type = 'text';
				icon.classList.remove('fa-eye');
				icon.classList.add('fa-eye-slash');
			} else {
				input.type = 'password';
				icon.classList.remove('fa-eye-slash');
				icon.classList.add('fa-eye');
			}
		}

		function showAlert(type, title, message) {
			Swal.fire({
				icon: type,
				title: title,
				text: message,
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: true,
				didOpen: function (toast) {
					toast.addEventListener('mouseenter', Swal.stopTimer);
					toast.addEventListener('mouseleave', Swal.resumeTimer);
				}
			});
		}

		// ========================================
		// AVATAR GENERATOR (reutilizado de home.php)
		// ========================================
		function generarAvatar(nombre, apellido) {
			let canvas = document.createElement('canvas');
			canvas.style.display = 'none';
			canvas.width = 160;
			canvas.height = 160;
			document.body.appendChild(canvas);
			let context = canvas.getContext('2d');

			let colores = ['#00A97A', '#9E2AB5', '#ECECEC', '#C6C6C6', '#9A9A9A', '#737373', '#545454', '#202020', '#F0EDE6', '#CAC6BF', '#9C9A94', '#74746F', '#555451', '#31312E', '#2DA0D3', '#E40147', '#F29000', '#BFCF00'];
			let color = colores[Math.floor(Math.random() * colores.length)];

			context.beginPath();
			context.arc(80, 80, 80, 0, Math.PI * 2, false);
			context.fillStyle = color;
			context.fill();

			context.beginPath();
			context.arc(80, 80, 80 * 0.6, 0, Math.PI * 2, false);
			context.fillStyle = colorDarker(color);
			context.fill();

			context.font = 'bold 70px Arial';
			context.fillStyle = '#ffffff';
			context.textAlign = 'center';
			context.textBaseline = 'middle';
			context.fillText(nombre.charAt(0) + apellido.charAt(0), 80, 85);

			let img = document.getElementById('avatar');
			img.src = canvas.toDataURL('image/png');
			document.body.removeChild(canvas);
		}

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
