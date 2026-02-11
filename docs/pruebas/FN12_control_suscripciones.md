# FN12. Control de suscripciones

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN12 – Control de Suscripciones                            |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 03:50 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de una suscripción, incluyendo la funcionalidad de renovación.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen categorías de suscripciones activas en el catálogo (ver Tabla 15 de la BD de pruebas).

**Entradas:**
Nueva suscripción: Nombre: "Spotify Premium", Categoría: "Comunicación" (id=5), Fecha inicio: 2026-02-11, Costo: $169.00, Periodicidad: mensual, Nivel de uso: medio, Estatus: activo.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /suscripciones. | 1. Se visualiza el listado de suscripciones con las suscripciones existentes (ver Tabla 16 de la BD de pruebas). | 1. El sistema le permite al usuario administrador crear, leer, actualizar, renovar y eliminar suscripciones. |
| 2. Verificar que se muestran las suscripciones registradas (Adobe Creative Cloud, Hostinger, Google Workspace, ChatGPT Plus). | 2. Las 4 suscripciones de la BD de pruebas se visualizan con su categoría, costo, periodicidad y estatus. | |
| **CREAR** | | |
| 3. Clic en "Nueva Suscripción". | 3. Se abre el formulario de creación con los campos: categoría, nombre, fecha inicio, costo, periodicidad, nivel de uso, días de recordatorio, observaciones y estatus. | |
| 4. Ingresar: Categoría "Comunicación", Nombre "Spotify Premium", Fecha inicio "2026-02-11", Costo "$169.00", Periodicidad "mensual", Nivel de uso "medio", Estatus "activo". | 4. Los campos muestran los valores ingresados. El sistema calculará automáticamente la fecha de vencimiento (2026-03-11). | |
| 5. Clic en "Guardar". | 5. Guarda la suscripción, redirige al listado y muestra mensaje "Suscripción creada exitosamente." | |
| **LEER** | | |
| 6. En el listado, localizar "Spotify Premium" y hacer clic en "Ver detalle". | 6. Se muestra la vista de detalle con todos los datos ingresados: nombre, categoría "Comunicación", costo $169.00, periodicidad mensual, fecha de vencimiento 2026-03-11 y el historial de renovaciones vacío. | |
| **ACTUALIZAR** | | |
| 7. Clic en "Editar" desde la vista de detalle o listado. | 7. Se abre el formulario de edición con los datos actuales de la suscripción precargados. | |
| 8. Cambiar Nivel de uso de "medio" a "alto" y Costo de "$169.00" a "$199.00". Clic en "Guardar". | 8. Guarda los cambios, redirige al listado y muestra mensaje "Suscripción actualizada exitosamente." | |
| 9. Clic en "Ver detalle" de "Spotify Premium" para verificar. | 9. La vista de detalle refleja los cambios: nivel de uso "alto" y costo $199.00. Los demás campos permanecen sin cambio. | |
| **RENOVAR** | | |
| 10. En la vista de detalle de "Spotify Premium", clic en "Renovar". | 10. Se abre el formulario de renovación con los campos: fecha de renovación, costo del ciclo y observaciones. | |
| 11. Ingresar: Fecha renovación "2026-03-11", Costo ciclo "$199.00", Observaciones "Renovación primer mes". Clic en "Guardar". | 11. Guarda la renovación, redirige a la vista de detalle y muestra mensaje "Suscripción renovada exitosamente." La fecha de vencimiento se actualiza de 2026-03-11 a 2026-04-11. | |
| 12. Verificar en la vista de detalle el historial de renovaciones. | 12. El historial muestra 1 registro: fecha renovación 2026-03-11, costo $199.00, vencimiento anterior 2026-03-11 y vencimiento nuevo 2026-04-11. | |
| **ELIMINAR** | | |
| 13. Regresar al listado. Clic en "Dar de baja" de "Spotify Premium". | 13. El sistema cambia el estatus a "inactivo" (baja lógica), redirige al listado y muestra mensaje "Suscripción dada de baja exitosamente." | |
| 14. Verificar en el listado que "Spotify Premium" aparece con estatus "inactivo". | 14. La suscripción se muestra con estatus "inactivo" y su badge correspondiente. | |
| 15. Clic en "Reactivar" de la suscripción "Spotify Premium". | 15. El sistema cambia el estatus a "activo", redirige al listado y muestra mensaje "Suscripción reactivada exitosamente." La suscripción vuelve a estatus activo. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
