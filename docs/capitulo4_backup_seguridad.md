# CAPÍTULO 4 — SEGURIDAD EN EL MÓDULO DE RESPALDO Y RESTAURACIÓN

---

## 4.X PRUEBAS DE ESCENARIOS DE SEGURIDAD EN RESPALDO Y RESTAURACIÓN

El módulo de respaldo y restauración de la base de datos implementa múltiples capas de validación para garantizar la integridad de la información y restringir el acceso no autorizado. A continuación, se documentan las pruebas realizadas sobre los escenarios de riesgo identificados.

---

### ESCENARIO 1: Restauración con archivo incorrecto (no SQL)

Para verificar el comportamiento del sistema ante un archivo que no corresponde a un respaldo de base de datos, se intentó restaurar un archivo con contenido de texto plano, sin estructura SQL, renombrado con extensión `.sql`.

El sistema detecta en la primera línea del archivo si el contenido corresponde a un volcado generado por `mysqldump` (el cual siempre inicia con comentarios `--` o directivas `/*!`). Al no encontrar estos indicadores, interrumpe el proceso antes de ejecutar cualquier comando en la base de datos y muestra un mensaje descriptivo al administrador.

> 📸 **[CAPTURA AQUÍ — Escenario 1]**
> Abre el módulo de Respaldos, selecciona el archivo `backup-invalido.sql` de la lista y haz clic en "Restaurar Base de Datos". Captura la pantalla completa mostrando el mensaje de error en rojo.

**Pie de figura:**
*Figura 4.X. Mensaje de error al intentar restaurar un archivo que no es un respaldo SQL válido.*

**Descripción para el documento:**
En la figura 4.X se muestra la respuesta del sistema ante el intento de restaurar un archivo con contenido inválido. El sistema valida el formato del archivo antes de ejecutar cualquier operación sobre la base de datos: verifica que el archivo no esté vacío y que su contenido corresponda a la estructura de un volcado SQL generado por `mysqldump`. Al detectar que el archivo no cumple estas condiciones, el proceso se interrumpe y se notifica al administrador con el mensaje: *"El archivo no es un respaldo SQL válido. Verifique que el archivo fue generado por este sistema y que no está dañado o es de un formato diferente."*

---

### ESCENARIO 2: Intento de acceso sin permisos de administrador

El módulo de respaldos está protegido mediante el middleware `['auth', 'admin']` registrado en `bootstrap/app.php`. Esto garantiza que únicamente los usuarios con rol `admin` puedan acceder a las funciones de creación, descarga, restauración y eliminación de respaldos.

Para verificar este comportamiento, se inició sesión con un usuario de rol `empleado` y se intentó acceder directamente a la URL del módulo de respaldos.

> 📸 **[CAPTURA AQUÍ — Escenario 2]**
> 1. Cierra sesión como administrador.
> 2. Inicia sesión con una cuenta de empleado.
> 3. Escribe directamente en el navegador: `http://127.0.0.1:8000/admin/backups`
> 4. Captura la pantalla con el error 403 que aparece.

**Pie de figura:**
*Figura 4.X. Error HTTP 403 al intentar acceder al módulo de respaldos con un rol no autorizado.*

**Descripción para el documento:**
En la figura 4.X se muestra la respuesta del sistema al intentar acceder al módulo de respaldos con un usuario que no posee el rol de administrador. El middleware `IsAdmin`, configurado como alias `admin` en `bootstrap/app.php`, intercepta la petición, verifica el campo `rol` del usuario autenticado y, al no corresponder a `admin`, interrumpe la ejecución retornando un error HTTP 403 (Prohibido) con el mensaje *"No tienes permiso para acceder a esta sección."* Este control aplica tanto a la vista del módulo como a todas las rutas de acción (crear, restaurar, descargar, eliminar).

---

### ESCENARIO 3: Restauración de respaldo corrupto o incompleto

Para verificar el comportamiento ante un archivo SQL con contenido dañado o incompleto, se tomó un respaldo real generado por el sistema, se eliminó la mitad de su contenido SQL y se intentó restaurar el archivo resultante.

A diferencia del escenario 1, este archivo sí inicia con la estructura correcta de un volcado SQL (pasa la validación de formato), pero al ejecutarse presenta errores de sintaxis. MySQL detecta los errores durante la ejecución y los reporta en el output del proceso. El sistema captura esta respuesta, identifica el tipo de error y muestra un mensaje descriptivo.

