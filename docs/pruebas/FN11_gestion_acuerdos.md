# FN11. Gestión de los acuerdos

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN11 – Gestión de Acuerdos                                 |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 02:30 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite realizar el ciclo de vida completo (Crear, Leer, Actualizar y Eliminar) de un acuerdo dentro de una minuta existente.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Existe minuta id=11 (Los Callos de Cortes) con acuerdos previos completados.

**Entradas:**
Minuta ID 11. Nuevo acuerdo: "Diseñar plantilla de menú digital para redes sociales", Responsable: "Juan Carlos Pérez", Estatus: Pendiente, Fecha: 2026-03-15.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /minutas. | 1. Se visualiza el listado. Se ve la minuta #11 "Reunión inicial Los Callos de Cortes". | 1. El sistema le permite al usuario administrador crear, leer, actualizar y eliminar acuerdos. |
| 2. Hacer clic en "Editar" de la minuta #11. | 2. Se abre formulario con datos generales y tabla de acuerdos con 2 filas existentes. | |
| **CREAR** | | |
| 3. Clic en "+ Agregar Acuerdo". | 3. Se agrega una nueva fila vacía al final de la tabla de acuerdos. | |
| 4. Ingresar: "Diseñar plantilla...", "Juan Carlos Pérez", "Pendiente", "2026-03-15". | 4. Los campos de la fila 3 muestran los valores ingresados. | |
| 5. Clic en "Guardar Cambios". | 5. Guarda cambios, redirige al listado y muestra mensaje de éxito. | |
| **LEER** | | |
| 6. Clic en "Editar" minuta #11 nuevamente. | 6. La tabla muestra 3 filas. La fila 3 contiene los datos del acuerdo recién creado (persistidos). | |
| **ACTUALIZAR** | | |
| 7. En fila 3, cambiar Estatus a "Completado" y Fecha a "2026-03-10". | 7. Campos se actualizan. La fila adquiere estilo de fondo degradado (completado). | |
| 8. Clic en "Guardar Cambios". | 8. Guarda cambios en BD, redirige y muestra mensaje de éxito. | |
| 9. Clic en "Editar" minuta #11 para verificar actualización. | 9. La fila 3 refleja los cambios guardados: estatus "Completado" y fecha límite "2026-03-10". | |
| **ELIMINAR** | | |
| 10. Clic en botón "Eliminar" (✕) en la fila 3. | 10. La fila 3 se elimina visualmente de la tabla. Solo quedan 2 filas. | |
| 11. Clic en "Guardar Cambios". | 11. Guarda eliminación en BD, redirige y muestra mensaje de éxito. | |
| 12. Clic en "Editar" minuta #11 para verificar eliminación. | 12. La tabla muestra únicamente las 2 filas originales. El acuerdo ya no existe en BD. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
