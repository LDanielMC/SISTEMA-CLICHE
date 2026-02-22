# FN21. Reporte de acuerdos por cliente

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN21 – Reporte de Acuerdos por Cliente                    |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 03:07 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema genera correctamente el reporte de acuerdos por cliente, mostrando KPIs de cumplimiento, tabla resumen por cliente, detalle de acuerdos pendientes al filtrar por cliente, gráfica de distribución y opción de exportación a PNG.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen clientes activos con minutas y acuerdos registrados en la BD de pruebas (ver Tablas 3, 13 y 14).

**Entradas:**
Filtro por cliente: "LOS CALLOS DE CORTES".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /admin/reportes/acuerdos-cliente. | 1. Se muestra el reporte con las 4 tarjetas de KPIs: Total Acuerdos, Concluidos, Pendientes y % Cumplimiento (generales). Se visualiza la tabla "Resumen por Cliente" con columnas: Cliente, Total, Concluidos, Pendientes y % Cumplimiento con barra de progreso. | 1. El sistema le permite al usuario administrador consultar el reporte de acuerdos por cliente, visualizar KPIs de cumplimiento, filtrar por cliente, consultar los acuerdos pendientes en detalle con gráfica de distribución, y exportar el reporte a PNG. |
| **VERIFICAR KPIs GENERALES** | | |
| 2. Verificar que los KPIs muestran los totales correctos de todos los clientes: total de acuerdos, concluidos, pendientes y porcentaje de cumplimiento global. | 2. Los KPIs reflejan la suma de acuerdos de todos los clientes con minutas. El porcentaje de cumplimiento se calcula como (concluidos / total × 100). | |
| **VERIFICAR TABLA RESUMEN** | | |
| 3. Verificar que la tabla muestra los clientes con acuerdos, con su empresa, contacto, conteos de acuerdos y porcentaje de cumplimiento con barra de progreso visual. | 3. Cada fila muestra el nombre de la empresa, el contacto, el total de acuerdos, concluidos (badge verde), pendientes (badge amarillo) y el porcentaje con barra. | |
| **FILTRAR POR CLIENTE** | | |
| 4. Seleccionar cliente "LOS CALLOS DE CORTES" en el filtro. Clic en "Filtrar". | 4. Los KPIs se actualizan mostrando únicamente los datos del cliente seleccionado. La tabla resumen muestra solo la fila del cliente filtrado. | |
| **VERIFICAR ACUERDOS PENDIENTES** | | |
| 5. Verificar que se muestra la sección "Acuerdos Pendientes" del cliente seleccionado con las columnas: Acuerdo, Responsable y Fecha Límite. | 5. Se listan los acuerdos con estatus "pendiente" del cliente. Las fechas límite vencidas se resaltan en rojo. | |
| **LIMPIAR FILTRO** | | |
| 6. Clic en "Limpiar" para remover el filtro de cliente. | 6. El reporte regresa a la vista general mostrando todos los clientes con acuerdos y los KPIs globales. | |
| **EXPORTAR A PNG** | | |
| 7. Seleccionar nuevamente "LOS CALLOS DE CORTES" y clic en "Filtrar". Clic en "Exportar PNG". | 7. Se genera y descarga automáticamente una imagen PNG con el nombre "reporte-acuerdos-[cliente]-[fecha].png" conteniendo los KPIs, la gráfica de dona (Concluidos vs Pendientes), el resumen ejecutivo del cliente y la tabla de acuerdos pendientes. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
