# FN5. Gestión de cotizaciones

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN5 – Gestión de Cotizaciones                             |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 20:15 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de una cotización, incluyendo la generación de PDF y envío por correo electrónico al cliente.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen cotizaciones y clientes registrados en la BD de pruebas (ver Tablas 5 y 3).

**Entradas:**
Nueva cotización: Cliente: "LOS CALLOS DE CORTES", Título: "Campaña Publicitaria Verano 2026", Fecha emisión: 2026-02-11, Vencimiento: 15 días, Estatus: "pendiente".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /cotizaciones. | 1. Se visualiza el listado de cotizaciones con las 2 cotizaciones de la BD de pruebas (Paquete Redes Sociales Premium y Paquete Campestre). Se muestran pestañas de filtro: Todas, Pendientes, Aceptadas, Rechazadas. | 1. El sistema le permite al usuario administrador crear, leer, actualizar y eliminar cotizaciones, generar PDF y enviarlo por correo al cliente. |
| 2. Verificar que se muestran las cotizaciones con su folio, título, cliente, fecha, total y estatus. | 2. Las 2 cotizaciones de la BD de pruebas se visualizan con sus datos completos. | |
| **CREAR** | | |
| 3. Clic en "+ Nueva". | 3. Se abre el formulario con campos: título, cliente, fecha de emisión, días de vencimiento, texto de introducción, estatus y la sección de servicios/partidas. | |
| 4. Seleccionar cliente "LOS CALLOS DE CORTES". Ingresar título "Campaña Publicitaria Verano 2026", fecha "2026-02-11", vencimiento 15 días, estatus "pendiente". Agregar al menos un servicio. | 4. Los campos muestran los valores ingresados correctamente. | |
| 5. Clic en "Guardar". | 5. Crea la cotización, redirige al listado y muestra mensaje "Cotización creada correctamente." | |
| **LEER** | | |
| 6. En el listado, verificar que aparece "Campaña Publicitaria Verano 2026" del cliente "LOS CALLOS DE CORTES" con estatus "pendiente". | 6. La nueva cotización se muestra en la lista con su folio, título, cliente, fecha, total y estatus. | |
| 7. Clic en el botón de PDF de la cotización creada. | 7. Se genera y muestra el PDF con los datos de la cotización: cabecera, partidas, subtotal, IVA y total. | |
| **ACTUALIZAR** | | |
| 8. Clic en "Editar" de la cotización "Campaña Publicitaria Verano 2026". | 8. Se abre el formulario de edición con los datos de la cotización precargados. | |
| 9. Cambiar título a "Campaña Publicitaria Verano-Otoño 2026" y cambiar estatus a "aceptada". Clic en "Actualizar Cotización". | 9. Guarda los cambios, redirige al listado con mensaje "Cotización actualizada correctamente." | |
| **ENVIAR PDF** | | |
| 10. Clic en "Enviar PDF" de la cotización actualizada. | 10. El sistema genera el PDF y lo envía por correo electrónico al cliente. Redirige al listado con mensaje de confirmación de envío. | |
| **ELIMINAR** | | |
| 11. Clic en "Eliminar" de la cotización "Campaña Publicitaria Verano-Otoño 2026". | 11. El sistema elimina la cotización y todos sus servicios asociados. Redirige al listado con mensaje "Cotización eliminada." | |
| 12. Verificar en el listado que la cotización eliminada ya no aparece en ningún filtro de estatus. | 12. La cotización ya no existe en el sistema. Solo se muestran las 2 cotizaciones originales de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
