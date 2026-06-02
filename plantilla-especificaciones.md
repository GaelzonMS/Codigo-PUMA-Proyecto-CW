#### 1. INFORMACIÓN INICIAL
- **Autor:** Codigo PUMA
- **Título del proyecto:** ETECHelp
- **Fecha de inicio:** 27 de mayo de 2026

#### 2. RESUMEN DEL PROYECTO, METAS Y OBJETIVOS
- **Resumen:** 
    ETECHelp es una página web que ayuda al estudiante y al profesor a llevar un seguimiento de su progreso en el estudio técnico en computación, mitigando la decersión, brindando areas de oportunidad y ayuda especializada tanto alumnos y profesores. 
- **Metas:**
    - Tener un producto básico que represente bien la idea en 1 semana 
    - Que el producto despliegue una pagina de acuerdo a tu perfil (alumno, maestro o administrador)
    - Mostrar estadísticas de desempeño de cada alumno 
- **Objetivos:**
    - Tener una interfáz intuitiva tanto para el alumnado y el profesor
    - Tener una base de datos solida que almacene perfiles de alumnos
    - Crear gráficas de acuerdo a su calificación, actividades y cantidad de veces de apoyo
    - Que la pagina se adapte al dispositivo 

#### 3. PÚBLICO OBJETIVO (UX)
- Alumnos y profesores de la ETE de computación de la ENP 6

#### 4. PROPÓSITO Y ALCANCE
- **En alcance (Entregables):**
    - Pagina que tenga registro de alumnos y maestros
    - Tener una sección de dudas y comentarios global
    - Realizar un cuestionario para averiguar el perfil de un estudiante 
    - Desplegar en que áreas un alumno esta deficiente
    - Crear una lista para el profesor que muestre los alumnos que ocupan más ayuda
- **Fuera de alcance:**
    - Despliegue de información por gráficas
    - Desplegar recursos personalizados de acuerdo al estudiante 

#### 5. ESPECIFICACIONES FUNCIONALES
| **Módulo**     | **Descripción**                                                              | **Criterio de Aceptación**                                                      |
| -------------- | ---------------------------------------------------------------------------- | ------------------------------------------------------------------------------- |
| Autenticación  | El usuario podrá registrarse usando email y contraseña, numero de cuenta y grupo                      | El sistema debe enviar un correo de verificación exitoso.                       |
| Consulta de alumnos (exclusivo de profes)      | El profesor podra ver que alumnos estan inscritos y cual es su situación académica (calificaciones, cuantas veces pidio ayuda, actividades realizadas)                    | Al mostrar el listado de alumnos, aparecen primero los alumnos con potencial de desertar       |
| Consulta de tu perfil (ALUMNO) | El alumno podra observar por modulo cuantas actividades ha entregado o le faltan y sus calificaciones                               | Se muestran las actividades divididas por modulo, mostrando primero los modulos de mayor deficiencia  | 
| Publicar comentarios | El alumno pueda hacer preguntas acerca de los temas que no entienda y contabilice cuantas veces lo ha hecho| Que el profesor pueda leer el comentario | 
| Registro de calificaciones | Que el profesor pueda modificar o darle una calificación a cada alumno, por actividad 
| Registro de alumnos (PROFESOR) | El profesor puede registrar un nuevo alumno por medio de su numero de cuenta y correro electrónico | |
| Consultar recursos personalizados | El alumno puede ingresar para ver que recursos puede consultar o ejercicios propuestos de acuerdo a su perfil | 
| Cuestionario de revisión de perfil (ESTUDIANTE) | El alumno al ingresar por primera vez a la pagina, tendrá que realizar un cuestionario para averiguar el perfil que tiene | Aparecen secciones personalizadas en la pagina |
| Registro de actividades, avisos y recursos (PROFESOR) | El profesor puede ya sea añadir nuevas actividades al modulo, dar avisos en una sección única y poner nuevos recursos a disposición | 


#### 6. REQUISITOS NO FUNCIONALES
| **Categoría**     | **Requisito**                                                      |
| ----------------- | ------------------------------------------------------------------ |
| **Rendimiento**   | Tiempo de carga inicial rápido (LCP).                              |
| **Accesibilidad** | La web debe ser navegable mediante teclado y lectores de pantalla. |
| **Responsivo**     | El sitio web tiene que adaptarse a cualquier dispositivo           

#### 7. ARQUITECTURA DE LA INFORMACIÓN Y UX

- **Patrón de Navegación:** Barra de navegación superior fija (Sticky Navbar) con: Inicio, Mi Perfil.
- **Diseño Visual:** Se permitira.

#### 8. ESPECIFICACIONES TÉCNICAS

- **Frontend:** figma para el prototipado, y html y css para la estructura y diseño de la plataforma.
- **Backend:** PHP para gestionar la lógica de estadisticas y validacion para credenciales.
- **Base de Datos:** MariaDB para relacionar alumnado, calificaciones, grado de desercion y profesorado de manera estructurada.


