# FN22. Reporte de crecimiento de clientes

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN22 – Reporte de Crecimiento de Clientes                |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 03:10 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema genera correctamente el reporte de crecimiento de clientes, mostrando la evolución mensual de altas, bajas y total acumulado de clientes por año, con KPIs de crecimiento, insights de mejor y peor mes, gráfica de línea y opción de exportación a PNG.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen clientes con historial de altas, bajas y reactivaciones registradas en la bitácora de clientes de la BD de pruebas (ver Tablas 3 y 21).

**Entradas:**
Filtro por año: 2024. Segundo filtro: 2026.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /admin/reportes/crecimiento-clientes. | 1. Se muestra el reporte del año actual con las 4 tarjetas de KPIs: Clientes al Inicio del año, Clientes al Final del año, Crecimiento Neto (con porcentaje) y Movimientos (altas/bajas). Se visualizan los insights de Mejor Mes de Captación y Mayor Pérdida de Clientes. Se muestra la tabla de evolución mensual. | 1. El sistema le permite al usuario administrador consultar el reporte de crecimiento de clientes por año, visualizar KPIs de evolución, insights de captación y pérdida, tabla mensual con desglose de altas, bajas y reactivaciones, y exportar el reporte a PNG con gráfica de línea. |
| **VERIFICAR KPIs** | | |
| 2. Verificar que los KPIs muestran correctamente: clientes activos al inicio del año, clientes al final, crecimiento neto (positivo en verde, negativo en rojo) con porcentaje, y el total de altas y bajas del año. | 2. Los KPIs reflejan los datos calculados a partir de la bitácora de clientes. El crecimiento neto es la diferencia entre clientes al final e inicio. El porcentaje se calcula como (neto / inicio × 100). | |
| **VERIFICAR INSIGHTS** | | |
| 3. Verificar que se muestran las tarjetas de "Mejor Mes de Captación" y "Mayor Pérdida de Clientes". | 3. El mejor mes muestra el mes con más altas (nuevos + reactivaciones). La mayor pérdida muestra el mes con más bajas. | |
| **VERIFICAR TABLA MENSUAL** | | |
| 4. Verificar que la tabla "Evolución Mensual" muestra los 12 meses con las columnas: Mes, Altas (con desglose N:nuevos / R:reactivaciones si aplica), Bajas, Neto y Total Acumulado. | 4. Cada fila muestra el mes con sus altas (badge verde), bajas (badge rojo), neto (verde si positivo, rojo si negativo) y total acumulado (indigo). El total acumulado es consistente mes a mes. | |
| **FILTRAR POR AÑO** | | |
| 5. Seleccionar año "2024" en el filtro. Clic en "Filtrar". | 5. El reporte se actualiza mostrando los datos de 2024. Los KPIs, insights y tabla mensual reflejan los movimientos de ese año. Se muestra el historial de baja y reactivación del cliente "LOS CALLOS DE CORTES" en los meses correspondientes. | |
| **CAMBIAR AÑO** | | |
| 6. Seleccionar año "2026" en el filtro. Clic en "Filtrar". | 6. El reporte se actualiza mostrando los datos de 2026. Los KPIs y la tabla reflejan únicamente los movimientos registrados hasta la fecha actual. | |
| **EXPORTAR A PNG** | | |
| 7. Clic en "Exportar PNG". | 7. Se genera y descarga automáticamente una imagen PNG con el nombre "reporte-crecimiento-clientes-[año]-[fecha].png" conteniendo los KPIs, la gráfica de línea (Total Clientes, Altas, Bajas por mes), los insights y la tabla mensual detallada. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
