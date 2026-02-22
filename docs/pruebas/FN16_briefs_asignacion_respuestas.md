# FN16. Briefs para clientes

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN16 – Briefs para clientes (Asignación y Respuestas)    |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 02:20 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite asignar briefs a clientes, enviar notificaciones y correos de asignación, consultar el estado de las respuestas obtenidas desde Google Forms, vincular respuestas automática o manualmente, y solicitar nuevas respuestas.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen briefs, clientes y asignaciones en la BD de pruebas (ver Tablas 18, 19 y 3).
Google Forms está autenticado para la lectura de respuestas.

**Entradas:**
Asignación: Brief "Brief Inicial" al cliente "RESTAURANTE CAMPESTRE HUITZILAC". El cliente tiene cuenta de usuario con correo registrado en el sistema.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /briefs. Clic en "Ver" del brief "Brief Inicial". | 1. Se muestra el detalle del brief con título, descripción, enlace al formulario, preguntas y respuestas recibidas desde Google Forms. | 1. El sistema le permite al usuario administrador asignar briefs a clientes, enviar notificaciones por correo, consultar respuestas de Google Forms, vincular respuestas de forma automática o manual, y solicitar nuevas respuestas. |
| **ASIGNAR BRIEF A CLIENTE** | | |
| 2. Clic en "Asignar Clientes" del brief "Brief Inicial". | 2. Se abre la vista de asignación mostrando la lista de clientes activos disponibles (que aún no tienen asignado este brief) y la lista de clientes ya asignados con su estado (pendiente/recibido). Se muestra la asignación existente de "LOS CALLOS DE CORTES" con estado "pendiente". | |
| 3. Seleccionar cliente "RESTAURANTE CAMPESTRE HUITZILAC" y clic en "Asignar". | 3. El cliente se asigna al brief con estado "pendiente" y fecha de envío actual. Se envía un correo electrónico al cliente con el enlace al formulario. Se crea una notificación en el sistema para el cliente. Muestra mensaje "Brief asignado correctamente al cliente." | |
| **VERIFICAR NOTIFICACIÓN Y CORREO** | | |
| 4. Verificar que el cliente recibió el correo electrónico con el enlace al formulario de Google Forms. | 4. El correo llega a la bandeja de entrada del cliente con el asunto de asignación del brief y el enlace directo al formulario. | |
| **CONSULTAR ESTADO DE RESPUESTAS** | | |
| 5. En la vista de asignación, verificar que "RESTAURANTE CAMPESTRE HUITZILAC" aparece con estado "pendiente". | 5. El cliente aparece en la lista de asignados con estado "pendiente", fecha de envío y sin respuesta vinculada. | |
| 6. (Cliente) El cliente accede al enlace del formulario y responde las preguntas del brief en Google Forms. | 6. La respuesta se registra en Google Forms con el correo del cliente. | |
| **VINCULACIÓN AUTOMÁTICA DE RESPUESTA** | | |
| 7. (Admin) Clic en "Ver Respuestas" del cliente "RESTAURANTE CAMPESTRE HUITZILAC". | 7. El sistema consulta las respuestas de Google Forms, detecta la respuesta del cliente por coincidencia de correo electrónico y la vincula automáticamente. El estado cambia a "recibido". Se muestran las respuestas del cliente con las preguntas y sus respuestas correspondientes. | |
| **SOLICITAR NUEVA RESPUESTA** | | |
| 8. Clic en "Solicitar Nueva Respuesta" del cliente "RESTAURANTE CAMPESTRE HUITZILAC". | 8. El estado se reinicia a "pendiente", se elimina la vinculación anterior, se envía nuevo correo y notificación al cliente. Muestra mensaje "Se ha solicitado una nueva respuesta al cliente. Se reinició el estado y se envió notificación." | |
| **DESVINCULAR Y VINCULAR MANUALMENTE** | | |
| 9. (Cliente) El cliente responde nuevamente el formulario con un correo diferente. (Admin) Clic en "Ver Respuestas". La respuesta no se vincula automáticamente, aparece en "Posibles Coincidencias". | 9. Se muestran las respuestas candidatas filtradas por fecha posterior al reenvío. El administrador puede identificar la respuesta correcta. | |
| 10. Clic en "Vincular" de la respuesta correspondiente. | 10. La respuesta se vincula al cliente. El estado cambia a "recibido". Muestra mensaje "Respuesta vinculada correctamente al cliente." | |
| **ELIMINAR ASIGNACIÓN** | | |
| 11. Clic en "Eliminar asignación" del cliente "RESTAURANTE CAMPESTRE HUITZILAC". | 11. Se elimina la asignación del brief al cliente y las notificaciones asociadas. Muestra mensaje "Asignación eliminada correctamente." | |
| 12. Verificar que el cliente ya no aparece en la lista de asignados del brief. | 12. Solo se muestran las asignaciones originales de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
