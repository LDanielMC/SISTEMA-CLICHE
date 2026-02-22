# FN8. Gestión de tareas

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN8 – Gestión de Tareas                                   |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 23:38 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de las tareas internas, utilizando las categorías del catálogo y asociándolas a un cliente.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen tareas, clientes y categorías registradas en la BD de pruebas (ver Tablas 8, 3 y 7).

**Entradas:**
Nueva tarea: Título: "Rediseño de logotipo", Cliente: "LOS CALLOS DE CORTES", Categoría: "Diseño Gráfico", Descripción: "Rediseñar el logotipo principal del restaurante con nueva paleta de colores", Observaciones: "El cliente solicita un estilo más moderno".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /tareas. | 1. Se visualiza el listado de tareas con las 3 tareas de la BD de pruebas (Diseño de menú digital, Sesión fotográfica mariscos, Fotografía de instalaciones). | 1. El sistema le permite al usuario administrador crear, leer, actualizar y eliminar tareas internas asociadas a un cliente y una categoría. |
| 2. Verificar que se muestran las tareas con su título, cliente, categoría, descripción y fecha de creación. | 2. Las 3 tareas de la BD de pruebas se visualizan con sus datos completos. | |
| **CREAR** | | |
| 3. Clic en "Nueva Tarea". | 3. Se abre el formulario con campos: título, cliente (búsqueda dinámica), categoría (búsqueda dinámica), descripción y observaciones. | |
| 4. Ingresar: Título "Rediseño de logotipo", buscar y seleccionar cliente "LOS CALLOS DE CORTES", buscar y seleccionar categoría "Diseño Gráfico", Descripción "Rediseñar el logotipo principal del restaurante con nueva paleta de colores", Observaciones "El cliente solicita un estilo más moderno". | 4. Los campos muestran los valores ingresados. El cliente y la categoría se seleccionan mediante búsqueda dinámica. | |
| 5. Clic en "Guardar". | 5. Crea la tarea con fecha de creación asignada por el sistema, redirige al listado y muestra mensaje "Tarea creada exitosamente." | |
| **LEER** | | |
| 6. En el listado, verificar que aparece "Rediseño de logotipo" con cliente "LOS CALLOS DE CORTES", categoría "Diseño Gráfico" y fecha de creación del día actual. | 6. La nueva tarea se muestra en la lista con todos sus datos correctos. | |
| **ACTUALIZAR** | | |
| 7. Clic en "Editar" de la tarea "Rediseño de logotipo". | 7. Se abre el formulario de edición con los datos precargados: título, cliente, categoría, descripción y observaciones. | |
| 8. Cambiar Título a "Rediseño de logotipo y papelería" y Descripción a "Rediseñar logotipo y diseñar papelería corporativa completa". Clic en "Guardar". | 8. Guarda los cambios, redirige al listado y muestra mensaje "Tarea actualizada exitosamente." | |
| 9. Verificar en el listado que la tarea muestra el nuevo título "Rediseño de logotipo y papelería". | 9. Los datos actualizados se reflejan correctamente en el listado. | |
| **ELIMINAR** | | |
| 10. Clic en "Eliminar" de la tarea "Rediseño de logotipo y papelería". | 10. El sistema elimina la tarea de la base de datos. Redirige al listado y muestra mensaje "Tarea eliminada exitosamente." | |
| 11. Verificar en el listado que la tarea eliminada ya no aparece. | 11. La tarea ya no existe en el sistema. Solo se muestran las 3 tareas originales de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
