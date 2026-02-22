# FN1. Recuperación de contraseña

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN1 – Inicio de Sesión (Recuperación de contraseña)        |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 16:55 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite al usuario solicitar un enlace de restablecimiento de contraseña, recibir el correo electrónico, establecer una nueva contraseña e iniciar sesión con ella.

**Condiciones de ejecución:**
No hay sesión activa en el navegador.
El usuario administrador (luisd.m.c2002@gmail.com) existe en la BD de pruebas (ver Tabla 1).
El servicio de correo electrónico está configurado correctamente en el servidor.

**Entradas:**
Correo del usuario: luisd.m.c2002@gmail.com. Nueva contraseña: "NuevaPass2026!".

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /login. | 1. Se muestra el formulario de inicio de sesión. | 1. El sistema permite al usuario recuperar su contraseña mediante correo electrónico y establecer una nueva contraseña exitosamente. |
| 2. Clic en el enlace "¿Olvidaste tu contraseña?". | 2. Se muestra el formulario de solicitud de restablecimiento (/forgot-password) con el campo de correo electrónico. | |
| 3. Ingresar correo: "luisd.m.c2002@gmail.com". Clic en "Enviar enlace". | 3. El sistema envía un correo con el enlace de restablecimiento y muestra el mensaje de confirmación. | |
| 4. Abrir la bandeja de entrada del correo y localizar el email de restablecimiento. | 4. Se recibe un correo electrónico con un enlace válido para restablecer la contraseña. | |
| 5. Clic en el enlace de restablecimiento del correo. | 5. Se abre el formulario de nueva contraseña (/reset-password/{token}) con los campos: correo, nueva contraseña y confirmación de contraseña. | |
| 6. Ingresar nueva contraseña: "NuevaPass2026!" y confirmar contraseña: "NuevaPass2026!". Clic en "Restablecer contraseña". | 6. El sistema actualiza la contraseña en la BD, redirige a /login y muestra mensaje de éxito indicando que la contraseña fue restablecida. | |
| 7. En /login, ingresar correo: "luisd.m.c2002@gmail.com" y contraseña: "NuevaPass2026!". Clic en "Iniciar sesión". | 7. El sistema autentica al usuario con la nueva contraseña y redirige al dashboard de administrador. | |
| 8. Clic en "Cerrar sesión". | 8. El sistema invalida la sesión y redirige a la página de inicio. | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
