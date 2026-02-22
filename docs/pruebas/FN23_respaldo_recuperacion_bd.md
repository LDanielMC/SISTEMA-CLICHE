# FN23. Respaldo y recuperación de base de datos

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN23 – Respaldo y Recuperación de Base de Datos           |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 12 de febrero de 2026, 03:17 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite generar respaldos completos de la base de datos en formato SQL, listar el historial de respaldos con sus metadatos, descargar archivos de respaldo, restaurar la base de datos a un punto anterior y eliminar archivos de respaldo del servidor.

**Condiciones de ejecución:**
Sesión iniciada como administrador.
Servidor MySQL activo con acceso a mysqldump y mysql. Directorio de almacenamiento con permisos de escritura.

**Entradas:**
Archivo de respaldo generado automáticamente con nombre "backup-[fecha]-[hora].sql".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /admin/backups. | 1. Se muestra la vista "Administración de Respaldos" con la sección "Copias de Seguridad de la Base de Datos", el botón "Generar Nuevo Respaldo" y el historial de respaldos existentes en una tabla con columnas: Archivo, Fecha y Hora, Tamaño y Acciones. | 1. El sistema le permite al usuario administrador generar respaldos completos de la base de datos, visualizar el historial de copias de seguridad, descargar archivos SQL, restaurar la base de datos a un punto anterior y eliminar archivos de respaldo del servidor. |
| **CREAR RESPALDO** | | |
| 2. Clic en "Generar Nuevo Respaldo". | 2. Se abre un modal de confirmación indicando que se creará un archivo SQL con el estado actual de toda la base de datos. | |
| 3. Clic en "Confirmar" dentro del modal. | 3. El sistema genera el respaldo mediante mysqldump. Se muestra el mensaje "Respaldo generado exitosamente: backup-[fecha].sql". El archivo aparece en la tabla del historial con su nombre, fecha, tamaño y acciones disponibles. | |
| **LEER HISTORIAL** | | |
| 4. Verificar que la tabla muestra el respaldo recién creado y los anteriores, ordenados por fecha descendente. | 4. Cada fila muestra el nombre del archivo (formato backup-YYYY-MM-DD-HH-mm-ss.sql), la fecha y hora de creación, el tamaño del archivo y los botones de acción: Descargar, Restaurar y Eliminar. | |
| **DESCARGAR RESPALDO** | | |
| 5. Clic en el ícono de descarga del respaldo recién creado. | 5. Se descarga el archivo .sql al equipo local. El archivo contiene las sentencias SQL completas de la base de datos. | |
| **RESTAURAR BASE DE DATOS** | | |
| 6. Clic en el ícono de restaurar del respaldo recién creado. | 6. Se abre un modal de advertencia con borde amarillo indicando: el nombre del archivo seleccionado, que toda la información actual será eliminada y sobrescrita, que los cambios posteriores al respaldo se perderán, y que la acción no se puede deshacer. | |
| 7. Clic en "Sí, Restaurar Base de Datos". | 7. El sistema ejecuta la restauración mediante el cliente mysql. Se muestra el mensaje "Base de datos restaurada exitosamente desde: backup-[fecha].sql". Los datos de la BD corresponden al estado del momento del respaldo. | |
| **ELIMINAR RESPALDO** | | |
| 8. Clic en el ícono de eliminar de un respaldo existente. | 8. Se abre un modal de confirmación preguntando "¿Eliminar Respaldo?" indicando que el archivo será eliminado permanentemente del servidor. | |
| 9. Clic en "Eliminar". | 9. El archivo se elimina del servidor. Se muestra el mensaje "Respaldo eliminado correctamente." El archivo ya no aparece en la tabla del historial. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
