# FN2. Gestión de empleados

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN2 – Gestión de Empleados                                |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 17:05 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de un empleado, incluyendo la creación automática de su cuenta de usuario y el envío de invitación por correo electrónico.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen empleados registrados en la BD de pruebas (ver Tabla 3 y Tabla 1).

**Entradas:**
Nuevo empleado: Nombre: "Roberto", Apellido paterno: "García", Apellido materno: "López", Correo: "roberto.garcia@test.com", Teléfono: "6141234567", Puesto: "Community Manager".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /empleados. | 1. Se visualiza el listado de empleados activos con los empleados de la BD de pruebas (Juan Carlos Pérez y Mariana Rodríguez). | 1. El sistema le permite al usuario administrador crear, leer, actualizar y dar de baja empleados. |
| 2. Verificar que se muestran los empleados con su nombre, teléfono, puesto, fecha de ingreso y correo. | 2. Los 2 empleados activos de la BD de pruebas se visualizan con sus datos completos. | |
| **CREAR** | | |
| 3. Clic en "Nuevo Empleado". | 3. Se abre el formulario de registro con los campos: nombre, apellido paterno, apellido materno, correo, teléfono, puesto y fecha de ingreso. | |
| 4. Ingresar: Nombre "Roberto", Ap. Paterno "García", Ap. Materno "López", Correo "roberto.garcia@test.com", Teléfono "6141234567", Puesto "Community Manager". | 4. Los campos muestran los valores ingresados correctamente. | |
| 5. Clic en "Guardar". | 5. El sistema crea el empleado y su cuenta de usuario asociada (rol "empleado"), envía invitación por correo para crear contraseña, redirige al listado y muestra mensaje "Empleado registrado. Se envió una invitación al correo para crear su contraseña." | |
| **LEER** | | |
| 6. En el listado, verificar que aparece "Roberto García López" con puesto "Community Manager" y estatus activo. | 6. El nuevo empleado se muestra en la lista con todos sus datos: nombre completo, teléfono, puesto, fecha de ingreso y correo electrónico. | |
| **ACTUALIZAR** | | |
| 7. Clic en "Editar" del empleado "Roberto García López". | 7. Se abre el formulario de edición con los datos actuales del empleado precargados. | |
| 8. Cambiar Puesto de "Community Manager" a "Diseñador Gráfico" y Teléfono de "6141234567" a "6149876543". Clic en "Guardar". | 8. Guarda los cambios, redirige al listado y muestra mensaje "Empleado actualizado correctamente." | |
| 9. Verificar en el listado que "Roberto García López" ahora muestra puesto "Diseñador Gráfico" y teléfono "6149876543". | 9. Los datos actualizados se reflejan correctamente en el listado. | |
| **ELIMINAR** | | |
| 10. Clic en "Dar de baja" del empleado "Roberto García López". | 10. El sistema cambia el estatus a "baja" y registra la fecha de baja (baja lógica). Redirige al listado y muestra mensaje "Empleado dado de baja correctamente." | |
| 11. Cambiar el filtro de estatus a "baja" y verificar que "Roberto García López" aparece con estatus "baja" y fecha de baja registrada. | 11. El empleado se muestra en la lista de bajas con su fecha de baja. Ya no aparece en la lista de empleados activos. | |
| 12. Clic en "Reactivar" del empleado "Roberto García López". | 12. El sistema cambia el estatus a "activo", limpia la fecha de baja, redirige al listado y muestra mensaje "Empleado reactivado correctamente." El empleado vuelve a aparecer en la lista de activos. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
