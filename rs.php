<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
	// Si el usuario no está logueado, redirígelo a index.php
	header('Location: index.php');
	exit;
}

$arttipo = $_GET['arttipo'];
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
	<!--link rel="stylesheet" type="text/css" href="plugins/dt/css/dataTables.bootstrap5.min.css">
		<link rel="stylesheet" type="text/css" href="plugins/dt/css/responsive.bootstrap5.min.css">
		<link rel="stylesheet" type="text/css" href="plugins/dt/css/buttons.bootstrap5.min.css">
		<link rel="stylesheet" type="text/css" href="plugins/dt/css/searchBuilder.bootstrap5.min.css"-->
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/fixedColumns.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="plugins/dt/css/dataTables.dateTime.min.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" type="text/css" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<style>
		#listaArchivos,
		#listaArchivosEdit {
			list-style-type: none;
		}

		th {
			font-size: 0.9em;
			border-top: 1px solid #dddddd;
			border-bottom: 1px solid #dddddd;
			border-right: 1px solid #dddddd;
		}

		th:first-child {
			border-left: 1px solid #dddddd;
		}

		table.dataTable td {
			font-size: 0.9em;
		}

		table.dataTable tr.dtrg-level-0 td {
			font-size: 1.1em;
		}

		td {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			max-width: 250px;
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
							<a href="importaciones.php" class="nav-link">
								<i class="nav-icon fas fa-upload"></i>
								<p>Importaciones AE</p>
							</a>
						</li>
						<li class="nav-item menu-open">
							<a href="#" class="nav-link active">
								<i class="nav-icon fas fa-chart-pie"></i>
								<p>
									Maestros
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item <?= $arttipo !='PRODUCTO AE' ? 'menu-open' : '' ?>">
									<a href="#" class="nav-link active">
										<i class="far fa-circle nav-icon <?= $arttipo !='PRODUCTO AE' ? 'text-danger' : '' ?>"></i>
										<p>
											Registros Sanitarios HC
											<i class="right fas fa-angle-left"></i>
										</p>
									</a>
									<ul class="nav nav-treeview">
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA AVITUM" class="nav-link" <?php if ($arttipo == "PRODUCTO LINEA AVITUM") {
												echo "active";
											} ?>>
												<i
													class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO LINEA AVITUM") {
														echo "text-success";
													} ?>"></i>
												<p>Producto L&iacute;nea AVITUM</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC AIS" class="nav-link" <?php if ($arttipo == "PRODUCTO LINEA HC AIS") {
												echo "active";
											} ?>>
												<i
													class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO LINEA HC AIS") {
														echo "text-success";
													} ?>"></i>
												<p>Producto L&iacute;nea HC AIS</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC BC" class="nav-link" <?php if ($arttipo == "PRODUCTO LINEA HC BC") {
												echo "active";
											} ?>">
												<i
													class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO LINEA HC BC") {
														echo "text-success";
													} ?>"></i>
												<p>Producto L&iacute;nea HC BC</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC CN" class="nav-link" <?php if ($arttipo == "PRODUCTO LINEA HC CN") {
												echo "active";
											} ?>>
												<i
													class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO LINEA HC CN") {
														echo "text-success";
													} ?>"></i>
												<p>Producto L&iacute;nea HC CN</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA HC PC VA" class="nav-link" <?php if ($arttipo == "PRODUCTO LINEA HC PC VA") {
												echo "active";
											} ?>>
												<i
													class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO LINEA HC PC VA") {
														echo "text-success";
													} ?>"></i>
												<p>Producto L&iacute;nea HP PC VA</p>
											</a>
										</li>
										<li class="nav-item">
											<a href="rs.php?arttipo=PRODUCTO LINEA OPM" class="nav-link" <?php if ($arttipo == "PRODUCTO LINEA OPM") {
												echo "active";
											} ?>>
												<i
													class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO LINEA OPM") {
														echo "text-success";
													} ?>"></i>
												<p>Producto L&iacute;nea OPM</p>
											</a>
										</li>
									</ul>
								</li>

								<li class="nav-item <?= $arttipo =='PRODUCTO AE' ? 'menu-open' : '' ?>">
									<a href="#" class="nav-link active">
										<i class="far fa-circle nav-icon <?= $arttipo =='PRODUCTO AE' ? 'text-danger' : '' ?>"></i>
										<p>
											Registros Sanitarios AE
											<i class="right fas fa-angle-left"></i>
										</p>
									</a>
									<ul class="nav nav-treeview">
										<li class="nav-item">
											<a href="rs_ae.php?arttipo=PRODUCTO AE" class="nav-link" <?php if ($arttipo == "PRODUCTO AE") {
												echo "active";
											} ?>>
												<i
													class="far fa-dot-circle nav-icon <?php if ($arttipo == "PRODUCTO AE") {
														echo "text-success";
													} ?>"></i>
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
			<!-- /.sidebar -->
		</aside>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<!--section class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Registros Sanitarios</h1>
							</div>
							<div class="col-sm-6">
								<ol class="breadcrumb float-sm-right">
									<li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
								</ol>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
			<!--/section-->
			<!-- Main content -->
			<section class="content pt-3">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="card card-secondary">
								<div class="card-header">
									<h3 class="card-title">MAESTROS | Registros Sanitarios - <?php echo $arttipo; ?>
									</h3>
									<!--div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-minus"></i>
											</button>
										</div-->
								</div>
								<div class="card-body">
									<div class="btn-group btn-group-toggle mt-3 mb-1" data-toggle="buttons">
										<label class="btn btn-success">
											<input type="radio" name="filtro1" id="" autocomplete="off" checked="">
											Todos
										</label>
										<label class="btn btn-success">
											<input type="radio" name="filtro1" id="VENCIDO" autocomplete="off"> Vencidos
										</label>
										<label class="btn btn-success">
											<input type="radio" name="filtro1" id="VENCE_1_MES" autocomplete="off">
											Vence 1 Mes
										</label>
										<label class="btn btn-success">
											<input type="radio" name="filtro1" id="VENCE_2_MESES" autocomplete="off">
											Vence 2 Meses
										</label>
										<label class="btn btn-success">
											<input type="radio" name="filtro1" id="VENCE_3_MESES" autocomplete="off">
											Vence 3 Meses
										</label>
									</div>
									<table id="registrosanitario"
										class="display compact cell-border table-striped hover" style="width:100%">
										<thead>
											<tr>
												<th class="text-center">ID</th>
												<th class="text-center">ARTID</th>
												<th class="text-center">C&Oacute;DIGO</th>
												<th class="text-center">DESCRIPCI&Oacute;N</th>
												<th class="text-center">REGISTRO SANITARIO</th>
												<th class="text-center">RESOLUCI&Oacute;N / OFICIO</th>
												<th class="text-center">EMISI&Oacute;N</th>
												<th class="text-center">APROBACI&Oacute;N</th>
												<th class="text-center">VENCIMIENTO</th>
												<th class="text-center">ESTADO</th>
												<th class="text-center">FABRICANTE</th>
												<th class="text-center">ORIGEN</th>
												<th class="text-center">LUGAR FABRICACI&Oacute;N</th>
												<th class="text-center">FORMA FARMAC&Eacute;UTICA</th>
												<th class="text-center">CONCENTRACI&Oacute;N</th>
												<th class="text-center">PRESENTACI&Oacute;N</th>
												<th class="text-center">NIVEL RIESGO</th>
												<th class="text-center">MATERIAL PCB</th>
												<th class="text-center">COMERCIALIZADO</th>
												<th class="text-center">LABEL ALEMANIA</th>
												<th class="text-center">EXONERACI&Oacute;N CM</th>
												<th class="text-center">PRESENT (UN)</th>
												<th class="text-center">EAN13 (CJ)</th>
												<th class="text-center">EAN13 (UN)</th>
												<th class="text-center">PRESENT (CJ)</th>
												<th class="text-center">GTIN (UN)</th>
												<th class="text-center">GTIN (CJ)</th>
												<th class="text-center">PROYECTO</th>
												<th class="text-center">COD PROVEEDOR</th>
												<th class="text-center">PROVEEDOR</th>
												<th class="text-center">PROYECTO RA AE</th>
												<th class="text-center">OBSERVACIONES</th>
												<?php if ($_SESSION['nivel'] === 'EDITOR') { ?>
													<th class="text-center">OPCI&Oacute;N</th><?php } ?>
											</tr>
										</thead>
									</table>
								</div>
								<!--div class="card-footer">
										<small>Puedes actualizar el registro haciendo clic en el bot&oacute;n Editar</small>
									</div-->
							</div>
						</div>
					</div>
				</div>
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
	<!-- Button trigger modal -->
	<button type="button" id="btneditmodal" name="btneditmodal" class="btn btn-primary" data-toggle="modal"
		data-target="#editModal" hidden>Edit Modal</button>
	<!-- Modal -->
	<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
		aria-labelledby="editModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Editar Registro Sanitario</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="row" hidden>
							<div class="col-md-6">
								<div class="form-group">
									<label for="modalID">ID</label>
									<input type="text" class="form-control" id="modalID" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="ArtID">ArtID</label>
									<input type="text" class="form-control" id="ArtID" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="codigo">C&oacute;digo</label>
									<input type="text" class="form-control" id="codigo" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div id="accordion">
									<div class="card card-success">
										<div class="card-header">
											<h4 class="card-title w-100">
												<a id="tabarticuloedit" class="d-block w-100" data-toggle="collapse"
													href="#collapseOne">
													<span id="artcoddes">Informaci&oacute;n del Producto</span> <i
														class="fas fa-arrow-circle-right"></i>
												</a>
											</h4>
										</div>
										<div id="collapseOne" class="collapse" data-parent="#accordion">
											<div class="card-body">
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="descripcion">Descripci&oacute;n</label>
															<input type="text" class="form-control" id="descripcion">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="arttipo">Tipo de Producto</label>
															<select class="form-control form-select" id="arttipo" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> disabled <?php } ?>>
																<!--option value="PRODUCTO LINEA AESCULAP">PRODUCTO LINEA AESCULAP</option-->
																<option value="PRODUCTO LINEA AVITUM">PRODUCTO LINEA
																	AVITUM</option>
																<option value="PRODUCTO LINEA HC AIS">PRODUCTO LINEA HC
																	AIS</option>
																<option value="PRODUCTO LINEA HC BC">PRODUCTO LINEA HC
																	BC</option>
																<option value="PRODUCTO LINEA HC CN">PRODUCTO LINEA HC
																	CN</option>
																<option value="PRODUCTO LINEA HC PC VA">PRODUCTO LINEA
																	HC PC VA</option>
																<option value="PRODUCTO LINEA OPM">PRODUCTO LINEA OPM
																</option>
																<!--option value="PRODUCTO NACIONAL">PRODUCTO NACIONAL</option>
																	<option value="PRODUCTO PROVEEDOR LOCAL">PRODUCTO PROVEEDOR LOCAL</option-->
															</select>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="origen">Origen</label>
															<input type="text" class="form-control" id="origen" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="fabricante">Fabricante</label>
															<input type="text" class="form-control" id="fabricante"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly
																<?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="lugarfab">Lugar Fabricaci&oacute;n</label>
															<input type="text" class="form-control" id="lugarfab" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label for="formafarma">Forma Farmac&eacute;utica</label>
															<input type="text" class="form-control" id="formafarma"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly
																<?php } ?>>
															<!--select class="form-control form-select" id="formafarma"
																	<option value=""></option>
																	<option value="CONCENTRADO PARA SOLUCIÓN DE PERFUSIÓN">CONCENTRADO PARA SOLUCION DE PERFUSION</option>
																	<option value="DISOLVENTE PARA USO PARENTERAL">DISOLVENTE PARA USO PARENTERAL</option>
																	<option value="EMULSIÓN PARA PERFUSIÓN">EMULSION PARA PERFUSION</option>
																	<option value="SOLUCIÓN INYECTABLE Y PARA PERFUSIÓN">SOLUCION INYECTABLE Y PARA PERFUSION</option>
																	<option value="SOLUCIÓN ORAL">SOLUCION ORAL</option>
																	<option value="SOLUCIÓN PARA HEMOFILTRACIÓN">SOLUCION PARA HEMOFILTRACION</option>
																	<option value="SOLUCIÓN PARA IRRIGACIÓN">SOLUCION PARA IRRIGACION</option>
																	<option value="SOLUCIÓN PARA PERFUSIÓN">SOLUCION PARA PERFUSION</option>
																	<option value="NO APLICA">NO APLICA</option>
																	<option value="OTRO">OTRO</option>
																</select-->
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="concentracion">Concentraci&oacute;n</label>
															<input type="text" class="form-control" id="concentracion"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly
																<?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="presentacion">Presentaci&oacute;n</label>
															<input type="text" class="form-control" id="presentacion"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly
																<?php } ?>>
															<!--select class="form-control form-select" id="presentacion" 
																	<option value="Caja x 4 Frascos">CAJA X 4 FRASCOS</option>
																	<option value="Caja x 12 Frascos">CAJA X 12 FRASCOS</option>
																	<option value="Caja x 20 Frascos">CAJA X 20 FRASCOS</option>
																	<option value="Caja x 24 Frascos">CAJA X 24 FRASCOS</option>
																	<option value="Caja x 50 Frascos">CAJA X 50 FRASCOS</option>
																	<option value="Fco x 250 mL">FCO X 250ML</option>
																	<option value="Fco x 500 mL">FCO X 500ML</option>
																	<option value="Fco x 1000 mL">FCO X 1000ML</option>
																	<option value="Galonera x 4 Litros">GALONERA X 4 LITROS</option>
																	<option value="NO APLICA">NO APLICA</option>
																	<option value="OTRO">OTRO</option>
																</select-->
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="nivelriesgo">Nivel de Riesgo</label>
															<select class="form-control form-select" id="nivelriesgo"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> disabled
																<?php } ?>>
																<option value=""></option>
																<option value="I">I</option>
																<option value="II">II</option>
																<option value="III">III</option>
																<option value="IV">IV</option>
																<option value="NO APLICA">NO APLICA</option>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="materialpcb">Material Policarbonato</label>
															<input type="text" class="form-control" id="materialpcb"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly
																<?php } ?>>
															<!--select class="form-control form-select" id="materialpcb" >
																	<option value="SI">SI</option>
																	<option value="NO">NO</option>
																	<option value="CONECTOR SAFSITE">CONECTOR SAFSITE</option>
																	<option value="NO MENCIONA MATERIAL">NO MENCIONA MATERIAL</option>
																	<option value="VALVULA Y">VALVULA Y</option>
																	<option value="NO APLICA">NO APLICA</option>
																</select-->
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="comercializado">Comercializado</label>
															<select class="form-control form-select" id="comercializado"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> disabled
																<?php } ?>>
																<option value=""></option>
																<option value="SI">SI</option>
																<option value="NO">NO</option>
																<option value="NO APLICA">NO APLICA</option>
															</select>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="labelalemania">Label Alemania</label>
															<select class="form-control form-select" id="labelalemania"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> disabled
																<?php } ?>>
																<option value=""></option>
																<option value="SI">SI</option>
																<option value="NO">NO</option>
																<option value="NO APLICA">NO APLICA</option>
															</select>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="exoneracioncm">Exoneracion CM</label>
															<input type="text" class="form-control" id="exoneracioncm"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly
																<?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="proyectoart">Proyecto</label>
															<select class="form-control form-select" id="proyectoart"
																<?php if ($_SESSION['nivel'] === 'VISOR') { ?> disabled
																<?php } ?>>
																<option value=""></option>
																<option value="E">ESTERIL</option>
																<option value="NE">NO ESTERIL</option>
																<option value="NO APLICA">NO APLICA</option>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="ean13un">C&oacute;digo EAN 13 (unid)</label>
															<input type="text" class="form-control" id="ean13un" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="barraun">Presentacion (unid)</label>
															<!-- C&oacute;digo de Barras (unid) -->
															<input type="text" class="form-control" id="barraun" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="barracj">C&oacute;digo EAN 13 (caja)</label>
															<!-- C&oacute;digo de Barras (caja) -->
															<input type="text" class="form-control" id="barracj" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="ean14cj">Presentacion (caja))</label>
															<input type="text" class="form-control" id="ean14cj" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="gtinun">C&oacute;digo GTIN (unid)</label>
															<!-- C&oacute;digo GTIN (unid) -->
															<input type="text" class="form-control" id="gtinun" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="gtincj">C&oacute;digo GTIN (caja)</label>
															<!-- C&oacute;digo GTIN (caja) -->
															<input type="text" class="form-control" id="gtincj" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="gmdn">GTIN Presentacion (unid)</label>
															<!-- C&oacute;digo GMDN -->
															<input type="text" class="form-control" id="gmdn" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="umdns">GTIN Presentacion (caja)</label>
															<!-- C&oacute;digo UMDNS -->
															<input type="text" class="form-control" id="umdns" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-7">
														<!-- textarea -->
														<div class="form-group">
															<label for="proyecto">Proyecto RA AE</label>
															<textarea class="form-control" rows="5"
																placeholder="proyecto" id="proyecto" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>></textarea>
														</div>
													</div>
													<div class="col-md-5">
														<div class="col-md-12">
															<div class="form-group">
																<label for="proveedor">Proveedor</label>
																<input type="text" class="form-control" id="proveedor"
																	<?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly
																	<?php } ?>>
															</div>
														</div>
														<div class="col-md-12">
															<div class="form-group">
																<label for="proveedordes">Proveedor
																	Descripci&oacute;n</label>
																<input type="text" class="form-control"
																	id="proveedordes" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="rs">Registro Sanitario</label>
									<input type="text" class="form-control" id="rs" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="resolucion">Resoluci&oacute;n / Oficio</label>
									<input type="text" class="form-control" id="resolucion" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="emision">Emision</label>
									<input type="date" class="form-control" id="emision" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="aprobacion">Aprobaci&oacute;n</label>
									<input type="date" class="form-control" id="aprobacion" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="vencimiento">Vencimiento</label>
									<input type="date" class="form-control" id="vencimiento" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="estadors">Estado</label>
									<select class="form-control form-select" id="estadors" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> disabled <?php } ?>>
										<option value="BAJA">BAJA</option>
										<option value="RENOVACION">EN RENOVACI&Oacute;N</option>
										<option value="TRAMITE">EN TR&Aacute;MITE</option>
										<option value="PRORROGADO">PRORROGADO</option>
										<option value="VENCIDO">VENCIDO</option>
										<option value="VENCE 1 MES">VENCE 1 MES</option>
										<option value="VENCE 2 MESES">VENCE 2 MESES</option>
										<option value="VENCE 3 MESES">VENCE 3 MESES</option>
										<option value="VIGENTE">VIGENTE</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<!-- textarea -->
								<div class="form-group">
									<label>Observaciones</label>
									<textarea class="form-control" rows="3" placeholder="Observaciones"
										id="observaciones" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="inputFileEdit">Documentos Adjuntos</label>
									<div class="input-group">
										<input type="file" class="form-control" id="inputFileEdit"
											aria-describedby="inputFileEditAdd" aria-label="Upload" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> readonly <?php } ?>>
										<button onclick="agregarArchivoEdit()" class="btn btn-outline-secondary"
											type="button" id="inputFileEditAdd" <?php if ($_SESSION['nivel'] === 'VISOR') { ?> disabled <?php } ?>>Agregar archivo</button>
									</div>
								</div>
								<?php if ($_SESSION['nivel'] === 'EDITOR') { ?>
									<ul id="listaArchivosEdit"></ul><?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="ucreacion">Usuario Creaci&oacute;n RS</label>
									<input type="text" class="form-control" id="ucreacion" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="creacion">Fecha Creaci&oacute;n RS</label>
									<input type="text" class="form-control" id="creacion" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="umodificacion">Usuario Modificaci&oacute;n RS</label>
									<input type="text" class="form-control" id="umodificacion" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="modificacion">Fecha Modificaci&oacute;n RS</label>
									<input type="text" class="form-control" id="modificacion" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="artucreacion">Usuario Creaci&oacute;n ART</label>
									<input type="text" class="form-control" id="artucreacion" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="artcreacion">Fecha Creaci&oacute;n ART</label>
									<input type="text" class="form-control" id="artcreacion" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="artumodificacion">Usuario Modificaci&oacute;n ART</label>
									<input type="text" class="form-control" id="artumodificacion" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="artmodificacion">Fecha Modificaci&oacute;n ART</label>
									<input type="text" class="form-control" id="artmodificacion" readonly>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<!--button type="button" class="btn btn-danger" onclick="eliminar()">Eliminar Registro</button-->
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<div>
						<button type="button" class="btn-close" data-dismiss="modal" data-target="#editModal"
							aria-label="Close" id="modeditcierra" hidden></button>
						<?php if ($_SESSION['nivel'] === 'EDITOR') { ?><button type="button" class="btn btn-success"
								onclick="guardar()">Actualizar Registro</button><?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- ./modal -->
	<!-- Button trigger modal -->
	<button type="button" id="btnnewmodal" name="btnnewmodal" class="btn btn-primary" data-toggle="modal"
		data-target="#newModal" hidden>New Modal</button>
	<!-- Modal -->
	<div class="modal fade" id="newModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
		aria-labelledby="newModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Nuevo Registro Sanitario</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row" hidden>
						<div class="col-md-6">
							<div class="form-group">
								<label for="newmodalID">ID</label>
								<input type="text" class="form-control" id="newmodalID" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="newArtID">newArtID</label>
								<input type="text" class="form-control" id="newArtID" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="newcodigo">C&oacute;digo</label>
								<input type="text" class="form-control" id="newcodigo" readonly>
							</div>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<label for="newdescripcion">Descripci&oacute;n</label>
								<input type="text" class="form-control" id="newdescripcion" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div id="newaccordion">
								<div class="card card-success">
									<div class="card-header">
										<h4 class="card-title w-100">
											<a id="tabarticulo" class="d-block w-100" data-toggle="collapse"
												href="#collapseOnenew">
												<span id="newartcoddes">Informaci&oacute;n del Producto</span> <i
													class="fas fa-arrow-circle-right"></i>
											</a>
										</h4>
									</div>
									<div id="collapseOnenew" class="collapse" data-parent="#newaccordion">
										<div class="card-body">
											<div class="row">
												<table id="dtnewproducto" class="table-striped display compact"
													style="width:100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>C&oacute;digo</th>
															<th>Descripci&oacute;n</th>
															<th>Opci&oacute;n</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="news">Registro Sanitario</label>
								<input type="text" class="form-control" id="newrs">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="newresolucion">Resoluci&oacute;n / Oficio</label>
								<input type="text" class="form-control" id="newresolucion">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="newemision">Emision</label>
								<input type="date" class="form-control" id="newemision">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="newaprobacion">Aprobaci&oacute;n</label>
								<input type="date" class="form-control" id="newaprobacion">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="newvencimiento">Vencimiento</label>
								<input type="date" class="form-control" id="newvencimiento">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="newestadors">Estado Vencimiento</label>
								<select class="form-control form-select" id="newestadors">
									<option value="BAJA">BAJA</option>
									<option value="RENOVACION">EN RENOVACI&Oacute;N</option>
									<option value="TRAMITE">EN TR&Aacute;MITE</option>
									<option value="PRORROGADO">PRORROGADO</option>
									<option value="VENCIDO">VENCIDO</option>
									<option value="VENCE 1 MES">VENCE 1 MES</option>
									<option value="VENCE 2 MESES">VENCE 2 MESES</option>
									<option value="VENCE 3 MESES">VENCE 3 MESES</option>
									<option value="VIGENTE" selected>VIGENTE</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<!-- textarea -->
							<div class="form-group">
								<label>Observaciones / GetRA</label>
								<textarea class="form-control" rows="3" placeholder="Observaciones"
									id="newobservaciones"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="inputFile">Documentos Adjuntos</label>
								<div class="input-group">
									<input type="file" class="form-control" id="inputFile"
										aria-describedby="inputFileAdd" aria-label="Upload">
									<button onclick="agregarArchivo()" class="btn btn-outline-secondary" type="button"
										id="inputFileAdd">Agregar archivo</button>
								</div>
							</div>
							<ul id="listaArchivos"></ul>
						</div>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<!--button type="button" class="btn btn-danger" onclick="eliminar()">Eliminar Registro</button-->
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<div>
						<button type="button" class="btn-close" data-dismiss="modal" data-target="#newModal"
							aria-label="Close" id="modnewcierra" hidden></button>
						<button type="button" class="btn btn-success" onclick="newguardar()">Guardar Nuevo
							Registro</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- ./modal -->
	<!-- Button trigger modal -->
	<button type="button" id="btnmasivomodal" name="btnmasivomodal" class="btn btn-primary" data-toggle="modal"
		data-target="#modalproductomasivo" hidden>Masivo Modal</button>
	<!-- modal -->
	<div class="modal fade" id="modalproductomasivo" data-backdrop="static" data-keyboard="false"
		aria-labelledby="masivoModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Actualizaci&oacute;n Masiva de Productos</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="inputFileMasivo">Archivo de Carga Masiva</label>
								<div class="input-group">
									<input type="file" class="form-control" id="inputFileMasivo"
										aria-describedby="inputFileMasivoAdd" accept=".xlsx, .xls" aria-label="Upload">
									<button onclick="agregarArchivoMasivo()" class="btn btn-outline-secondary"
										type="button" id="inputFileMasivoAdd" hidden><i class="fas fa-plus-square"></i>
										Agregar archivo</button>
								</div>
							</div>
							<!-- Nuevo elemento para el enlace de descarga -->
							<div class="form-group">
								<a href="reports/UpdateMasivoRS.xlsx" target="_blank" class="btn btn-success" download>
									<i class="fas fa-download"></i> Descargar Plantilla de Carga Masiva
								</a>
							</div>
							<ul id="listaArchivosMasivo"></ul>
						</div>
					</div>
					<div class="row" hidden>
						<table id="excelTable" border="1">
							<thead>
								<tr>
									<th>ID</th>
									<th>SKU</th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
								<!-- Contenido de la tabla se agregará aquí -->
							</tbody>
						</table>
					</div>
					<div>
						<p class="text-danger">- El proceso de actualizaci&oacute;n masiva requiere siempre información
							en el campo ArtCodigo.</p>
					</div>
					<div>
						<p class="text-danger">- El proceso de actualizaci&oacute;n masiva actualizar&aacute; solo
							productos con la condici&oacute;n comercializado = "SI".</p>
					</div>
					<div>
						<p class="text-danger">- El proceso de actualizaci&oacute;n masiva tomar&aacute; en cuenta
							solamente los campos con informaci&oacute;n.</p>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn-close" data-dismiss="modal" data-target="#modalproductomasivo"
						aria-label="Close" id="modalproductomasivocierra" hidden></button>
					<div>
						<button type="button" class="btn btn-success" onclick="cargamasiva()">Procesar Archivo</button>
					</div>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- Button trigger modal -->
	<button type="button" id="btncreamodal" name="btncreamodal" class="btn btn-primary" data-toggle="modal"
		data-target="#modalproductocrea" hidden>Crea Producto</button>
	<!-- modal -->
	<div class="modal fade" id="modalproductocrea" data-backdrop="static" data-keyboard="false"
		aria-labelledby="masivoModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Registro de Nuevo de Producto</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="row col-md-12">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codprodcrea">C&oacute;digo</label>
									<input type="text" class="form-control" id="codprodcrea">
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label for="desprodcrea">Descripci&oacute;n</label>
									<input type="text" class="form-control" id="desprodcrea">
								</div>
							</div>
						</div>
						<div class="row col-md-12">
							<div class="col-md-12">
								<div class="form-group">
									<label for="arttipocrea">Tipo de Producto</label>
									<select class="form-control form-select" id="arttipocrea">
										<option value="PRODUCTO LINEA AVITUM">PRODUCTO LINEA AVITUM</option>
										<option value="PRODUCTO LINEA HC AIS">PRODUCTO LINEA HC AIS</option>
										<option value="PRODUCTO LINEA HC BC">PRODUCTO LINEA HC BC</option>
										<option value="PRODUCTO LINEA HC CN">PRODUCTO LINEA HC CN</option>
										<option value="PRODUCTO LINEA HC PC VA">PRODUCTO LINEA HC PC VA</option>
										<option value="PRODUCTO LINEA OPM">PRODUCTO LINEA OPM</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div>
						<p class="text-danger">- La informaci&oacute;n adicional del producto podr&aacute; ser agregada
							posterior a la asignación del RS.</p>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn-close" data-dismiss="modal" data-target="#modalproductocrea"
						aria-label="Close" id="modalproductocreacierra" hidden></button>
					<div>
						<button type="button" class="btn btn-success" onclick="cargacrea()">Crear Producto</button>
					</div>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
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
	<!--script src="plugins/dt/js/dataTables.bootstrap5.min.js"></script>
		<script src="plugins/dt/js/dataTables.dateTime.min.js"></script>			
		<script src="plugins/dt/js/dataTables.responsive.min.js"></script>
		<script src="plugins/dt/js/responsive.bootstrap5.min.js"></script-->
	<script src="plugins/dt/js/dataTables.fixedColumns.min.js"></script>
	<script src="plugins/dt/js/dataTables.buttons.min.js"></script>
	<!--script src="plugins/dt/js/buttons.bootstrap5.min.js"></script-->
	<script src="plugins/dt/js/jszip.min.js"></script>
	<script src="plugins/dt/js/pdfmake.min.js"></script>
	<script src="plugins/dt/js/vfs_fonts.js"></script>
	<script src="plugins/dt/js/buttons.html5.min.js"></script>
	<script src="plugins/dt/js/buttons.print.min.js"></script>
	<script src="plugins/dt/js/buttons.colVis.min.js"></script>
	<!-- SweetAlert2 -->
	<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
	<!-- bs-custom-file-input -->
	<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
	<!-- Excel -->
	<script src="plugins/excel/xlsx.full.min.js"></script>
	<script>
		$(document).ready(function () {
			var table = $('#registrosanitario').DataTable({
				dom: '<"top"Bf>rt<"bottom"lip><"clear">',
				lengthMenu: [
					[20, 50, 100, 200],
					['20 filas', '50 filas', '100 filas', '200 filas']
				],
				buttons: [
					<?php if ($_SESSION['nivel'] === 'EDITOR') { ?>	
							{
							text: '<i class="fas fa-box"></i>&nbsp;Nuevo Producto',
							className: 'btn btn-success',
							action: function (e, dt, node, config) {
								document.getElementById("btncreamodal").click();
							}
						},
						'spacer',
						{
							text: '<i class="fas fa-plus"></i>&nbsp;Nuevo Registro Sanitario',
							className: 'btn btn-dark',
							action: function (e, dt, node, config) {
								document.getElementById("btnnewmodal").click();
							}
						},
						'spacer',
						{
							text: '<i class="fas fa-upload"></i>&nbsp;Actualización Masiva',
							className: 'btn btn-warning',
							action: function (e, dt, node, config) {
								document.getElementById("btnmasivomodal").click();
							}
						},
						'spacer',
					<?php } ?>
						{
						extend: 'print',
						className: 'btn btn-primary',
						orientation: 'landscape',
						text: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">  <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/>  <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/></svg>'
					},
					'spacer',
					{
						extend: 'excel',
						className: 'btn btn-primary',
						text: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel-fill" viewBox="0 0 16 16">  <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M5.884 6.68 8 9.219l2.116-2.54a.5.5 0 1 1 .768.641L8.651 10l2.233 2.68a.5.5 0 0 1-.768.64L8 10.781l-2.116 2.54a.5.5 0 0 1-.768-.641L7.349 10 5.116 7.32a.5.5 0 1 1 .768-.64z"/></svg>'
					}
				],
				//responsive: true,
				fixedColumns: {
					left: 2
						<?php if ($_SESSION['nivel'] === 'EDITOR') { ?>, right: 1<?php } ?>
				},
				scrollX: true,
				scrollCollapse: true,
				scroller: true,
				scrollY: 400,
				//paging: false,
				ajax: {
					url: 'scripts/consultar_rs.php',
					data: function (d) {
						d.filter = $('input[name="filtro1"]:checked').attr('id');
						// Obtén los parámetros de la URL
						var urlParams = new URLSearchParams(window.location.search);
						// Obtén el valor del parámetro 'arttipo'
						var urlarttipo = urlParams.get('arttipo');
						// Asigna el valor obtenido a 'filter2'
						d.filter2 = urlarttipo;
					}
				},
				columns: [
					{ data: 'DT_RowId', "visible": false },
					{ data: 'ArtID', "visible": false },
					{ data: 'ArtCodigo' },
					{ data: 'ArtDescripcion' },
					{ data: 'RegNumero' },
					{ data: 'RegResolucion' },
					{ data: 'RegFechaEmision' },
					{ data: 'RegFechaAprobacion' },
					{ data: 'RegFechaVencimiento' },
					{ data: 'RegEstadoVencimiento' },
					{ data: 'ArtFabricante' },
					{ data: 'ArtOrigen' },
					{ data: 'ArtLugarFabricacion' },
					{ data: 'ArtFormaFarma' },
					{ data: 'ArtConcentracion' },
					{ data: 'ArtPresentacion' },
					{ data: 'ArtNivelRiesgo' },
					{ data: 'ArtMaterialPCB' },
					{ data: 'ArtComercializado' },
					{ data: 'ArtLabelAlemania' },
					{ data: 'ArtExoneracionCM' },
					{ data: 'ArtBarraUn' },
					{ data: 'ArtBarraCj' },
					{ data: 'ArtEAN13Un' },
					{ data: 'ArtEAN14Cj' },
					{ data: 'ArtGTINUn' },
					{ data: 'ArtGTINCj' },
					{ data: 'ArtProyecto' },
					{ data: 'ArtProveedor' },
					{ data: 'ArtProveedorDes' },
					{ data: 'ArtProyectoRAAE' },
					{ data: 'RegObservaciones' }
						<?php if ($_SESSION['nivel'] === 'EDITOR') { ?>
						, {
							// Columna adicional para el botón de edición
							data: null,
							className: "center",
							defaultContent: '<button type="button" class="btn btn-success btn-sm editbtn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg></button>'
						}
						<?php } ?>
				],
				columnDefs: [
					<?php if ($_SESSION['nivel'] === 'EDITOR') { ?>
							{
							"targets": 32, // your case first column
							"className": "text-center",
							"width": "2%"
						},
					<?php } ?>					   
						{
						"targets": 30//, // the column you want to truncate
						//"render": function (data, type, row) {
						//	return type === 'display' && data.length > 60 ?
						//		data.substr(0, 60) + '…' :
						//		data;
						//}
					},
					{
						"targets": 31//, // the column you want to truncate
						//"render": function (data, type, row) {
						//	return type === 'display' && data.length > 60 ?
						//		data.substr(0, 60) + '…' :
						//		data;
						//}
					}
				],
				rowCallback: function (row, data, index) {
					if (data['RegEstadoVencimiento'] == 'RENOVACION') {
						$(row).css('background-color', '#5DDBBC');
					} else if (data['RegEstadoVencimiento'] == 'TRAMITE') {
						$(row).css('background-color', '#AADEFF');
					} else if (data['RegEstadoVencimiento'] == 'BAJA') {
						$(row).css('background-color', '#F7B95A');
					} else if (data['RegEstadoVencimiento'] == 'VENCIDO') {
						$(row).css('background-color', '#F66C7D');
					} else if (data['RegEstadoVencimiento'] == 'VENCE 1 MES') {
						$(row).css('background-color', '#D6E37D');
					} else if (data['RegEstadoVencimiento'] == 'VENCE 2 MESES') {
						$(row).css('background-color', '#D6E37D');
					} else if (data['RegEstadoVencimiento'] == 'VENCE 3 MESES') {
						$(row).css('background-color', '#D6E37D');
					} else if (data['RegEstadoVencimiento'] == 'VENCE ESTE MES') {
						$(row).css('background-color', '#D6E37D');
					}
				},
				language: {
					info: 'Mostrando página _PAGE_ de _PAGES_',
					infoEmpty: 'No existen registros',
					infoFiltered: '(filtrado de _MAX_ registros totales)',
					search: "Búsqueda por palabra clave:",
					lengthMenu: "_MENU_",
					info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
					zeroRecords: 'No existen registros',
					url: 'plugins/dt/js/es-MX.json'
				},
				processing: true,
				serverSide: true
			});

			$('input[name=filtro1]').on('change', function () {
				table.ajax.reload();
			});

			var table2 = $('#dtnewproducto').DataTable({
				lengthMenu: [
					[5, 10, 15, 20],
					['5 filas', '10 filas', '15 filas', '20 filas']
				],
				responsive: true,
				ajax: 'scripts/consultar_articulos.php',
				columns: [
					{ data: 'DT_RowId', "visible": false },
					{ data: 'ArtCodigo' },
					{ data: 'ArtDescripcion' },
					{
						// Columna adicional para el botón de edición
						data: null,
						className: "center",
						defaultContent: '<button type="button" class="btn btn-success newbtn"><i class="fas fa-check-double"></i></button>'
					}
				],
				language: {
					info: 'Mostrando página _PAGE_ de _PAGES_',
					infoEmpty: 'No existen artículos',
					infoFiltered: '(filtrado de _MAX_ artículos totales)',
					search: "Código de Artículo:",
					lengthMenu: "_MENU_",
					info: "Mostrando _START_ a _END_ de _TOTAL_ artículos",
					zeroRecords: 'No existen artículos',
					url: 'plugins/dt/js/es-MX.json'
				},
				processing: true,
				serverSide: true
			});

			//MODAL EDICIÓN DE REGISTRO SANITARIO
			$('#registrosanitario tbody').on('click', 'button.editbtn', function () {

				var data;
				var tr = $(this).closest('tr'); // Encuentra la fila más cercana al botón de edición
				//Este if corrige el bug de la asignación de data cuando el dt está en modo responsive
				if (tr.hasClass('child')) {
					// Si la fila es una fila hija (es decir, está en modo responsive), obtén los datos de la fila padre
					data = table.row(tr.prev()).data();
				} else {
					// Si no, obtén los datos de la fila normalmente
					data = table.row(tr).data();
				}

				//console.log(data); // Aquí puedo manejar los datos como necesite					
				// Abre el modal
				document.getElementById("btneditmodal").click();
				// Muestra los datos de la fila en el modal
				$('#modalID').val(data.DT_RowId);
				$('#ArtID').val(data.ArtID);
				$('#codigo').val(data.ArtCodigo);
				$('#descripcion').val(data.ArtDescripcion);

				var codigo = data.ArtCodigo;
				var descripcion = data.ArtDescripcion;
				var tipo = data.ArtTipo;

				// Concatena los valores con " - "
				var concatenado = codigo + " - " + descripcion + " [" + tipo + "] ";

				// Asigna el valor concatenado al span con id="artcoddes"
				$('#artcoddes').text(concatenado);

				$('#rs').val(data.RegNumero);
				$('#resolucion').val(data.RegResolucion);

				// Convierte las fechas antes de asignarlas
				$('#emision').val(convertirFecha(data.RegFechaEmision));
				$('#aprobacion').val(convertirFecha(data.RegFechaAprobacion));
				$('#vencimiento').val(convertirFecha(data.RegFechaVencimiento));

				$('#estadors').val(data.RegEstadoVencimiento);
				$('#gmdn').val(data.ArtCodigoGMDN);
				$('#umdns').val(data.ArtCodigoUMDNS);
				$('#proyecto').val(data.ArtProyectoRAAE);
				$('#observaciones').val(data.RegObservaciones);

				$('#creacion').val(data.RegFechaCreacion);
				$('#ucreacion').val(data.RegUsuarioCreacion);
				$('#modificacion').val(data.RegFechaModificacion);
				$('#umodificacion').val(data.RegUsuarioModificacion);

				$('#artcreacion').val(data.ArtFechaCreacion);
				$('#artucreacion').val(data.ArtUsuarioCreacion);
				$('#artmodificacion').val(data.ArtFechaModificacion);
				$('#artumodificacion').val(data.ArtUsuarioModificacion);

				//Informacion del producto
				$('#arttipo').val(data.ArtTipo);
				$('#fabricante').val(data.ArtFabricante);
				$('#origen').val(data.ArtOrigen);
				$('#lugarfab').val(data.ArtLugarFabricacion);
				$('#formafarma').val(data.ArtFormaFarma);
				$('#concentracion').val(data.ArtConcentracion);
				$('#presentacion').val(data.ArtPresentacion);
				$('#nivelriesgo').val(data.ArtNivelRiesgo);
				$('#materialpcb').val(data.ArtMaterialPCB);
				$('#comercializado').val(data.ArtComercializado);
				$('#labelalemania').val(data.ArtLabelAlemania);
				$('#exoneracioncm').val(data.ArtExoneracionCM);
				$('#barraun').val(data.ArtBarraUn);
				$('#barracj').val(data.ArtBarraCj);
				$('#ean13un').val(data.ArtEAN13Un);
				$('#ean14cj').val(data.ArtEAN14Cj);
				$('#gtinun').val(data.ArtGTINUn);
				$('#gtincj').val(data.ArtGTINCj);
				$('#proyectoart').val(data.ArtProyecto);
				$('#proveedor').val(data.ArtProveedor);
				$('#proveedordes').val(data.ArtProveedorDes);


				// Después de obtener los datos
				var rutas = data.Rutas;

				// Verifica si rutas es diferente de null
				if (rutas) {
					// Divide las rutas por comas para obtener una lista de rutas
					var listaRutas = rutas.split(':');

					// Recorre cada ruta en la lista
					listaRutas.forEach(function (ruta) {
						// Elimina el '../' de la ruta
						var rutaLimpia = ruta.replace('../', '');

						// Encuentra la posición del primer guión
						var posicionGuion = rutaLimpia.indexOf('-');

						// Extrae el nombre del archivo después del primer guión
						var nombreArchivo = rutaLimpia.substring(posicionGuion + 1);

						// Crea un objeto de archivo ficticio con la ruta y el nombre
						var archivoFicticio = {
							name: nombreArchivo,
							ruta: rutaLimpia
						};

						// Llama a la función agregarArchivoEdit con el archivo ficticio
						agregarArchivoEdit(archivoFicticio);
					});
				}
				$('#rs').focus();

			});

			$('#registrosanitario tbody').on('dblclick', 'tr', function () {

				var data;
				var tr = $(this).closest('tr'); // Encuentra la fila más cercana al botón de edición
				//Este if corrige el bug de la asignación de data cuando el dt está en modo responsive
				if (tr.hasClass('child')) {
					// Si la fila es una fila hija (es decir, está en modo responsive), obtén los datos de la fila padre
					data = table.row(tr.prev()).data();
				} else {
					// Si no, obtén los datos de la fila normalmente
					data = table.row(tr).data();
				}

				//console.log(data); // Aquí puedo manejar los datos como necesite					
				// Abre el modal
				document.getElementById("btneditmodal").click();
				// Muestra los datos de la fila en el modal
				$('#modalID').val(data.DT_RowId);
				$('#ArtID').val(data.ArtID);
				$('#codigo').val(data.ArtCodigo);
				$('#descripcion').val(data.ArtDescripcion);

				var codigo = data.ArtCodigo;
				var descripcion = data.ArtDescripcion;
				var tipo = data.ArtTipo;

				// Concatena los valores con " - "
				var concatenado = codigo + " - " + descripcion + " [" + tipo + "] ";

				// Asigna el valor concatenado al span con id="artcoddes"
				$('#artcoddes').text(concatenado);

				$('#rs').val(data.RegNumero);
				$('#resolucion').val(data.RegResolucion);

				// Convierte las fechas antes de asignarlas
				$('#emision').val(convertirFecha(data.RegFechaEmision));
				$('#aprobacion').val(convertirFecha(data.RegFechaAprobacion));
				$('#vencimiento').val(convertirFecha(data.RegFechaVencimiento));

				$('#estadors').val(data.RegEstadoVencimiento);
				$('#gmdn').val(data.ArtCodigoGMDN);
				$('#umdns').val(data.ArtCodigoUMDNS);
				$('#proyecto').val(data.ArtProyectoRAAE);
				$('#observaciones').val(data.RegObservaciones);

				$('#creacion').val(data.RegFechaCreacion);
				$('#ucreacion').val(data.RegUsuarioCreacion);
				$('#modificacion').val(data.RegFechaModificacion);
				$('#umodificacion').val(data.RegUsuarioModificacion);

				$('#artcreacion').val(data.ArtFechaCreacion);
				$('#artucreacion').val(data.ArtUsuarioCreacion);
				$('#artmodificacion').val(data.ArtFechaModificacion);
				$('#artumodificacion').val(data.ArtUsuarioModificacion);

				//Informacion del producto
				$('#arttipo').val(data.ArtTipo);
				$('#fabricante').val(data.ArtFabricante);
				$('#origen').val(data.ArtOrigen);
				$('#lugarfab').val(data.ArtLugarFabricacion);
				$('#formafarma').val(data.ArtFormaFarma);
				$('#concentracion').val(data.ArtConcentracion);
				$('#presentacion').val(data.ArtPresentacion);
				$('#nivelriesgo').val(data.ArtNivelRiesgo);
				$('#materialpcb').val(data.ArtMaterialPCB);
				$('#comercializado').val(data.ArtComercializado);
				$('#labelalemania').val(data.ArtLabelAlemania);
				$('#exoneracioncm').val(data.ArtExoneracionCM);
				$('#barraun').val(data.ArtBarraUn);
				$('#barracj').val(data.ArtBarraCj);
				$('#ean13un').val(data.ArtEAN13Un);
				$('#ean14cj').val(data.ArtEAN14Cj);
				$('#gtinun').val(data.ArtGTINUn);
				$('#gtincj').val(data.ArtGTINCj);
				$('#proyectoart').val(data.ArtProyecto);
				$('#proveedor').val(data.ArtProveedor);
				$('#proveedordes').val(data.ArtProveedorDes);

				// Después de obtener los datos
				var rutas = data.Rutas;

				// Verifica si rutas es diferente de null
				if (rutas) {
					// Divide las rutas por comas para obtener una lista de rutas
					var listaRutas = rutas.split(':');

					// Recorre cada ruta en la lista
					listaRutas.forEach(function (ruta) {
						// Elimina el '../' de la ruta
						var rutaLimpia = ruta.replace('../', '');

						// Encuentra la posición del primer guión
						var posicionGuion = rutaLimpia.indexOf('-');

						// Extrae el nombre del archivo después del primer guión
						var nombreArchivo = rutaLimpia.substring(posicionGuion + 1);

						// Crea un objeto de archivo ficticio con la ruta y el nombre
						var archivoFicticio = {
							name: nombreArchivo,
							ruta: rutaLimpia
						};

						// Llama a la función agregarArchivoEdit con el archivo ficticio
						agregarArchivoEdit(archivoFicticio);
					});
				}
				$('#rs').focus();

			});

			//MODAL NUEVO REGISTRO SANITARIO
			$('#dtnewproducto tbody').on('click', 'button.newbtn', function () {

				var data;
				var tr = $(this).closest('tr'); // Encuentra la fila más cercana al botón de edición
				//Este if corrige el bug de la asignación de data cuando el dt está en modo responsive
				if (tr.hasClass('child')) {
					// Si la fila es una fila hija (es decir, está en modo responsive), obtén los datos de la fila padre
					data = table2.row(tr.prev()).data();
				} else {
					// Si no, obtén los datos de la fila normalmente
					data = table2.row(tr).data();
				}

				var codigo = data.ArtCodigo;
				var ArtID = data.DT_RowId;
				ArtID = ArtID.split('_')[1];
				var descripcion = data.ArtDescripcion;
				var tipo = data.ArtTipo;

				// Concatena los valores con " - "
				var concatenado = codigo + " - " + descripcion + " [" + tipo + "] ";

				// Asigna el valor concatenado al span con id="newartcoddes"
				$('#newartcoddes').text(concatenado);
				// Asigna el valor concatenado al span con id="artcoddes"
				$('#newmodalID').val(data.DT_RowId);
				$('#newArtID').val(ArtID);
				$('#newcodigo').val(codigo);
				$('#newdescripcion').val(descripcion);

				// Colapsa el tab
				var enlace = document.getElementById('tabarticulo');
				var div = document.getElementById('collapseOnenew');
				enlace.classList.add('collapsed');

				// Cambia la propiedad aria-expanded a "false"
				enlace.setAttribute('aria-expanded', 'false');

				// Cambia la clase del div de "collapse show" a "collapse"
				div.className = 'collapse';

				$('#newrs').focus();

			});

			$('#newModal').on('hidden.bs.modal', function () {
				// Restablece el campo de entrada del archivo cuando se cierra el modal
				$('#newartcoddes').text('Información del Producto');
				$('#newArtID').val('');
				$('#newcodigo').val('');
				$("#newrs").val('');
				$("#newresolucion").val('');
				$("#newemision").val('');
				$("#newaprobacion").val('');
				$("#newvencimiento").val('');
				$("#newobservaciones").val('');
				$("#newestadors").val('VIGENTE');
				$('#dtnewproducto').DataTable().ajax.reload();
				$('#dtnewproducto').DataTable().search("").draw();
				// Colapsa el tab
				var enlace = document.getElementById('tabarticulo');
				var div = document.getElementById('collapseOnenew');
				enlace.classList.add('collapsed');

				// Cambia la propiedad aria-expanded a "false"
				enlace.setAttribute('aria-expanded', 'false');

				// Cambia la clase del div de "collapse show" a "collapse"
				div.className = 'collapse';

				// Limpiar la lista de archivos
				archivos = [];
				var listaArchivos = document.querySelector('#listaArchivos');
				while (listaArchivos.firstChild) {
					listaArchivos.removeChild(listaArchivos.firstChild);
				}

				// Restablecer el texto del label
				var label = document.querySelector('#labelinputfile');
			});

			$('#newModal').on('hidden.bs.modal', function () {
				// Restablece el campo de entrada del archivo cuando se cierra el modal
				$('#codprodcrea').val('');
				$("#desprodcrea").val('');
				$("#arttipocrea").val('PRODUCTO LINEA AVITUM');
			});

			$('#editModal').on('hidden.bs.modal', function () {
				// Colapsa el tab
				var enlace = document.getElementById('tabarticuloedit');
				var div = document.getElementById('collapseOne');
				enlace.classList.add('collapsed');

				// Cambia la propiedad aria-expanded a "false"
				enlace.setAttribute('aria-expanded', 'false');

				// Cambia la clase del div de "collapse show" a "collapse"
				div.className = 'collapse';

				// Limpiar la lista de archivos
				archivosedit = [];
				var listaArchivosEdit = document.querySelector('#listaArchivosEdit');
				while (listaArchivosEdit.firstChild) {
					listaArchivosEdit.removeChild(listaArchivosEdit.firstChild);
				}

				// Restablecer el texto del label
				var label = document.querySelector('#labelinputfileEdit');
			});


			//Generación del avatar
			generarAvatar('<?php echo $_SESSION['nombres']; ?>', '<?php echo $_SESSION['apellidos']; ?>');

		});

		var archivos = [];

		function eliminarArchivo(nombre) {
			// Buscar el archivo en la lista de archivos
			var indice = archivos.findIndex(function (archivo) {
				return archivo.name === nombre;
			});

			// Si se encontró el archivo, eliminarlo de la lista
			if (indice !== -1) {
				archivos.splice(indice, 1);
			}

			// Eliminar el elemento de lista correspondiente
			var li = document.querySelector('#listaArchivos li[data-nombre="' + nombre + '"]');
			if (li) {
				li.parentNode.removeChild(li);
			}
		}

		function agregarArchivo() {
			// Obtener el archivo del input
			var archivo = document.querySelector('#inputFile').files[0];

			// Comprobar si el archivo está definido
			if (!archivo) {
				// Mostrar un mensaje de advertencia si falta información
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				Toast.fire({
					icon: 'warning',
					title: 'Por favor, selecciona un archivo.'
				})
				return;
			}

			// Añadir el archivo a la lista de archivos
			archivos.push(archivo);

			// Crear un nuevo elemento de lista para el archivo
			var li = document.createElement('li');

			// Añadir un botón para eliminar el archivo de la lista
			var boton = document.createElement('button');
			boton.type = 'button';
			//boton.textContent = 'Eliminar';
			boton.className = 'btn btn-xs btn-danger';  // Añadir la clase al botón
			boton.style.marginRight = '10px';  // Añadir un espacio de 10px entre el botón y el nombre
			boton.onclick = function () {
				eliminarArchivo(archivo.name);
			};

			// Crear un elemento i para el ícono de eliminación
			var icono = document.createElement('i');
			icono.className = 'far fa-trash-alt';
			boton.appendChild(icono);

			li.appendChild(boton);

			// Añadir el nombre del archivo al elemento de lista
			var texto = document.createTextNode(archivo.name);
			li.appendChild(texto);

			// Añadir un atributo de datos al elemento de lista para poder encontrarlo más tarde
			li.setAttribute('data-nombre', archivo.name);

			// Añadir el elemento de lista a la lista de archivos en el modal
			document.querySelector('#listaArchivos').appendChild(li);

			// Limpiar la casilla del input
			document.querySelector('#inputFile').value = null;
			// Cambiar el texto del label
			var label = document.querySelector('#labelinputfile');
		}

		var archivosedit = [];

		function eliminarArchivoEdit(nombre, ruta) {
			// Buscar el archivo en la lista de archivosedit
			var indice = archivosedit.findIndex(function (archivoedit) {
				return archivoedit.name === nombre;
			});

			// Si se encontró el archivo
			if (indice !== -1) {
				// Si el archivo fue cargado desde un dato, mostrar una alerta
				if (archivosedit[indice].cargadoDesdeDato) {
					alert('Este archivo fue cargado desde un dato y no puede ser eliminado.');
				} else {
					// Si el archivo no fue cargado desde un dato, eliminarlo de la lista
					archivosedit.splice(indice, 1);

					// Eliminar el elemento de lista correspondiente
					var li = document.querySelector('#listaArchivosEdit li[data-nombre="' + nombre + '"]');

					var rutadel = '../' + ruta;

					// Enviar los datos al archivo PHP para guardar la orden
					if (!archivosedit[indice].agregadoManualmente) {
						console.log("Eliminado del servidor, archivo: " + archivosedit[indice].name)
						$.post("scripts/eliminar_archivos.php", {
							rutadel: rutadel
						}, function (data) {
							// Manejar la respuesta del servidor (puede ser un mensaje de éxito o error)
							if (data === "success") {
								// Actualizar la tabla de órdenes (puedes recargar la tabla o hacerlo de otra manera)
								$('#registrosanitario').DataTable().ajax.reload();

								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 3000
								});

								Toast.fire({
									icon: 'success',
									title: 'Archivo eliminado con éxito.'
								})
							} else {
								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 3000
								});

								Toast.fire({
									icon: 'error',
									title: 'Error en la operación.'
								})
							}
						});
					}
					//----------

					if (li) {
						li.parentNode.removeChild(li);
					}
				}
			}
		}

		function agregarArchivoEdit(archivoedit) {
			// Si no se proporciona un archivo, obtenerlo del input y marcarlo como agregado manualmente
			if (!archivoedit) {
				archivoedit = document.querySelector('#inputFileEdit').files[0];
				if (archivoedit) {
					archivoedit.agregadoManualmente = true;
				}
			}

			// Comprobar si el archivoedit está definido
			if (!archivoedit) {
				// Mostrar un mensaje de advertencia si falta información
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				Toast.fire({
					icon: 'warning',
					title: 'Por favor, selecciona un archivo.'
				})
				return;
			}

			// Añadir el archivoedit a la lista de archivos
			archivosedit.push(archivoedit);

			// Crear un nuevo elemento de lista para el archivo
			var li = document.createElement('li');

			// Añadir un botón para eliminar el archivo de la lista
			var boton = document.createElement('button');
			boton.type = 'button';
			boton.className = 'btn btn-xs btn-danger';  // Añadir la clase al botón
			boton.style.marginRight = '10px';  // Añadir un espacio de 10px entre el botón y el nombre
			boton.onclick = function () {
				var rutadel = '';
				if (archivoedit.ruta) {
					eliminarArchivoEdit(archivoedit.name, archivoedit.ruta);
				} else {
					eliminarArchivoEdit(archivoedit.name, rutadel);
				}
			};

			// Crear un elemento i para el ícono de eliminación
			var icono = document.createElement('i');
			icono.className = 'far fa-trash-alt';
			boton.appendChild(icono);

			li.appendChild(boton);

			// Crear un enlace con el nombre del archivo solo si la ruta está definida
			if (archivoedit.ruta) {
				var enlace = document.createElement('a');
				enlace.href = archivoedit.ruta;  // Añadir la ruta del archivo al href del enlace
				enlace.target = '_blank';  // Hacer que el enlace se abra en una nueva pestaña
				enlace.textContent = archivoedit.name;  // Añadir el nombre del archivo al texto del enlace

				// Añadir el enlace al elemento de lista
				li.appendChild(enlace);
			} else {
				// Si la ruta no está definida, simplemente añadir el nombre del archivo como texto
				var texto = document.createTextNode(archivoedit.name);
				li.appendChild(texto);
			}

			// Añadir un atributo de datos al elemento de lista para poder encontrarlo más tarde
			li.setAttribute('data-nombre', archivoedit.name);

			// Añadir el elemento de lista a la lista de archivos en el modal
			document.querySelector('#listaArchivosEdit').appendChild(li);

			// Limpiar la casilla del input
			document.querySelector('#inputFileEdit').value = null;
			// Cambiar el texto del label
			var label = document.querySelector('#labelinputfileEdit');
		}

		//***********************************************************************************

		//***********************************************************************************
		// FUNCIÓN DE UPDATE MASIVO DE PRODUCTOS
		//***********************************************************************************

		function cargamasiva() {

			// Obtener el archivo del input
			var archivo = document.querySelector('#inputFileMasivo').files[0];

			// Comprobar si el archivo está definido
			if (!archivo) {
				// Mostrar un mensaje de advertencia si falta información
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				Toast.fire({
					icon: 'warning',
					title: 'Por favor, selecciona un archivo.'
				})
				return;
			}


			if (archivo && !validarFormatoArchivo(archivo.name)) {
				// Mostrar mensaje de advertencia si el formato no es válido
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				Toast.fire({
					icon: 'warning',
					title: 'Formato de archivo no permitido. Por favor, selecciona un archivo .xls o .xlsx.'
				});

				// Limpiar el input
				inputFile.value = null;
				return;
			}

			const inputFileMasivo = document.getElementById('inputFileMasivo');
			const excelTable = document.getElementById('excelTable');

			const file = inputFileMasivo.files[0];
			const reader = new FileReader();

			reader.onload = function (e) {
				const data = e.target.result;
				const workbook = XLSX.read(new Uint8Array(data), { type: 'array' });

				const sheetName = workbook.SheetNames[0];
				const worksheet = workbook.Sheets[sheetName];

				const excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

				// Limpiar la tabla antes de agregar nuevos datos
				while (excelTable.rows.length > 1) {
					excelTable.deleteRow(1);
				}

				// Filtramos las filas vacías
				let excelDataFiltered = excelData.filter(row => row.some(cell => cell !== null && cell !== ''));

				let headers = excelDataFiltered[0]; // Obtenemos los nombres de las columnas

				// Definimos los campos que corresponden a cada tabla
				let camposArticulos = ["ArtDescripcion", "ArtTipo", "ArtCodigoGMDN", "ArtCodigoUMDNS", "ArtProyectoRAAE", "ArtFabricante", "ArtOrigen", "ArtLugarFabricacion", "ArtFormaFarma", "ArtConcentracion", "ArtPresentacion", "ArtNivelRiesgo", "ArtMaterialPCB", "ArtComercializado", "ArtLabelAlemania", "ArtExoneracionCM", "ArtBarraUn", "ArtBarraCj", "ArtEAN13Un", "ArtEAN14Cj", "ArtGTINUn", "ArtGTINCj", "ArtProyecto", "ArtProveedor", "ArtProveedorDes"];
				let camposRegistroSanitario = ["RegNumero", "RegResolucion", "RegFechaEmision", "RegFechaAprobacion", "RegFechaVencimiento", "RegEstadoVencimiento", "RegObservaciones"];

				let promises = []; // Almacenamos las promesas de las consultas
				let failedArtCodes = []; // Almacenamos los códigos de los artículos cuyas consultas fallaron

				for (let i = 1; i < excelDataFiltered.length; i++) {
					let row = excelDataFiltered[i];
					let query_update1 = "UPDATE Sdt_Articulos SET ";
					let query_update2 = "UPDATE Sdt_RegistroSanitario SET ";

					for (let j = 1; j < row.length; j++) {
						let valor = row[j];

						// Verificamos si el encabezado contiene la palabra 'fecha'
						if (headers[j].toLowerCase().includes('fecha') && valor && !isNaN(valor)) {
							// Convertimos el número de Excel a una fecha de JavaScript
							let fechaJS = new Date((valor - (25567 + 2)) * 86400 * 1000);

							// Ajustamos la fecha a la medianoche UTC
							fechaJS.setUTCHours(0, 0, 0, 0);

							// Formateamos la fecha al formato esperado por SQL Server
							valor = fechaJS.getUTCFullYear() + '-' + (fechaJS.getUTCMonth() + 1).toString().padStart(2, '0') + '-' + fechaJS.getUTCDate().toString().padStart(2, '0');
						}

						if (valor) { // Solo agregamos el valor si no es nulo o indefinido
							if (camposArticulos.includes(headers[j])) {
								query_update1 += headers[j] + " = '" + valor + "'";
								if (j < row.length - 1) {
									query_update1 += ", ";
								}
							} else if (camposRegistroSanitario.includes(headers[j])) {
								query_update2 += headers[j] + " = '" + valor + "'";
								if (j < row.length - 1) {
									query_update2 += ", ";
								}
							}
						}
					}

					if (query_update1.endsWith(", ")) {
						query_update1 = query_update1.slice(0, -2);
					}
					if (query_update2.endsWith(", ")) {
						query_update2 = query_update2.slice(0, -2);
					}

					query_update1 += ", ArtUsuarioModificacion = '<?php echo $_SESSION['usuario']; ?>', ArtFechaModificacion = getdate() WHERE ArtComercializado='SI' AND ArtCodigo = '" + row[0] + "'";
					query_update2 += ", RegUsuarioModificacion = '<?php echo $_SESSION['usuario']; ?>', RegFechaModificacion = getdate() WHERE ArtID = (SELECT ArtID FROM Sdt_Articulos WHERE ArtComercializado='SI' AND ArtCodigo = '" + row[0] + "')";

					// Verificamos si las consultas están vacías
					if (query_update1 === "UPDATE Sdt_Articulos SET , ArtUsuarioModificacion = '<?php echo $_SESSION['usuario']; ?>', ArtFechaModificacion = getdate() WHERE ArtComercializado='SI' AND ArtCodigo = '" + row[0] + "'") {
						query_update1 = "";
					}
					if (query_update2 === "UPDATE Sdt_RegistroSanitario SET , RegUsuarioModificacion = '<?php echo $_SESSION['usuario']; ?>', RegFechaModificacion = getdate() WHERE ArtID = (SELECT ArtID FROM Sdt_Articulos WHERE ArtComercializado='SI' AND ArtCodigo = '" + row[0] + "'") {
						query_update2 = "";
					}

					// Enviar los datos al archivo PHP para guardar la orden
					if (query_update1 !== "") {
						promises.push($.post("scripts/guardar_edit_masivo.php", { query_update1: query_update1 }));
					}
					if (query_update2 !== "") {
						promises.push($.post("scripts/guardar_edit_masivo.php", { query_update2: query_update2 }));
					}
				}

				// Esperamos a que todas las promesas se resuelvan
				Promise.all(promises).then(function (responses) {
					// Verificamos si todas las consultas fueron exitosas
					if (responses.every(response => response === "success")) {
						// Mostramos el toast de éxito
						var Toast = Swal.mixin({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 5000 // Aumentamos el tiempo de visualización del toast
						});

						Toast.fire({
							icon: 'success',
							title: 'Todas las consultas se ejecutaron correctamente.'
						});
					} else {
						// Mostramos el toast de error
						var Toast = Swal.mixin({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 5000 // Aumentamos el tiempo de visualización del toast
						});

						Toast.fire({
							icon: 'error',
							title: 'Las consultas para los siguientes códigos de artículos no se ejecutaron correctamente: ' + failedArtCodes.join(', ')
						});
					}
				});
			};

			// Utilizar readAsArrayBuffer en lugar de readAsBinaryString
			reader.readAsArrayBuffer(file);

			document.getElementById("modalproductomasivocierra").click();

			// Actualizar la tabla de órdenes (puedes recargar la tabla o hacerlo de otra manera)
			$('#registrosanitario').DataTable().ajax.reload();
		}

		//***********************************************************************************			

		//***********************************************************************************
		// SECCIÓN PARA EL CONTROL DEL INPUT DE CARGA MASIVA DE PRODUCTOS EN EL MODAL
		//***********************************************************************************

		var archivosmasivo = [];

		$('#modalproductomasivo').on('hidden.bs.modal', function () {

			// Limpiar la lista de archivos
			archivosmasivo = [];
			var listaArchivosMasivo = document.querySelector('#listaArchivosMasivo');
			while (listaArchivosMasivo.firstChild) {
				listaArchivosMasivo.removeChild(listaArchivosMasivo.firstChild);
			}

			// Restablecer el texto del label
			var label = document.querySelector('#labelinputfileMasivo');

			// Limpiar la casilla del input
			document.querySelector('#inputFileMasivo').value = null;
		});

		function validarFormatoArchivo(nombre) {
			var extensionesPermitidas = ['.xls', '.xlsx'];
			var extension = nombre.substring(nombre.lastIndexOf('.')).toLowerCase();
			return extensionesPermitidas.includes(extension);
		}

		function convertirFecha(fecha) {
			var partes = fecha.split("/");
			if (partes[2] === '1970') {
				return '';
			}
			if (partes[2] === '1900') {
				return '';
			} else {
				return partes[2] + "-" + partes[1] + "-" + partes[0];
			}
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

		function guardar() {

			// Obtener los datos del formulario
			var modalIDprev = $("#modalID").val();
			var modalID = modalIDprev.split('_')[1];
			var ArtID = $("#ArtID").val();
			var codigo = $("#codigo").val();
			var descripcion = $("#descripcion").val();
			var rs = $("#rs").val();
			var resolucion = $("#resolucion").val();
			var emision = $("#emision").val();
			var aprobacion = $("#aprobacion").val();
			var vencimiento = $("#vencimiento").val();
			var observaciones = $("#observaciones").val();
			var estadors = $("#estadors").val();
			var usuariomod = '<?php echo $_SESSION['usuario']; ?>';

			var arttipo = $("#arttipo").val();
			var origen = $("#origen").val();
			var fabricante = $("#fabricante").val();
			var lugarfab = $("#lugarfab").val();
			var formafarma = $("#formafarma").val();
			var concentracion = $("#concentracion").val();
			var presentacion = $("#presentacion").val();
			var nivelriesgo = $("#nivelriesgo").val();
			var materialpcb = $("#materialpcb").val();
			var comercializado = $("#comercializado").val();
			var labelalemania = $("#labelalemania").val();
			var exoneracioncm = $("#exoneracioncm").val();
			var proyectoart = $("#proyectoart").val();
			var barraun = $("#barraun").val();
			var barracj = $("#barracj").val();
			var ean13un = $("#ean13un").val();
			var ean14cj = $("#ean14cj").val();
			var gtinun = $("#gtinun").val();
			var gtincj = $("#gtincj").val();
			var gmdn = $("#gmdn").val();
			var umdns = $("#umdns").val();
			var proyecto = $("#proyecto").val();
			var proveedor = $("#proveedor").val();
			var proveedordes = $("#proveedordes").val();

			// Crear un objeto FormData para contener los datos del formulario y los archivos
			var formData = new FormData();
			formData.append('modalID', modalID);
			formData.append('ArtID', ArtID);
			formData.append('codigo', codigo);
			formData.append('descripcion', descripcion);
			formData.append('rs', rs);
			formData.append('resolucion', resolucion);
			formData.append('emision', emision);
			formData.append('aprobacion', aprobacion);
			formData.append('vencimiento', vencimiento);
			formData.append('observaciones', observaciones);
			formData.append('estadors', estadors);
			formData.append('usuariomod', usuariomod);

			formData.append('arttipo', arttipo);
			formData.append('origen', origen);
			formData.append('fabricante', fabricante);
			formData.append('lugarfab', lugarfab);
			formData.append('formafarma', formafarma);
			formData.append('concentracion', concentracion);
			formData.append('presentacion', presentacion);
			formData.append('nivelriesgo', nivelriesgo);
			formData.append('materialpcb', materialpcb);
			formData.append('comercializado', comercializado);
			formData.append('labelalemania', labelalemania);
			formData.append('exoneracioncm', exoneracioncm);
			formData.append('proyectoart', proyectoart);
			formData.append('barraun', barraun);
			formData.append('barracj', barracj);
			formData.append('ean13un', ean13un);
			formData.append('ean14cj', ean14cj);
			formData.append('gtinun', gtinun);
			formData.append('gtincj', gtincj);
			formData.append('gmdn', gmdn);
			formData.append('umdns', umdns);
			formData.append('proyecto', proyecto);
			formData.append('proveedor', proveedor);
			formData.append('proveedordes', proveedordes);

			// Añadir cada archivo de la lista archivosedit al objeto FormData
			archivosedit.forEach(function (archivo) {
				// Comprobar si el archivo tiene una propiedad 'ruta'
				if (!archivo.ruta) {
					// Si el archivo no tiene una propiedad 'ruta', entonces es un archivo real y se puede añadir a FormData
					formData.append('archivos[]', archivo, archivo.name);
				}
			});

			// Verificar que los campos obligatorios no estén vacíos
			if (
				resolucion.trim() === "" ||
				rs.trim() === ""  /* ||
					emision.trim() === "" ||
					aprobacion.trim() === "" ||
					vencimiento.trim() === "" */
			) {
				// Mostrar un mensaje de advertencia si falta información
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				Toast.fire({
					icon: 'warning',
					title: 'Es necesario completar toda la información.'
				})
			} else {

				// Enviar los datos al archivo PHP para guardar la orden
				$.ajax({
					url: "scripts/guardar_edit.php",
					type: "POST",
					data: formData,
					processData: false,  // Indicar a jQuery que no procese los datos
					contentType: false,  // Indicar a jQuery que no establezca el tipo de contenido
					success: function (data) {
						// Manejar la respuesta del servidor (puede ser un mensaje de éxito o error)
						if (data === "success") {
							// Cerrar el modal
							//$("#editModal").hide();
							document.getElementById("modeditcierra").click();
							// Limpiar la lista de archivos
							archivosedit = [];
							var listaArchivosEdit = document.querySelector('#listaArchivosEdit');
							while (listaArchivosEdit.firstChild) {
								listaArchivosEdit.removeChild(listaArchivosEdit.firstChild);
							}
							// Actualizar la tabla de órdenes (puedes recargar la tabla o hacerlo de otra manera)
							$('#registrosanitario').DataTable().ajax.reload();

							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 3000
							});

							Toast.fire({
								icon: 'success',
								title: 'Registro actualizado con éxito.'
							})
						} else {
							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 3000
							});

							Toast.fire({
								icon: 'error',
								title: 'Error en la operación.'
							})
						}
					}
				});
			}
		}

		function newguardar() {

			// Obtener los datos del formulario
			var newArtID = $("#newArtID").val();
			var newcodigo = $("#newcodigo").val();
			var newrs = $("#newrs").val();
			var newresolucion = $("#newresolucion").val();
			var newemision = $("#newemision").val();
			var newaprobacion = $("#newaprobacion").val();
			var newvencimiento = $("#newvencimiento").val();
			var newobservaciones = $("#newobservaciones").val();
			var newestadors = $("#newestadors").val();
			var newucreacion = '<?php echo $_SESSION['usuario']; ?>';

			// Crear un objeto FormData para almacenar los archivos y los datos del formulario
			var formData = new FormData();
			formData.append('newArtID', newArtID);
			formData.append('newcodigo', newcodigo);
			formData.append('newrs', newrs);
			formData.append('newresolucion', newresolucion);
			formData.append('newemision', newemision);
			formData.append('newaprobacion', newaprobacion);
			formData.append('newvencimiento', newvencimiento);
			formData.append('newobservaciones', newobservaciones);
			formData.append('newestadors', newestadors);
			formData.append('newucreacion', newucreacion);

			// Añadir cada archivo al objeto FormData
			for (var i = 0; i < archivos.length; i++) {
				var archivo = archivos[i];
				formData.append('archivos[]', archivo, archivo.name);
			}

			// Verificar que los campos obligatorios no estén vacíos
			if (
				newresolucion.trim() === "" ||
				newrs.trim() === "" ||
				newcodigo.trim() === "" /* ||
					newemision.trim() === "" ||
					newaprobacion.trim() === "" ||
					newvencimiento.trim() === "" */
			) {
				// Mostrar un mensaje de advertencia si falta información
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				Toast.fire({
					icon: 'warning',
					title: 'Es necesario completar toda la información.'
				})
			} else {
				// Enviar los datos al archivo PHP para guardar la orden
				$.ajax({
					url: "scripts/guardar_nuevo.php",
					type: "POST",
					data: formData,
					processData: false,  // Indicar a jQuery que no procese los datos
					contentType: false,  // Indicar a jQuery que no establezca el tipo de contenido
					success: function (data) {
						// Manejar la respuesta del servidor (puede ser un mensaje de éxito o error)
						if (data === "success") {
							// Cerrar el modal
							//$("#editModal").hide();
							document.getElementById("modnewcierra").click();
							// Limpiar la lista de archivos
							archivos = [];
							var listaArchivos = document.querySelector('#listaArchivos');
							while (listaArchivos.firstChild) {
								listaArchivos.removeChild(listaArchivos.firstChild);
							}
							// Actualizar la tabla de órdenes (puedes recargar la tabla o hacerlo de otra manera)
							$('#registrosanitario').DataTable().ajax.reload();

							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 3000
							});

							Toast.fire({
								icon: 'success',
								title: 'Registro creado con éxito.'
							})
						} else {
							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 3000
							});

							Toast.fire({
								icon: 'error',
								title: 'Error en la operación.'
							})
						}
					}
				});
			}
		}

		function cargacrea() {

			// Obtener los datos del formulario
			var newcodigo = $("#codprodcrea").val();
			var newdescripcion = $("#desprodcrea").val();
			var newtipo = $("#arttipocrea").val();
			var newucreacion = '<?php echo $_SESSION['usuario']; ?>';

			// Crear un objeto FormData para almacenar los archivos y los datos del formulario
			var formData = new FormData();
			formData.append('newcodigo', newcodigo);
			formData.append('newdescripcion', newdescripcion);
			formData.append('newtipo', newtipo);
			formData.append('newucreacion', newucreacion);

			// Verificar que los campos obligatorios no estén vacíos
			if (
				newcodigo.trim() === "" ||
				newdescripcion.trim() === ""
			) {
				// Mostrar un mensaje de advertencia si falta información
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				Toast.fire({
					icon: 'warning',
					title: 'Es necesario completar toda la información.'
				})
			} else {
				// Enviar los datos al archivo PHP para guardar la orden
				$.ajax({
					url: "scripts/guardar_nuevo_prod.php",
					type: "POST",
					data: formData,
					processData: false,  // Indicar a jQuery que no procese los datos
					contentType: false,  // Indicar a jQuery que no establezca el tipo de contenido
					success: function (data) {
						// Manejar la respuesta del servidor (puede ser un mensaje de éxito o error)
						if (data === "success") {
							// Cerrar el modal
							//$("#editModal").hide();
							document.getElementById("modalproductocreacierra").click();

							// Actualizar la tabla de órdenes (puedes recargar la tabla o hacerlo de otra manera)
							$('#registrosanitario').DataTable().ajax.reload();
							$('#dtnewproducto').DataTable().ajax.reload();

							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 3000
							});

							Toast.fire({
								icon: 'success',
								title: 'Producto creado con éxito.'
							})
						} else {
							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 3000
							});

							Toast.fire({
								icon: 'error',
								title: 'Error en la operación.'
							})
						}
					}
				});
			}
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