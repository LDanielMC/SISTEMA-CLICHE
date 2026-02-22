# FN9. Calendario de publicaciones

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN9 – Calendario de Publicaciones                         |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 00:42 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite gestionar el calendario de publicaciones en redes sociales: visualizar el calendario general, crear calendarios de publicaciones de forma masiva por cliente, actualizar y eliminar publicaciones individuales o por lote.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen publicaciones, clientes, plataformas y formatos en la BD de pruebas (ver Tablas 10, 11, 12 y 3).

**Entradas:**
Nuevo calendario para cliente "LOS CALLOS DE CORTES": Publicación 1: Plataforma: Facebook, Formato: Post, Fecha: 2026-03-01, Copy: "Promoción de cuaresma – Mariscos frescos", Estatus: Pendiente. Publicación 2: Plataforma: Instagram, Formato: Reel, Fecha: 2026-03-05, Copy: "Detrás de cámaras – Preparación de ceviche", Estatus: Pendiente.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /calendario. | 1. Se visualiza el calendario general (FullCalendar) con las publicaciones de la BD de pruebas representadas como eventos con colores según estatus (verde=Publicado, amarillo=Pendiente, rojo=Reprogramar). | 1. El sistema le permite al usuario administrador visualizar el calendario general, crear calendarios de publicaciones de forma masiva por cliente, actualizar estatus y eliminar publicaciones individuales o por lote. |
| **LEER** | | |
| 2. Navegar a /calendario/gestion. Seleccionar el cliente "LOS CALLOS DE CORTES". | 2. Se muestra la vista de gestión del cliente con las publicaciones agrupadas por lote/calendario, mostrando tarjetas con el rango de fechas y la cantidad de publicaciones por cada lote. | |
| **CREAR** | | |
| 3. Clic en "Nuevo Calendario". Agregar Publicación 1: Facebook, Post, 2026-03-01, Copy "Promoción de cuaresma – Mariscos frescos", Estatus Pendiente. Publicación 2: Instagram, Reel, 2026-03-05, Copy "Detrás de cámaras – Preparación de ceviche", Estatus Pendiente. | 3. Las filas se agregan correctamente con los campos: plataforma, formato, fecha, copy, arte y estatus. | |
| 4. Clic en "Guardar Calendario". | 4. Crea las 2 publicaciones como un lote, redirige a la gestión del cliente y muestra mensaje "¡Listo! Se creó el calendario y se guardaron 2 publicaciones." | |
| 5. Verificar que aparece una nueva tarjeta de calendario con el rango "1 de marzo – 5 de marzo 2026" y 2 publicaciones. | 5. La tarjeta del nuevo calendario se muestra con el rango de fechas y la cantidad correcta de publicaciones. | |
| 6. Navegar a /calendario y verificar que los 2 nuevos eventos aparecen en las fechas correspondientes. | 6. Los eventos se muestran en el calendario general en las fechas 1 y 5 de marzo 2026 con color amarillo (Pendiente). | |
| **ACTUALIZAR** | | |
| 7. Navegar a /calendario/gestion. Seleccionar "LOS CALLOS DE CORTES". Clic en "Editar" de la tarjeta del calendario creado. | 7. Se abre el modal de edición con las 2 publicaciones precargadas con sus datos (plataforma, formato, fecha, copy, estatus). | |
| 8. Cambiar el Copy de la Publicación 1 a "Cuaresma 2026 – Los mejores mariscos de Cuernavaca" y el estatus a "Publicado". Clic en "Guardar Cambios". | 8. Guarda los cambios y muestra mensaje con el conteo de registros procesados. La publicación actualizada refleja el nuevo copy y estatus. | |
| **ELIMINAR** | | |
| 9. En la gestión del cliente, clic en "Eliminar calendario" de la tarjeta del calendario creado. Confirmar la eliminación. | 9. El sistema elimina todas las publicaciones del lote. Redirige a la gestión del cliente y muestra mensaje "Calendario eliminado correctamente (2 publicaciones borradas)." | |
| 10. Verificar que la tarjeta del calendario eliminado ya no aparece en la gestión del cliente. | 10. El lote ya no existe. Solo se muestran los calendarios originales de la BD de pruebas. | |
| 11. Navegar a /calendario y verificar que los eventos eliminados ya no aparecen en las fechas 1 y 5 de marzo 2026. | 11. El calendario general ya no muestra los eventos eliminados. Solo se visualizan las publicaciones originales de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
