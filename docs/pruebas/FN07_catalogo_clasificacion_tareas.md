# FN7. Catálogo de clasificación de tareas

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN7 – Catálogo de Clasificación de Tareas                 |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 23:36 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de las categorías que clasifican las tareas dentro de la empresa.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen 6 categorías activas registradas en la BD de pruebas (ver Tabla 7).

**Entradas:**
Nueva categoría: Nombre: "Consultoría Estratégica", Descripción: "Asesoría y consultoría en estrategia de negocio".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /categorias. | 1. Se visualiza el listado de categorías activas con las 6 categorías de la BD de pruebas (Diseño Gráfico, Desarrollo Web, Redes Sociales, Marketing Digital, Fotografía y Video, Redacción). | 1. El sistema le permite al usuario administrador crear, leer, actualizar y desactivar categorías de clasificación de tareas. |
| 2. Verificar que se muestran las categorías con su nombre, descripción y estado (activo). | 2. Las 6 categorías activas se visualizan con sus datos completos. | |
| **CREAR** | | |
| 3. Clic en "Nueva Categoría". | 3. Se abre el formulario de registro con los campos: nombre y descripción. | |
| 4. Ingresar: Nombre "Consultoría Estratégica", Descripción "Asesoría y consultoría en estrategia de negocio". | 4. Los campos muestran los valores ingresados correctamente. | |
| 5. Clic en "Guardar". | 5. Crea la categoría con estado activo, redirige al listado y muestra mensaje "Categoría registrada correctamente." | |
| **LEER** | | |
| 6. En el listado, verificar que aparece "Consultoría Estratégica" con su descripción y estado activo. | 6. La nueva categoría se muestra en la lista con todos sus datos. | |
| **ACTUALIZAR** | | |
| 7. Clic en "Editar" de la categoría "Consultoría Estratégica". | 7. Se abre el formulario de edición con los datos precargados. | |
| 8. Cambiar Nombre a "Consultoría y Estrategia" y Descripción a "Asesoría en estrategia de negocio y marketing". Clic en "Guardar". | 8. Guarda los cambios, redirige al listado y muestra mensaje "Categoría actualizada correctamente." | |
| 9. Verificar en el listado que la categoría muestra el nuevo nombre "Consultoría y Estrategia" y la descripción actualizada. | 9. Los datos actualizados se reflejan correctamente en el listado. | |
| **ELIMINAR** | | |
| 10. Clic en "Desactivar" de la categoría "Consultoría y Estrategia". | 10. El sistema cambia el estado a inactivo. Redirige al listado y muestra mensaje "Categoría desactivada correctamente." | |
| 11. Cambiar el filtro de estatus a "inactivo" y verificar que "Consultoría y Estrategia" aparece con estado inactivo. | 11. La categoría se muestra en la lista de inactivas. Ya no aparece en la lista de activas. | |
| 12. Clic en "Reactivar" de la categoría "Consultoría y Estrategia". | 12. El sistema cambia el estado a activo. Redirige al listado y muestra mensaje "Categoría reactivada correctamente." La categoría vuelve a la lista de activas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
