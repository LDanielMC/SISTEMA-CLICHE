# FN6. Gestión de servicios

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN6 – Gestión de Servicios                                |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 20:18 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de los servicios (partidas) de una cotización, incluyendo el cálculo automático de subtotal, IVA y total.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existe la cotización "Paquete Redes Sociales Premium" (id=16) del cliente LOS CALLOS DE CORTES con 3 servicios registrados (ver Tablas 5 y 6).

**Entradas:**
Cotización: Paquete Redes Sociales Premium (id=16). Nuevo servicio: Título: "Community Manager", Cantidad: 1, Descripción: "Gestión de comunidad en redes sociales", Precio unitario: $2,500.00, IVA 16%.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /cotizaciones. Clic en "Editar" de la cotización "Paquete Redes Sociales Premium". | 1. Se abre el formulario de edición. En la sección de servicios se muestran las 3 partidas existentes: "Redes Sociales" ($2,000.00), "Fotografía" ($1,000.00) y "Diseño" ($1,000.00). | 1. El sistema le permite al usuario administrador crear, leer, actualizar y eliminar servicios dentro de una cotización con cálculo automático de totales. |
| **LEER** | | |
| 2. Verificar los datos de cada servicio: título, cantidad, descripción, precio unitario, IVA y total por línea. Verificar totales generales: Subtotal $3,448.28, IVA $551.72, Total $4,000.00. | 2. Los datos coinciden con la Tabla 6 de la BD de pruebas. Los totales se calculan correctamente. | |
| **CREAR** | | |
| 3. Clic en "+ Agregar al final". | 3. Se agrega una nueva fila vacía en la tabla de servicios con los campos: título, descripción, cantidad, precio unitario e IVA. | |
| 4. Ingresar: Título "Community Manager", Cantidad 1, Descripción "Gestión de comunidad en redes sociales", Precio unitario $2,500.00, IVA 16%. | 4. Los campos muestran los valores ingresados. El sistema calcula automáticamente el IVA ($400.00) y el total de la línea ($2,900.00). Los totales generales se actualizan: Subtotal $5,948.28, IVA $951.72, Total $6,900.00. | |
| 5. Clic en "Actualizar Cotización". | 5. Guarda la cotización con el nuevo servicio, redirige al listado con mensaje "Cotización actualizada correctamente." | |
| 6. Clic en "Editar" de la misma cotización para verificar. | 6. La sección de servicios ahora muestra 4 partidas: las 3 originales más "Community Manager" con todos sus datos persistidos. | |
| **ACTUALIZAR** | | |
| 7. En el servicio "Community Manager", cambiar Precio unitario de $2,500.00 a $3,000.00. | 7. El sistema recalcula automáticamente: IVA de la línea $480.00, total línea $3,480.00. Los totales generales se actualizan. | |
| 8. Clic en "Actualizar Cotización". | 8. Guarda los cambios, redirige al listado con mensaje "Cotización actualizada correctamente." | |
| 9. Clic en "Editar" de la cotización para verificar la actualización. | 9. El servicio "Community Manager" refleja el nuevo precio ($3,000.00) y los totales generales están recalculados correctamente. | |
| **ELIMINAR** | | |
| 10. Clic en el botón de eliminar (✕) del servicio "Community Manager". | 10. La fila del servicio se elimina visualmente de la tabla. Los totales generales se recalculan al valor original. | |
| 11. Clic en "Actualizar Cotización". | 11. Guarda los cambios, redirige al listado con mensaje "Cotización actualizada correctamente." El servicio eliminado se borra de la BD. | |
| 12. Clic en "Editar" de la cotización para verificar la eliminación. | 12. La sección de servicios muestra únicamente las 3 partidas originales. El servicio "Community Manager" ya no existe. Totales: Subtotal $3,448.28, IVA $551.72, Total $4,000.00. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
