# FN19. Reporte de costos y proyección de suscripciones

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN19 – Reporte de Costos y Proyección de Suscripciones   |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 02:58 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema genera correctamente el reporte de costos y proyección de suscripciones, mostrando KPIs de gasto mensual y anual, alertas de vencimiento, gráfica de distribución de gastos, filtros por categoría, periodicidad y nivel de uso, y opción de exportación a PDF.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen suscripciones activas con diferentes categorías, periodicidades y niveles de uso en la BD de pruebas (ver Tablas 15, 16 y 17).

**Entradas:**
Filtro por periodicidad: "mensual". Filtro por nivel de uso: "bajo". Ordenar por: "costo" descendente.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /admin/reportes/suscripciones. | 1. Se muestra el reporte con las 6 tarjetas de KPIs: Gasto Mensual, Proyección Anual, Total Suscripciones, Renovar Esta Semana, Renovar Este Mes y Promedio/Mes. Se visualiza la gráfica de pastel con el Top 10 de servicios con mayor gasto mensualizado y la tabla de suscripciones con columnas: Servicio, Costo, Vencimiento, Estado y Uso. | 1. El sistema le permite al usuario administrador consultar el reporte de costos y proyección de suscripciones, visualizar KPIs de gasto, alertas de vencimiento, gráfica de distribución de gastos, aplicar filtros y exportar el reporte a PDF. |
| **VERIFICAR KPIs** | | |
| 2. Verificar que el Gasto Mensual muestra el costo mensualizado total de todas las suscripciones activas (anuales divididas entre 12, mensuales tal cual). | 2. El KPI de Gasto Mensual refleja correctamente la suma mensualizada. La Proyección Anual muestra el Gasto Mensual multiplicado por 12. El Promedio/Mes muestra el gasto mensual dividido entre el número de suscripciones. | |
| **VERIFICAR ALERTAS DE VENCIMIENTO** | | |
| 3. Verificar las tarjetas de "Renovar Esta Semana" y "Renovar Este Mes". | 3. Se muestran las cantidades correctas de suscripciones que vencen en los próximos 7 y 30 días. Las tarjetas cambian de color según la urgencia (rojo si hay alertas, verde si no). | |
| **VERIFICAR GRÁFICA** | | |
| 4. Verificar que la gráfica de pastel muestra la distribución de gasto por servicio. | 4. La gráfica muestra los Top 10 servicios con mayor gasto mensualizado, con colores diferenciados y tooltips que muestran el monto en formato de moneda. | |
| **FILTRAR POR PERIODICIDAD** | | |
| 5. Seleccionar periodicidad "mensual" en los filtros. Clic en "Filtrar". | 5. La tabla se actualiza mostrando únicamente las suscripciones con periodicidad mensual. Los KPIs y la gráfica se recalculan reflejando solo las suscripciones filtradas. | |
| **FILTRAR POR NIVEL DE USO** | | |
| 6. Seleccionar nivel de uso "bajo". Clic en "Filtrar". | 6. La tabla muestra solo las suscripciones con nivel de uso bajo. Los KPIs se actualizan reflejando los costos de esas suscripciones. | |
| **ORDENAR RESULTADOS** | | |
| 7. Seleccionar ordenar por "costo" en dirección "descendente". Clic en "Filtrar". | 7. Las suscripciones se reordenan de mayor a menor costo. | |
| **EXPORTAR A PDF** | | |
| 8. Clic en "Exportar PDF". | 8. Se genera y descarga automáticamente un archivo PDF con el nombre "reporte-suscripciones-[fecha].pdf" conteniendo los KPIs, la gráfica de distribución de gastos y la tabla de suscripciones con los filtros aplicados. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
