# FN20. Reporte de carga de trabajo por empleado y cliente

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN20 – Reporte de Carga de Trabajo por Empleado y Cliente |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 03:02 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema genera correctamente el reporte de carga de trabajo, mostrando las tareas asignadas por empleado con desglose por cliente, métricas de tareas terminadas y pendientes, gráfica de distribución y opción de descarga en formato PNG.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen empleados activos con asignaciones de tareas vinculadas a clientes en la BD de pruebas (ver Tablas 2, 8, 9 y 3).

**Entradas:**
Filtro de fechas: Fecha inicio: 2024-01-01, Fecha fin: 2026-02-12. Segundo filtro: Fecha inicio: 2026-01-01, Fecha fin: 2026-01-31.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /admin/reportes/carga-trabajo. | 1. Se muestra el reporte con el rango predeterminado (mes actual). Se visualizan las tarjetas de cada empleado activo con tareas asignadas, mostrando su nombre, puesto y conteos de tareas: Total, Terminadas y Pendientes. Dentro de cada tarjeta se muestra la distribución por cliente con barras de progreso. | 1. El sistema le permite al usuario administrador consultar el reporte de carga de trabajo por empleado y cliente, filtrar por rango de fechas, visualizar la distribución de tareas por empleado y cliente, y descargar el reporte en formato PNG. |
| **FILTRAR POR RANGO DE FECHAS** | | |
| 2. Ingresar fecha inicio "2024-01-01" y fecha fin "2026-02-12". Clic en "Filtrar". | 2. El reporte se actualiza mostrando los empleados con tareas asignadas en el rango seleccionado. Los empleados se ordenan de mayor a menor carga total de tareas. | |
| **VERIFICAR DESGLOSE POR EMPLEADO** | | |
| 3. Verificar que cada tarjeta de empleado muestra correctamente el total de tareas, las terminadas (verde) y las pendientes (naranja). | 3. Los conteos son correctos y consistentes (terminadas + pendientes = total). | |
| **VERIFICAR DISTRIBUCIÓN POR CLIENTE** | | |
| 4. Verificar que dentro de cada tarjeta de empleado se muestra la distribución de tareas por cliente, con el nombre del cliente, la cantidad de tareas y una barra de progreso proporcional. | 4. Los clientes se muestran ordenados de mayor a menor cantidad de tareas. Las barras de progreso reflejan el porcentaje de tareas que cada cliente representa del total del empleado. | |
| **FILTRAR CON RANGO DIFERENTE** | | |
| 5. Cambiar el filtro a fecha inicio "2026-01-01" y fecha fin "2026-01-31". Clic en "Filtrar". | 5. El reporte se actualiza mostrando únicamente las tareas con fecha límite dentro de enero 2026. Los conteos y distribuciones cambian reflejando solo ese periodo. | |
| **DESCARGAR REPORTE** | | |
| 6. Clic en "Descargar PNG". | 6. Se genera y descarga automáticamente una imagen PNG con el nombre "reporte-carga-trabajo-completo-[fecha].png". La imagen contiene los KPIs (Total Tareas, Promedio/Empleado, Top Empleado, Top Cliente), la gráfica de distribución de tareas por empleado (pastel), la tabla resumen y el detalle de distribución por cliente. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
