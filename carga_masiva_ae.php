<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

// Verifica si el usuario tiene permisos de EDITOR
if ($_SESSION['nivel'] !== 'EDITOR') {
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carga Masiva - Dirección Técnica</title>
    <link rel="icon" href="dist/img/favicon.ico">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap 5.3.2 -->
    <link rel="stylesheet" type="text/css" href="plugins/btp/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" type="text/css" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="home.php" class="nav-link">Inicio</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="cnx/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-1">
            <a href="home.php" class="brand-link elevation-3">
                <img src="dist/img/bbraun.png" alt="B Logo" class="brand-image" width="60%" height="auto"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">Dirección Técnica</span>
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
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
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
                            <a href="carga_masiva_ae.php" class="nav-link active">
                                <i class="nav-icon fas fa-file-excel"></i>
                                <p>Carga Masiva AE</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rs_ae.php?arttipo=PRODUCTO AE" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Registros Sanitarios AE</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Carga Masiva de Artículos y Registros Sanitarios AE</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Cargar archivo Excel</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <h5><i class="icon fas fa-info"></i> Instrucciones:</h5>
                                        <p>El archivo Excel debe tener las siguientes columnas en este orden:</p>
                                        <ol>
                                            <li><strong>ArtCodigo_AE</strong> - Código del artículo</li>
                                            <li><strong>ArtDescripcion_AE</strong> - Descripción del artículo</li>
                                            <li><strong>ArtFabricante_AE</strong> - Fabricante</li>
                                            <li><strong>ArtLugarFabricacion_AE</strong> - Lugar de fabricación</li>
                                            <li><strong>ArtPaisOrigen_AE</strong> - País de origen</li>
                                            <li><strong>NivelRiesgoPeru_AE</strong> - Nivel de riesgo</li>
                                            <li><strong>Codigo_GMDN_UMDNS</strong> - Código GMDN</li>
                                            <li><strong>EsEsteril_AE</strong> - Es estéril (E/NE/NO APLICA)</li>
                                            <li><strong>NumeroIFU_AE</strong> - Número IFU</li>
                                            <li><strong>CodigoEAN_13</strong> - Código EAN 13</li>
                                            <li><strong>CodigoEAN_14</strong> - Código EAN 14</li>
                                            <li><strong>CodigoGTIN</strong> - Código GTIN</li>
                                            <li><strong>Cambios_AE</strong> - Cambios AE</li>
                                            <li><strong>ExoneracionCM</strong> - Exoneración CM</li>
                                            <li><strong>ProblemaDimensiones_AE</strong> - Problema dimensiones</li>
                                            <li><strong>RegNumero_AE</strong> - Número de Registro Sanitario</li>
                                            <li><strong>RegResolucion_AE</strong> - Resolución</li>
                                            <li><strong>RegFechaEmision_AE</strong> - Fecha emisión (DD/MM/YYYY)</li>
                                            <li><strong>RegFechaAprobacion_AE</strong> - Fecha aprobación (DD/MM/YYYY)
                                            </li>
                                            <li><strong>RegFechaVencimiento_AE</strong> - Fecha vencimiento (DD/MM/YYYY)
                                            </li>
                                            <li><strong>RegEstado_AE</strong> - Estado</li>
                                            <li><strong>Etiqueta_AE</strong> - Etiqueta</li>
                                            <li><strong>RegObservacion_AE</strong> - Observaciones</li>
                                        </ol>
                                        <p class="mb-0"><i class="fas fa-download"></i> <a
                                                href="scripts/descargar_plantilla_carga_masiva.php">Descargar plantilla
                                                Excel</a></p>
                                    </div>

                                    <form id="formCargaMasiva" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="archivoExcel">Seleccionar archivo Excel:</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="archivoExcel"
                                                        name="archivoExcel" accept=".xlsx,.xls">
                                                    <label class="custom-file-label" for="archivoExcel">Elegir
                                                        archivo...</label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="procesarArchivo()">
                                            <i class="fas fa-upload"></i> Procesar Carga Masiva
                                        </button>
                                    </form>

                                    <div id="resultadoCarga" class="mt-4" style="display: none;">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Resultado de la Carga</h3>
                                            </div>
                                            <div class="card-body">
                                                <div id="resumenCarga"></div>
                                                <div id="detalleErrores"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            <strong>B. Braun Medical Perú.</strong> All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="plugins/dt/js/jquery-3.7.0.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap 5.3.2 -->
    <script src="plugins/btp/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- Excel -->
    <script src="plugins/excel/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function () {
            bsCustomFileInput.init();
            generarAvatar('<?php echo $_SESSION['nombres']; ?>', '<?php echo $_SESSION['apellidos']; ?>');
        });

        function procesarArchivo() {
            var archivoInput = document.getElementById('archivoExcel');
            var archivo = archivoInput.files[0];

            if (!archivo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivo requerido',
                    text: 'Por favor seleccione un archivo Excel.'
                });
                return;
            }

            // Mostrar loading
            Swal.fire({
                title: 'Procesando...',
                html: 'Por favor espere mientras se procesa el archivo.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            var reader = new FileReader();
            reader.onload = function (e) {
                try {
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, { type: 'array' });
                    var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    var jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                    // Validar que tenga datos
                    if (jsonData.length < 2) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'El archivo no contiene datos para procesar.'
                        });
                        return;
                    }

                    // Preparar datos para enviar
                    var formData = new FormData();
                    formData.append('datosExcel', JSON.stringify(jsonData));
                    formData.append('usuario', '<?php echo $_SESSION['usuario']; ?>');

                    // Enviar al servidor
                    $.ajax({
                        url: 'scripts/procesar_carga_masiva_ae.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.close();
                            mostrarResultado(response);
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error en el servidor',
                                text: 'Ocurrió un error al procesar el archivo: ' + error
                            });
                        }
                    });

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al leer el archivo',
                        text: 'No se pudo leer el archivo Excel: ' + error.message
                    });
                }
            };

            reader.readAsArrayBuffer(archivo);
        }

        function mostrarResultado(response) {
            try {
                var resultado = JSON.parse(response);

                $('#resultadoCarga').show();

                var html = '<div class="row">';
                html += '<div class="col-md-3"><div class="info-box bg-success"><span class="info-box-icon"><i class="fas fa-check"></i></span><div class="info-box-content"><span class="info-box-text">Insertados</span><span class="info-box-number">' + resultado.exitosos + '</span></div></div></div>';
                html += '<div class="col-md-3"><div class="info-box bg-danger"><span class="info-box-icon"><i class="fas fa-times"></i></span><div class="info-box-content"><span class="info-box-text">Errores</span><span class="info-box-number">' + resultado.errores + '</span></div></div></div>';
                html += '<div class="col-md-3"><div class="info-box bg-info"><span class="info-box-icon"><i class="fas fa-info"></i></span><div class="info-box-content"><span class="info-box-text">Total Procesados</span><span class="info-box-number">' + resultado.total + '</span></div></div></div>';
                html += '</div>';

                $('#resumenCarga').html(html);

                if (resultado.detalleErrores && resultado.detalleErrores.length > 0) {
                    var errorHtml = '<div class="alert alert-danger"><h5><i class="icon fas fa-ban"></i> Detalle de Errores:</h5><ul>';
                    resultado.detalleErrores.forEach(function (error) {
                        errorHtml += '<li>' + error + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $('#detalleErrores').html(errorHtml);
                } else {
                    $('#detalleErrores').html('');
                }

                if (resultado.exitosos > 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Carga completada',
                        text: 'Se procesaron ' + resultado.exitosos + ' registros correctamente.'
                    });
                }

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la respuesta del servidor.'
                });
            }
        }

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