# FN13. Briefs para clientes (Gestión del formulario)

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN13 – Briefs para clientes (Gestión del formulario)      |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 02:20 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de los formularios de brief, ya sea creando un nuevo formulario en Google Forms desde el sistema o vinculando uno existente.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen briefs registrados en la BD de pruebas (ver Tabla 18).
Google Forms está autenticado para la creación de formularios.

**Entradas:**
Nuevo brief creado desde el sistema: Título: "Brief de Redes Sociales 2026", Descripción: "Cuestionario para conocer las necesidades del cliente en redes sociales", Preguntas: 1) "Correo electrónico" (texto corto, requerido), 2) "¿Cuáles son tus redes sociales actuales?" (párrafo), 3) "¿Con qué frecuencia publicas contenido?" (opción múltiple: Diario, Semanal, Mensual, Nunca).

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /briefs. | 1. Se visualiza el listado de briefs con los formularios de la BD de pruebas: "Brief Inicial" y "Formulario de Información para Sesión de Fotos", mostrando título y cantidad de clientes asignados. | 1. El sistema le permite al usuario administrador crear formularios de brief en Google Forms, vincular formularios existentes, consultar, actualizar y eliminar briefs del catálogo. |
| **LEER** | | |
| 2. Clic en "Ver" del brief "Brief Inicial". | 2. Se muestra el detalle del brief con su título, descripción, enlace al formulario de Google Forms, las preguntas del formulario y las respuestas recibidas. | |
| **CREAR** | | |
| 3. Clic en "Nuevo Brief". Seleccionar la opción "Crear nuevo formulario". Ingresar título "Brief de Redes Sociales 2026" y descripción según las entradas. | 3. Se muestra el formulario con campos de título, descripción y la sección para agregar preguntas con tipos: texto corto, párrafo y opción múltiple. | |
| 4. Agregar las 3 preguntas según las entradas: correo electrónico (texto corto, requerido), redes sociales actuales (párrafo) y frecuencia de publicación (opción múltiple con opciones: Diario, Semanal, Mensual, Nunca). | 4. Las preguntas se agregan correctamente al formulario con sus tipos y opciones configuradas. | |
| 5. Clic en "Guardar". | 5. Se crea el formulario en Google Forms con las preguntas configuradas. Se registra en el sistema con el google_form_id y la URL del formulario. Redirige al listado con mensaje "Nuevo formulario creado en Google y registrado exitosamente." | |
| 6. Verificar en el listado que aparece "Brief de Redes Sociales 2026". | 6. El nuevo brief se muestra en la lista con su título y 0 clientes asignados. | |
| **ACTUALIZAR** | | |
| 7. Clic en "Editar" del brief "Brief de Redes Sociales 2026". Cambiar título a "Brief de Redes Sociales y Marketing 2026". | 7. Se abre el formulario de edición con título, descripción y URL precargados. | |
| 8. Clic en "Guardar". | 8. El brief se actualiza en el sistema. Redirige al listado con mensaje "Formulario actualizado exitosamente." | |
| **ELIMINAR** | | |
| 9. Clic en "Eliminar" del brief "Brief de Redes Sociales y Marketing 2026". Confirmar la eliminación. | 9. El brief se elimina del sistema. Redirige al listado con mensaje "Formulario eliminado exitosamente." | |
| 10. Verificar en el listado que el brief eliminado ya no aparece. | 10. El brief ya no existe. Solo se muestran los 2 briefs originales de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
