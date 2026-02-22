# FN4. Información fiscal de clientes

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN4 – Información Fiscal de Clientes                      |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 17:44 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de la información fiscal de un cliente, gestionando múltiples registros fiscales dentro del formulario de edición del cliente.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existe el cliente "LOS CALLOS DE CORTES" (id_cliente=2) con un registro fiscal existente (ver Tabla 4 de la BD de pruebas).

**Entradas:**
Cliente: LOS CALLOS DE CORTES (id=2). Nuevo registro fiscal: RFC: "MACL850101AB2", Razón social: "Callos de Cortes Sucursal Sur S.A. de C.V.", Régimen: "Régimen Simplificado de Confianza", Correo fiscal: "sucursalsur@loscallosdecortes.com", Teléfono fiscal: "7771234599", Dirección fiscal: "Calle Morelos #120, Col. Centro, Cuernavaca, Morelos".
    
---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /clientes. Clic en "Editar" del cliente "LOS CALLOS DE CORTES". | 1. Se abre el formulario de edición del cliente. En la sección de información fiscal se muestra 1 registro existente: RFC "MACL850101AB1", Razón social "Carlos Matsui López S.A. de C.V." | 1. El sistema le permite al usuario administrador crear, leer, actualizar y eliminar registros de información fiscal de un cliente. |
| **LEER** | | |
| 2. Verificar los datos del registro fiscal existente: RFC, razón social, régimen, correo fiscal y dirección fiscal. | 2. Los datos coinciden con la Tabla 4 de la BD de pruebas: RFC "MACL850101AB1", Razón social "Carlos Matsui López S.A. de C.V.", Dirección "Av. Juan Pablo II #45, Col. Vista Hermosa, Cuernavaca, Morelos". | |
| **CREAR** | | |
| 3. Clic en el botón para agregar un nuevo registro fiscal. | 3. Se agrega una nueva fila vacía en la sección de información fiscal con los campos: RFC, razón social, régimen, correo fiscal, teléfono fiscal y dirección fiscal. | |
| 4. Ingresar: RFC "MACL850101AB2", Razón social "Callos de Cortes Sucursal Sur S.A. de C.V.", Régimen "Régimen Simplificado de Confianza", Correo "sucursalsur@loscallosdecortes.com", Teléfono "7771234599", Dirección "Calle Morelos #120, Col. Centro, Cuernavaca, Morelos". | 4. Los campos de la nueva fila muestran los valores ingresados. | |
| 5. Clic en "Guardar". | 5. Guarda los cambios del cliente con el nuevo registro fiscal, redirige al listado y muestra mensaje "Cliente actualizado correctamente." | |
| 6. Clic en "Editar" del cliente "LOS CALLOS DE CORTES" para verificar. | 6. La sección de información fiscal ahora muestra 2 registros: el original (MACL850101AB1) y el nuevo (MACL850101AB2) con todos sus datos persistidos. | |
| **ACTUALIZAR** | | |
| 7. En el segundo registro fiscal (MACL850101AB2), cambiar Régimen a "Régimen General de Ley Personas Morales" y Teléfono fiscal a "7771234600". | 7. Los campos se actualizan visualmente en el formulario. | |
| 8. Clic en "Guardar". | 8. Guarda los cambios, redirige al listado y muestra mensaje "Cliente actualizado correctamente." | |
| 9. Clic en "Editar" del cliente para verificar la actualización. | 9. El segundo registro fiscal refleja los cambios: régimen "Régimen General de Ley Personas Morales" y teléfono "7771234600". Los demás campos permanecen sin cambio. | |
| **ELIMINAR** | | |
| 10. Clic en el botón de eliminar (✕) del segundo registro fiscal (MACL850101AB2). | 10. La fila del segundo registro fiscal se elimina visualmente del formulario. Solo queda el registro original. | |
| 11. Clic en "Guardar". | 11. Guarda los cambios, redirige al listado y muestra mensaje "Cliente actualizado correctamente." El registro fiscal eliminado se borra de la BD. | |
| 12. Clic en "Editar" del cliente para verificar la eliminación. | 12. La sección de información fiscal muestra únicamente el registro original (MACL850101AB1). El segundo registro ya no existe. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
