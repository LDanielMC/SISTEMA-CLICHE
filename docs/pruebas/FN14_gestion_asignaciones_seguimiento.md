# FN14. Gestión de tareas (Asignaciones y Seguimiento)

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN14 – Gestión de Tareas (Asignaciones y Seguimiento)     |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 23:40 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el administrador puede asignar tareas a empleados definiendo prioridad y fecha límite, que el empleado puede gestionar el estado de sus tareas asignadas y subir evidencia, y que el administrador puede evaluar las tareas como completa, parcialmente completa o incompleta.

**Condiciones de ejecución:**
Sesión iniciada como administrador para los pasos de asignación y evaluación.
Sesión iniciada como empleado (luisd.m.c2002+E1@gmail.com) para los pasos de seguimiento y evidencia.
Existen tareas, empleados y asignaciones en la BD de pruebas (ver Tablas 8, 3 y 9).

**Entradas:**
Asignación: Tarea: "Diseño de menú digital" (id=21), Empleado: "Juan Carlos Pérez" (id=1), Prioridad: "alta", Fecha límite: 2026-03-15. Evidencia: archivo PDF de prueba.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. (Admin) Navegar a /asignaciones. | 1. Se visualiza el listado de asignaciones con las 3 asignaciones de la BD de pruebas. Se muestran filtros por empleado, prioridad, estado del empleado y estado del administrador. | 1. El sistema le permite al administrador asignar y evaluar tareas, y al empleado visualizar sus tareas, actualizar su avance y subir evidencia. |
| **CREAR (Asignar tarea)** | | |
| 2. (Admin) Clic en "Nueva Asignación". | 2. Se abre el formulario con campos: tarea (búsqueda dinámica), empleado (búsqueda dinámica), prioridad (baja, media, alta, urgente) y fecha límite. | |
| 3. (Admin) Buscar y seleccionar tarea "Diseño de menú digital", empleado "Juan Carlos Pérez", prioridad "alta", fecha límite "2026-03-15". Clic en "Guardar". | 3. Crea la asignación, envía notificación al empleado y redirige al listado con mensaje "Tarea asignada exitosamente." | |
| **LEER (Empleado ve sus tareas)** | | |
| 4. (Empleado) Iniciar sesión como Juan Carlos Pérez. Navegar a /mis-tareas. | 4. Se visualiza el panel de tareas del empleado organizado por estado: Asignadas, En Proceso y Terminadas. Aparece la tarea "Diseño de menú digital" en la columna "Asignadas" con prioridad "alta" y fecha límite 2026-03-15. | |
| **ACTUALIZAR (Empleado cambia estado)** | | |
| 5. (Empleado) Cambiar el estado de la tarea "Diseño de menú digital" de "asignada" a "en_proceso". | 5. El estado se actualiza correctamente. La tarea se mueve a la columna "En Proceso". Se envía notificación al administrador. | |
| **SUBIR EVIDENCIA** | | |
| 6. (Empleado) En la tarea "Diseño de menú digital", clic en "Subir evidencia". Seleccionar un archivo PDF y confirmar. | 6. El archivo se sube correctamente, se registra la fecha de entrega, la tarea se marca como "terminada" automáticamente y se mueve a la columna "Terminadas". Muestra mensaje "Evidencia subida exitosamente." Se notifica al administrador. | |
| **EVALUAR (Admin evalúa tarea)** | | |
| 7. (Admin) Iniciar sesión como administrador. Navegar a /asignaciones. Clic en el ícono del ojo (Evaluar esta tarea) de la asignación "Diseño de menú digital". | 7. Se abre la vista Kanban con las columnas: Pendientes, Completas, Parcialmente e Incompletas. La tarjeta de la tarea se muestra en la columna "Pendientes" con su prioridad, fecha límite y fecha de entrega. | |
| 8. (Admin) Clic en "Ver evidencia PDF" dentro de la tarjeta. | 8. Se abre el archivo PDF de evidencia en el navegador. | |
| 9. (Admin) Arrastrar la tarjeta de la tarea a la columna "Completas". | 9. La tarjeta se mueve a la columna "Completas". El estado del administrador se actualiza a "completa". Se envía notificación al empleado informando la evaluación. Muestra mensaje "Evaluación actualizada correctamente." | |
| **ELIMINAR** | | |
| 10. (Admin) Clic en "Eliminar" de la asignación creada. | 10. El sistema elimina la asignación, su evidencia y las notificaciones relacionadas. Redirige al listado con mensaje "Asignación eliminada exitosamente." | |
| 11. Verificar en el listado que la asignación eliminada ya no aparece. | 11. La asignación ya no existe. Solo se muestran las 3 asignaciones originales de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
