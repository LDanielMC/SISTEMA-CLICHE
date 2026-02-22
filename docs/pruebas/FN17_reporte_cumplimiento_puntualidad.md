# FN17. Reporte de cumplimiento y puntualidad de empleados

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN17 – Reporte de Cumplimiento y Puntualidad de Empleados |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 02:41 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema genera correctamente el reporte de cumplimiento y puntualidad de empleados, mostrando métricas de desempeño basadas en las tareas asignadas, con filtros por rango de fechas y opción de descarga en formato JPG.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen empleados activos y asignaciones de tareas con diferentes estados en la BD de pruebas (ver Tablas 2, 8 y 9).

**Entradas:**
Filtro de fechas: Fecha inicio: 2024-01-01, Fecha fin: 2026-02-12. Segundo filtro: Fecha inicio: 2026-01-01, Fecha fin: 2026-01-31.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /admin/reportes/cumplimiento. | 1. Se muestra el reporte de cumplimiento con el rango de fechas predeterminado (mes actual). Se visualiza la tabla con las columnas: Empleado, Total Tareas, Entregadas, A Tiempo, Tardías, Pendientes, Cumplimiento (%) y Puntualidad (%). | 1. El sistema le permite al usuario administrador generar y consultar el reporte de cumplimiento y puntualidad de empleados, filtrar por rango de fechas, visualizar métricas de desempeño y descargar el reporte en formato JPG. |
| **FILTRAR POR RANGO DE FECHAS** | | |
| 2. Ingresar fecha inicio "2024-01-01" y fecha fin "2026-02-12". Clic en "Filtrar Reporte". | 2. El reporte se actualiza mostrando los datos de todos los empleados activos con tareas asignadas en el rango seleccionado. Se muestran los conteos de tareas totales, entregadas, a tiempo, tardías y pendientes por cada empleado. | |
| **VERIFICAR MÉTRICAS DE CUMPLIMIENTO** | | |
| 3. Verificar que el porcentaje de cumplimiento de cada empleado se calcula correctamente (entregadas / total × 100). | 3. El porcentaje de cumplimiento refleja la proporción de tareas entregadas respecto al total asignado. Se muestra con indicador de color: verde (≥90%), amarillo (≥70%) o rojo (<70%), junto con una barra de progreso visual. | |
| **VERIFICAR MÉTRICAS DE PUNTUALIDAD** | | |
| 4. Verificar que el porcentaje de puntualidad de cada empleado se calcula correctamente (a tiempo / total × 100). | 4. El porcentaje de puntualidad refleja la proporción de tareas entregadas a tiempo (fecha de entrega ≤ fecha límite) respecto al total asignado. Se muestra con indicador de color: verde (≥90%), amarillo (≥70%) o rojo (<70%). | |
| **VERIFICAR ORDENAMIENTO** | | |
| 5. Verificar que los empleados se muestran ordenados por porcentaje de cumplimiento de mayor a menor. | 5. Los empleados aparecen ordenados descendentemente por cumplimiento. Los empleados con cumplimiento menor a 70% se resaltan con fondo rojo claro. | |
| **FILTRAR CON RANGO DIFERENTE** | | |
| 6. Cambiar el filtro a fecha inicio "2026-01-01" y fecha fin "2026-01-31". Clic en "Filtrar Reporte". | 6. El reporte se actualiza mostrando únicamente las tareas asignadas dentro de enero 2026. Las métricas cambian reflejando solo las tareas de ese periodo. | |
| **DESCARGAR REPORTE** | | |
| 7. Clic en "Descargar JPG". | 7. Se genera y descarga automáticamente una imagen JPG del reporte con el nombre "reporte-cumplimiento-[fecha].jpg". La imagen contiene la tabla completa con todos los datos visibles. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
