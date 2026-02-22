# FN15. Agenda

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN15 – Agenda (Calendario de Eventos)                     |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 01:56 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite gestionar el calendario de eventos: crear eventos con participantes, recordatorios, recurrencia y sincronización con Google Calendar, así como visualizarlos en un calendario interactivo, actualizarlos y eliminarlos.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existen eventos y clientes registrados en la BD de pruebas (ver Tablas 20 y 3).

**Entradas:**
Nuevo evento: Título: "Sesión fotográfica menú primavera", Cliente: "LOS CALLOS DE CORTES", Fecha: 2026-03-20, Hora inicio: 10:00, Hora fin: 13:00, Lugar: "Juan Pablo II Col Vista Hermosa", Notas: "Llevar equipo de iluminación para interiores", Color: azul (#3B82F6), Recurrencia: ninguna. Participante: nombre "Juan Carlos Pérez", correo "luisd.m.c2002+E1@gmail.com". Recordatorio: 30 minutos antes.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /eventos. | 1. Se visualiza el calendario de eventos (FullCalendar) con los eventos de la BD de pruebas: "Sesión fotos Los Callos", "Visita Campestre" y "Reunión planificación 2026". Se muestra la lista de próximos eventos. | 1. El sistema le permite al usuario administrador crear, visualizar, actualizar y eliminar eventos en el calendario, incluyendo participantes, recordatorios, recurrencia y sincronización con Google Calendar. |
| **LEER** | | |
| 2. Clic en el evento "Reunión planificación 2026" en el calendario. | 2. Se muestra el detalle del evento con título, cliente ("LOS CALLOS DE CORTES"), fecha (2026-01-20), hora inicio (10:00), hora fin (12:00), lugar, notas y color. | |
| **CREAR** | | |
| 3. Clic en "Nuevo Evento". Ingresar título "Sesión fotográfica menú primavera", seleccionar cliente "LOS CALLOS DE CORTES", fecha 2026-03-20, hora inicio 10:00, hora fin 13:00, lugar "Juan Pablo II Col Vista Hermosa", notas según las entradas y color azul. | 3. El formulario muestra los campos correctamente con selectores de cliente, fecha, hora y color. | |
| 4. Agregar participante: nombre "Juan Carlos Pérez", correo "luisd.m.c2002+E1@gmail.com". Agregar recordatorio: 30 minutos antes. | 4. El participante y el recordatorio se agregan correctamente a las listas del formulario. | |
| 5. Clic en "Guardar". | 5. El evento se crea exitosamente. Aparece en el calendario en la fecha 2026-03-20 con color azul. Se muestra en la lista de próximos eventos. | |
| **SINCRONIZACIÓN CON GOOGLE CALENDAR** | | |
| 6. Verificar que el sistema intenta sincronizar el evento con Google Calendar. | 6. Si Google Calendar está conectado, el evento se sincroniza automáticamente: se crea en Google Calendar con el mismo título, fecha, horario, lugar y participantes. El participante recibe una invitación por correo electrónico desde Google Calendar. | |
| **LEER** | | |
| 7. Clic en el evento "Sesión fotográfica menú primavera" en el calendario. | 7. Se muestra el detalle completo: título, cliente, fecha, horario, lugar, notas, participante (Juan Carlos Pérez) y recordatorio (30 min antes). | |
| **ACTUALIZAR** | | |
| 8. Clic en "Editar" del evento "Sesión fotográfica menú primavera". Cambiar hora fin a 14:00 y agregar en notas "Incluir fotos de bebidas". | 8. Se abre el formulario de edición con los datos precargados. | |
| 9. Clic en "Guardar". | 9. El evento se actualiza correctamente. La hora fin cambia a 14:00 y las notas reflejan el cambio. Si está sincronizado con Google Calendar, se actualiza también en Google. | |
| **ELIMINAR** | | |
| 10. Clic en "Eliminar" del evento "Sesión fotográfica menú primavera". Confirmar la eliminación. | 10. El evento se elimina del calendario. Si estaba sincronizado con Google Calendar, también se elimina de Google. | |
| 11. Verificar en el calendario que el evento eliminado ya no aparece en la fecha 2026-03-20. | 11. El evento ya no existe. Solo se visualizan los 3 eventos originales de la BD de pruebas. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
