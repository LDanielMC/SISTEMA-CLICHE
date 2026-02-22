# FN3. Gestión de clientes

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN3 – Gestión de Clientes                                 |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 17:40 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de un cliente, incluyendo la creación automática de su cuenta de usuario y el registro en bitácora.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen clientes registrados en la BD de pruebas (ver Tabla 5 y Tabla 1).

**Entradas:**
Nuevo cliente: Empresa: "Tacos El Paisa", Nombre: "Pedro", Ap. Paterno: "Sánchez", Ap. Materno: "Ruiz", Giro: "Restaurantes", Correo: "pedro.sanchez@test.com", Teléfono: "6145551234".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /clientes. | 1. Se visualiza el listado de clientes activos con los clientes de la BD de pruebas (Los Callos de Cortes y Campestre). | 1. El sistema le permite al usuario administrador crear, leer, actualizar y dar de baja clientes. |
| 2. Verificar que se muestran los clientes con su empresa, nombre, giro, teléfono, correo y fecha de registro. | 2. Los 2 clientes activos de la BD de pruebas se visualizan con sus datos completos. | |
| **CREAR** | | |
| 3. Clic en "Nuevo Cliente". | 3. Se abre el formulario de registro con los campos: empresa, nombre, apellido paterno, apellido materno, giro/sector, correo y teléfono. | |
| 4. Ingresar: Empresa "Tacos El Paisa", Nombre "Pedro", Ap. Paterno "Sánchez", Ap. Materno "Ruiz", Giro "Restaurantes", Correo "pedro.sanchez@test.com", Teléfono "6145551234". | 4. Los campos muestran los valores ingresados correctamente. | |
| 5. Clic en "Guardar". | 5. El sistema crea el cliente, su cuenta de usuario (rol "cliente"), registra el alta en la bitácora y envía invitación por correo. Redirige al listado con mensaje de éxito. | |
| **LEER** | | |
| 6. En el listado, verificar que aparece "Pedro Sánchez Ruiz" de "Tacos El Paisa" con giro "Restaurantes" y estatus activo. | 6. El nuevo cliente se muestra en la lista con todos sus datos: empresa, nombre completo, giro, teléfono, correo y fecha de registro. | |
| **ACTUALIZAR** | | |
| 7. Clic en "Editar" del cliente "Pedro Sánchez Ruiz". | 7. Se abre el formulario de edición con los datos del cliente precargados. | |
| 8. Cambiar Empresa de "Tacos El Paisa" a "Tacos El Paisa Sucursal Norte" y Teléfono de "6145551234" a "6145559876". Clic en "Guardar". | 8. Guarda los cambios, redirige al listado y muestra mensaje "Cliente actualizado correctamente." | |
| 9. Verificar en el listado que "Pedro Sánchez Ruiz" ahora muestra empresa "Tacos El Paisa Sucursal Norte" y teléfono "6145559876". | 9. Los datos actualizados se reflejan correctamente en el listado. | |
| **ELIMINAR** | | |
| 10. Clic en "Dar de baja" del cliente "Pedro Sánchez Ruiz". | 10. El sistema cambia el estatus a "inactivo", registra la fecha de baja y guarda el movimiento en la bitácora. Redirige al listado con mensaje "Cliente dado de baja correctamente (Bitácora actualizada)." | |
| 11. Cambiar el filtro de estatus a "inactivo" y verificar que "Pedro Sánchez Ruiz" aparece con estatus "inactivo" y fecha de baja. | 11. El cliente se muestra en la lista de inactivos con su fecha de baja. Ya no aparece en la lista de activos. | |
| 12. Clic en "Reactivar" del cliente "Pedro Sánchez Ruiz". | 12. El sistema cambia el estatus a "activo", limpia la fecha de baja, registra la reactivación en bitácora. Redirige al listado con mensaje "Cliente reactivado correctamente (Bitácora actualizada)." | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