> 📸 **[CAPTURA AQUÍ — Escenario 3]**
> 1. Genera un respaldo normal desde el módulo.
> 2. Descárgalo con el botón de descarga.
> 3. Ábrelo con un editor de texto y elimina los últimos 30–40% de líneas (asegúrate de que las primeras líneas con `--` o `/*!` queden intactas).
> 4. Guárdalo con un nombre diferente (ej. `backup-corrupto.sql`) y cópialo a `storage/app/private/backups/`.
> 5. Reinicia el servidor y restaura ese archivo desde el módulo.
> 6. Captura la pantalla con el mensaje de error.

**Pie de figura:**
*Figura 4.X. Mensaje de error al intentar restaurar un respaldo SQL con contenido corrupto o incompleto.*

**Descripción para el documento:**
En la figura 4.X se muestra la respuesta del sistema al intentar restaurar un archivo de respaldo que presenta errores de sintaxis SQL, producto de estar incompleto o dañado. El sistema pasa el archivo a MySQL para su ejecución y analiza el output resultante: al detectar la cadena `ERROR` o mensajes de sintaxis inválida en la respuesta, interrumpe la operación e informa al administrador con el mensaje: *"El archivo contiene errores de sintaxis SQL. El respaldo puede estar incompleto o corrupto."*

---

### ESCENARIO 4: Pérdida de conexión durante el proceso

**Este escenario no requiere captura. Se documenta con justificación técnica.**

**Texto para el documento:**
La pérdida de conexión con la base de datos durante un proceso de respaldo o restauración no representa un escenario viable en el entorno del sistema. Tanto en el entorno de desarrollo como en producción, el servidor MySQL se ejecuta en la misma máquina que la aplicación (`127.0.0.1`), por lo que una desconexión con la base de datos equivaldría a una falla total del servidor, situación que excede el alcance del sistema. 

En el supuesto de que `mysqldump` se interrumpiera por cualquier causa externa, el archivo resultante quedaría vacío o con tamaño cero. El sistema detecta esta condición durante la creación del respaldo mediante la verificación `filesize($path) > 0`: si el archivo está vacío, se elimina automáticamente y se informa al administrador con un mensaje de error, evitando que quede registrado un respaldo inútil o dañado en el historial.

---

### ESCENARIO 5: Verificación de integridad posterior a la restauración

**Este escenario no requiere captura. Se documenta con justificación técnica.**

**Texto para el documento:**
La verificación de integridad tras una restauración exitosa se realiza de forma funcional: inmediatamente después de completarse el proceso, el sistema redirige al administrador al módulo de respaldos con un mensaje de confirmación. A partir de ese momento, el administrador puede navegar por cualquier módulo del sistema para confirmar que los datos han sido restaurados correctamente, ya que todas las vistas consumen información directamente de la base de datos restaurada.

El sistema evalúa el output del proceso MySQL durante la restauración y únicamente considera la operación como exitosa cuando la respuesta no contiene mensajes de error. En caso contrario, muestra un mensaje descriptivo clasificado por tipo de fallo (sintaxis SQL, permisos, base de datos inexistente), permitiendo al administrador identificar la causa y tomar las acciones correspondientes.

---

## 4.X DATOS DE PRUEBA EN LA BASE DE DATOS

**Texto para el documento:**

Los datos registrados en la base de datos durante la fase de desarrollo y pruebas son de carácter ficticio y fueron creados exclusivamente para validar el funcionamiento de los módulos del sistema. Los correos electrónicos, nombres, empresas y demás datos personales utilizados no corresponden a personas físicas reales ni fueron obtenidos de ninguna fuente externa.

Los correos utilizados en las pruebas siguen convenciones claramente identificables como datos de prueba, por ejemplo: `empleado@cliche.com`, `cliente@empresa.mx`, `admin@clichemarketing.com`. Ningún dato personal real fue incorporado al sistema durante el desarrollo ni durante la documentación de las pruebas funcionales presentadas en este capítulo.

---

## RESUMEN DE CAPTURAS — MÓDULO DE RESPALDOS

| # | Escenario | Cómo provocarlo | Captura requerida |
|---|-----------|----------------|-------------------|
| 1 | **Archivo incorrecto** | Restaurar `backup-invalido.sql` (ya está en el sistema) | ✅ Sí |
| 2 | **Sin permisos** | Acceder a `/admin/backups` con cuenta de empleado | ✅ Sí |
| 3 | **Respaldo corrupto** | Cortar un `.sql` real a la mitad y restaurarlo | ✅ Sí |
| 4 | **Pérdida de conexión** | No aplica — BD en localhost | 📝 Solo texto |
| 5 | **Verificación de integridad** | Funcional post-restauración | 📝 Solo texto |
| 6 | **Datos de prueba** | Son ficticios | 📝 Solo texto |
