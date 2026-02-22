# FN18. Reporte de efectividad e ingresos por cotizaciones

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN18 – Reporte de Efectividad e Ingresos por Cotizaciones |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 02:51 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema genera correctamente el reporte de efectividad e ingresos por cotizaciones, mostrando métricas de conversión y montos por cliente, con filtros por rango de fechas y cliente, fila de totales y opción de exportación a Excel.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen clientes activos y cotizaciones con diferentes estatus en la BD de pruebas (ver Tablas 3, 5 y 6).

**Entradas:**
Filtro inicial: Fecha inicio: 2024-01-01, Fecha fin: 2026-02-12. Filtro por cliente: "LOS CALLOS DE CORTES".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /admin/reportes/efectividad. | 1. Se muestra el reporte con el rango predeterminado (mes actual). Se visualiza la tabla con columnas: Cliente, Cotizaciones Emitidas, Cotizaciones Aceptadas, Efectividad (%), Monto Emitido y Monto Aceptado. Se muestra el filtro de cliente y el botón "Exportar Excel". | 1. El sistema le permite al usuario administrador generar y consultar el reporte de efectividad e ingresos por cotizaciones, filtrar por rango de fechas y por cliente, visualizar métricas de conversión y montos, y exportar el reporte a Excel. |
| **FILTRAR POR RANGO DE FECHAS** | | |
| 2. Ingresar fecha inicio "2024-01-01" y fecha fin "2026-02-12". Clic en "Filtrar". | 2. El reporte se actualiza mostrando los clientes con cotizaciones en el rango seleccionado. Por cada cliente se muestran las cotizaciones emitidas, aceptadas, el porcentaje de efectividad (aceptadas/emitidas × 100), el monto total emitido y el monto aceptado. | |
| **VERIFICAR MÉTRICAS DE EFECTIVIDAD** | | |
| 3. Verificar que el porcentaje de efectividad de cada cliente se calcula correctamente y se muestra con indicador de color: verde (≥50%), amarillo (>0%) o gris (0%). | 3. Los porcentajes se muestran con su indicador de color correspondiente y barra de progreso visual. Los montos se formatean en formato de moneda. | |
| **VERIFICAR FILA DE TOTALES** | | |
| 4. Verificar que la fila "Totales" al final de la tabla muestra la suma correcta de cotizaciones emitidas, aceptadas, efectividad global y montos. | 4. La fila de totales muestra los acumulados correctos: total de emitidas, total de aceptadas, efectividad global (total aceptadas / total emitidas × 100), monto total emitido y monto total aceptado. | |
| **FILTRAR POR CLIENTE** | | |
| 5. Seleccionar el cliente "LOS CALLOS DE CORTES" en el filtro de cliente. Clic en "Filtrar". | 5. El reporte se actualiza mostrando únicamente las cotizaciones del cliente seleccionado. Las métricas reflejan solo los datos de ese cliente. | |
| 6. Verificar que los datos corresponden exclusivamente al cliente "LOS CALLOS DE CORTES". | 6. Solo se muestra una fila con los datos de cotizaciones emitidas, aceptadas, efectividad y montos del cliente filtrado. | |
| **EXPORTAR A EXCEL** | | |
| 7. Clic en "Exportar Excel". | 7. Se genera y descarga automáticamente un archivo Excel con el nombre "reporte-efectividad-[fecha].xls" conteniendo los datos del reporte con el filtro aplicado. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
