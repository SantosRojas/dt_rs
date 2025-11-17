<?php
// Establecer encabezados para descarga de archivo Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Plantilla_Carga_Masiva_AE.xls"');
header('Cache-Control: max-age=0');

// Crear contenido HTML que Excel puede interpretar
echo '<?xml version="1.0"?>';
echo '<?mso-application progid="Excel.Sheet"?>';
?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:html="http://www.w3.org/TR/REC-html40">
    <Styles>
        <Style ss:ID="Header">
            <Font ss:Bold="1" ss:Color="#FFFFFF" /><Interior ss:Color="#4472C4" ss:Pattern="Solid" /><Alignment ss:Horizontal="Center" ss:Vertical="Center" />
        </Style>
    </Styles>
    <Worksheet ss:Name="Plantilla">
        <Table>
            <Row ss:StyleID="Header">
                <Cell><Data ss:Type="String">ArtCodigo_AE</Data></Cell>
                <Cell><Data ss:Type="String">ArtDescripcion_AE</Data></Cell>
                <Cell><Data ss:Type="String">ArtFabricante_AE</Data></Cell>
                <Cell><Data ss:Type="String">ArtLugarFabricacion_AE</Data></Cell>
                <Cell><Data ss:Type="String">ArtPaisOrigen_AE</Data></Cell>
                <Cell><Data ss:Type="String">NivelRiesgoPeru_AE</Data></Cell>
                <Cell><Data ss:Type="String">Codigo_GMDN_UMDNS</Data></Cell>
                <Cell><Data ss:Type="String">EsEsteril_AE</Data></Cell>
                <Cell><Data ss:Type="String">NumeroIFU_AE</Data></Cell>
                <Cell><Data ss:Type="String">CodigoEAN_13</Data></Cell>
                <Cell><Data ss:Type="String">CodigoEAN_14</Data></Cell>
                <Cell><Data ss:Type="String">CodigoGTIN</Data></Cell>
                <Cell><Data ss:Type="String">Cambios_AE</Data></Cell>
                <Cell><Data ss:Type="String">ExoneracionCM_AE</Data></Cell>
                <Cell><Data ss:Type="String">ProblemaDimensiones_AE</Data></Cell>
                <Cell><Data ss:Type="String">RegNumero_AE</Data></Cell>
                <Cell><Data ss:Type="String">RegResolucion_AE</Data></Cell>
                <Cell><Data ss:Type="String">RegFechaEmision_AE</Data></Cell>
                <Cell><Data ss:Type="String">RegFechaAprobacion_AE</Data></Cell>
                <Cell><Data ss:Type="String">RegFechaVencimiento_AE</Data></Cell>
                <Cell><Data ss:Type="String">RegEstado_AE</Data></Cell>
                <Cell><Data ss:Type="String">Etiqueta_AE</Data></Cell>
                <Cell><Data ss:Type="String">RegObservacion_AE</Data></Cell>
            </Row>
            <Row>
                <Cell><Data ss:Type="String">EJEMPLO001</Data></Cell>
                <Cell><Data ss:Type="String">Producto de ejemplo para demostración</Data></Cell>
                <Cell><Data ss:Type="String">AESCULAP AG</Data></Cell>
                <Cell><Data ss:Type="String">Alemania</Data></Cell>
                <Cell><Data ss:Type="String">ALEMANIA</Data></Cell>
                <Cell><Data ss:Type="String">III</Data></Cell>
                <Cell><Data ss:Type="String">12345</Data></Cell>
                <Cell><Data ss:Type="String">E</Data></Cell>
                <Cell><Data ss:Type="String">IFU-001</Data></Cell>
                <Cell><Data ss:Type="String">1234567890123</Data></Cell>
                <Cell><Data ss:Type="String">12345678901234</Data></Cell>
                <Cell><Data ss:Type="String">12345678901234</Data></Cell>
                <Cell><Data ss:Type="String">Cambio de diseño</Data></Cell>
                <Cell><Data ss:Type="String">SI</Data></Cell>
                <Cell><Data ss:Type="String">NO</Data></Cell>
                <Cell><Data ss:Type="String">RS-12345-2024</Data></Cell>
                <Cell><Data ss:Type="String">R.D. 001-2024</Data></Cell>
                <Cell><Data ss:Type="String">01/01/2024</Data></Cell>
                <Cell><Data ss:Type="String">15/01/2024</Data></Cell>
                <Cell><Data ss:Type="String">01/01/2029</Data></Cell>
                <Cell><Data ss:Type="String">VIGENTE</Data></Cell>
                <Cell><Data ss:Type="String">Importado por B. Braun Medical Perú S.A.</Data></Cell>
                <Cell><Data ss:Type="String">Observaciones generales</Data></Cell>
            </Row>
        </Table>
    </Worksheet>
</Workbook>