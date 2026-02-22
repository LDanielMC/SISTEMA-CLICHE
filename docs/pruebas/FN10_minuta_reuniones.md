# FN10. Minuta de reuniones

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN10 – Minuta de Reuniones                                |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 01:15 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de las minutas de reuniones realizadas con los clientes, incluyendo el registro de asistentes, puntos tratados y observaciones.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen minutas y clientes registrados en la BD de pruebas (ver Tablas 13 y 3).

**Entradas:**
Nueva minuta: Cliente: "LOS CALLOS DE CORTES", Título: "Revisión de avances campaña 2026", Fecha: 2026-02-12, Asistentes: "Carlos Matsui, Equipo Cliché", Puntos tratados: "Revisión de métricas de redes sociales. Aprobación de contenido para marzo. Ajuste de presupuesto publicitario.", Observaciones: "Cliente solicita mayor enfoque en Instagram Reels".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /minutas. | 1. Se visualiza el listado de minutas con la minuta existente de la BD de pruebas ("Reunión inicial Los Callos de Cortes"). Se muestran pestañas de filtro: Todas, Con Pendientes, Completadas. | 1. El sistema le permite al usuario administrador crear, leer, actualizar y eliminar minutas de reuniones con sus datos de asistentes, puntos tratados y observaciones. |
| 2. Verificar que la minuta se muestra con su folio, título, cliente, fecha y estatus de acuerdos. | 2. La minuta de la BD de pruebas se visualiza con sus datos completos. | |
| **CREAR** | | |
| 3. Clic en "+ Nueva". | 3. Se abre el formulario con campos: cliente (desplegable), fecha, título, asistentes (editor de texto), puntos tratados (editor de texto), observaciones (editor de texto) y la sección de acuerdos. | |
| 4. Seleccionar cliente "LOS CALLOS DE CORTES". Ingresar título "Revisión de avances campaña 2026", fecha "2026-02-12", asistentes "Carlos Matsui, Equipo Cliché", puntos tratados y observaciones según las entradas. Agregar al menos un acuerdo. | 4. Los campos muestran los valores ingresados correctamente en los editores de texto enriquecido. | |
| 5. Clic en "Guardar Minuta". | 5. Crea la minuta, redirige al listado y muestra mensaje "Minuta creada correctamente." | |
| **LEER** | | |
| 6. En el listado, verificar que aparece "Revisión de avances campaña 2026" del cliente "LOS CALLOS DE CORTES" con fecha 2026-02-12. | 6. La nueva minuta se muestra en la lista con su folio, título, cliente y fecha. | |
| **ACTUALIZAR** | | |
| 7. Clic en "Editar" de la minuta "Revisión de avances campaña 2026". | 7. Se abre el formulario de edición con los datos precargados: cliente, fecha, título, asistentes, puntos tratados y observaciones en los editores de texto. | |
| 8. Cambiar título a "Revisión y planeación campaña 2026" y agregar en observaciones "Programar siguiente reunión para marzo". Clic en "Guardar Cambios". | 8. Guarda los cambios, redirige al listado y muestra mensaje "Minuta actualizada correctamente." | |
| 9. Verificar en el listado que la minuta muestra el nuevo título "Revisión y planeación campaña 2026". | 9. Los datos actualizados se reflejan correctamente en el listado. | |
| **ELIMINAR** | | |
| 10. Clic en "Eliminar" de la minuta "Revisión y planeación campaña 2026". | 10. El sistema elimina la minuta y todos sus acuerdos asociados. Redirige al listado y muestra mensaje "Minuta eliminada." | |
| 11. Verificar en el listado que la minuta eliminada ya no aparece. | 11. La minuta ya no existe en el sistema. Solo se muestra la minuta original de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
