# FN1. Inicio de sesión

---

## DATOS GENERALES DE LA PRUEBA

| Campo              | Detalle                                                        |
|--------------------|----------------------------------------------------------------|
| **Requisito a probar** | FN1 – Inicio de Sesión                                     |
| **Ejecutor**       | Luis Daniel Moctezuma Campuzano                                |
| **Fecha y Hora**   | 11 de febrero de 2026, 16:50 hrs                               |
| **Evaluador**      | Dra. Deny Lizbeth Hernández Rabadan                            |

---

## DESARROLLO

**Objetivo:**
Verificar que el sistema permite el inicio de sesión con credenciales válidas para los tres roles (administrador, empleado y cliente), redirigiendo a cada usuario a su dashboard correspondiente, y que permite cerrar sesión correctamente.

**Condiciones de ejecución:**
No hay sesión activa en el navegador.
Existen usuarios registrados con los tres roles en la BD de pruebas (ver Tabla 1 de la BD de pruebas).

**Entradas:**
Credenciales de administrador: luisd.m.c2002@gmail.com. Credenciales de empleado: luisd.m.c2002+E1@gmail.com. Credenciales de cliente: luisd.m.c2002+CALLOS@gmail.com. Contraseña válida para todos los usuarios.

---

## TABLA DE ACCIONES Y RESULTADOS

| Acciones | Resultados esperados | Resultados obtenidos |
|----------|----------------------|----------------------|
| 1. Navegar a /login. | 1. Se muestra el formulario de inicio de sesión con los campos: correo electrónico, contraseña, casilla "Recordarme" y botón "Iniciar sesión". | 1. El sistema permite iniciar sesión con los tres roles del sistema y redirige al dashboard correspondiente según el rol del usuario. |
| **INICIO DE SESIÓN – ADMINISTRADOR** | | |
| 2. Ingresar correo: "luisd.m.c2002@gmail.com" y contraseña válida. Clic en "Iniciar sesión". | 2. El sistema autentica al usuario, regenera la sesión y redirige al dashboard de administrador (/admin/dashboard). | |
| 3. Verificar que se muestra el dashboard de administrador con el menú completo del sistema. | 3. Se visualiza el panel de administración con acceso a todos los módulos del sistema. | |
| 4. Clic en "Cerrar sesión". | 4. El sistema invalida la sesión y redirige a la página de inicio (/). | |
| **INICIO DE SESIÓN – EMPLEADO** | | |
| 5. Navegar a /login. Ingresar correo: "luisd.m.c2002+E1@gmail.com" y contraseña válida. Clic en "Iniciar sesión". | 5. El sistema autentica al empleado y redirige al dashboard de empleado (/empleado/dashboard). | |
| 6. Verificar que se muestra el dashboard de empleado con los módulos correspondientes a su rol. | 6. Se visualiza el panel de empleado con acceso limitado a los módulos asignados. | |
| 7. Clic en "Cerrar sesión". | 7. El sistema invalida la sesión y redirige a la página de inicio (/). | |
| **INICIO DE SESIÓN – CLIENTE** | | |
| 8. Navegar a /login. Ingresar correo: "luisd.m.c2002+CALLOS@gmail.com" y contraseña válida. Clic en "Iniciar sesión". | 8. El sistema autentica al cliente y redirige al dashboard de cliente (/cliente/dashboard). | |
| 9. Verificar que se muestra el dashboard de cliente con los módulos correspondientes a su rol. | 9. Se visualiza el panel de cliente con acceso a sus proyectos, cotizaciones y documentos. | |
| 10. Clic en "Cerrar sesión". | 10. El sistema invalida la sesión y redirige a la página de inicio (/). | |

---

## EVALUACIÓN FINAL

**Evaluación de la prueba:**
La prueba se realizó de manera satisfactoria.

**Acciones correctivas:**
Ninguna.
