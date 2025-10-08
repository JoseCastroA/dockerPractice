<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $correo = htmlspecialchars($_POST['correo']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $tipo_consulta = htmlspecialchars($_POST['tipo_consulta']);
    $area = htmlspecialchars($_POST['area']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    if (!empty($nombre) && !empty($correo) && !empty($tipo_consulta) && !empty($area) && !empty($mensaje)) {
        try {
            $sql = "INSERT INTO mensajes (nombre, correo, telefono, tipo_consulta, area, mensaje) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $correo, $telefono, $tipo_consulta, $area, $mensaje]);
            $message = "<div class='alert success'>✅ Mensaje guardado correctamente.</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert error'>❌ Error al guardar el mensaje: " . $e->getMessage() . "</div>";
        }
    } else {
        $message = "<div class='alert warning'>⚠️ Por favor, completa todos los campos obligatorios.</div>";
    }
}

$stmt = $conn->query("SELECT * FROM mensajes ORDER BY fecha DESC");
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Arquitectura Cloud - Contenedores Docker</title>
<link rel='stylesheet' href='style.css'>
<link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
<script>
function validarFormulario() {
    const campos = ['nombre', 'correo', 'tipo_consulta', 'area', 'mensaje'];
    let esValido = true;
    
    // Limpiar errores anteriores
    document.querySelectorAll('.error-msg').forEach(el => el.remove());
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    
    campos.forEach(campo => {
        const elemento = document.getElementById(campo);
        const valor = elemento.value.trim();
        
        if (!valor) {
            mostrarError(elemento, 'Este campo es obligatorio');
            esValido = false;
        }
    });
    
    // Validar email
    const correo = document.getElementById('correo');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (correo.value && !emailRegex.test(correo.value)) {
        mostrarError(correo, 'Ingresa un correo electrónico válido');
        esValido = false;
    }
    
    // Validar teléfono si se proporciona
    const telefono = document.getElementById('telefono');
    const telefonoRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    if (telefono.value && !telefonoRegex.test(telefono.value)) {
        mostrarError(telefono, 'Ingresa un número de teléfono válido');
        esValido = false;
    }
    
    return esValido;
}

function mostrarError(elemento, mensaje) {
    elemento.classList.add('input-error');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-msg';
    errorDiv.textContent = mensaje;
    elemento.parentNode.appendChild(errorDiv);
}

function validarCampoEnTiempoReal(campo) {
    campo.addEventListener('blur', function() {
        const errorAnterior = this.parentNode.querySelector('.error-msg');
        if (errorAnterior) errorAnterior.remove();
        this.classList.remove('input-error');
        
        if (!this.value.trim() && this.hasAttribute('required')) {
            mostrarError(this, 'Este campo es obligatorio');
        }
    });
    
    campo.addEventListener('input', function() {
        if (this.classList.contains('input-error')) {
            this.classList.remove('input-error');
            const errorMsg = this.parentNode.querySelector('.error-msg');
            if (errorMsg) errorMsg.remove();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Aplicar validación en tiempo real a todos los campos
    document.querySelectorAll('input, textarea, select').forEach(validarCampoEnTiempoReal);
});
</script>
</head>
<body>
<header>
    <div class='header-content'>
        <div class='logo-section'>
            <i class='fas fa-cloud'></i>
            <div class='logo-text'>
                <h1>CloudTech Solutions</h1>
                <span class='tagline'>Innovación en Arquitectura Cloud</span>
            </div>
        </div>
        <nav class='header-nav'>
            <a href='#form-section'><i class='fas fa-edit'></i> Contacto</a>
            <a href='#table-section'><i class='fas fa-list'></i> Mensajes</a>
        </nav>
    </div>
    <div class='header-banner'>
        <h2><i class='fab fa-docker'></i> Taller de Contenedores con Docker</h2>
        <p>Aprende a desplegar aplicaciones web con PHP y MySQL usando contenedores</p>
    </div>
</header>

<main>
    <section class='form-section' id='form-section'>
        <div class='section-header'>
            <h3><i class='fas fa-paper-plane'></i> Contáctanos</h3>
            <p>Completa el formulario para enviarnos tu consulta o comentario</p>
        </div>
        <?= $message ?>
        <form method='POST' onsubmit='return validarFormulario();' class='contact-form'>
            <div class='form-row'>
                <div class='form-group'>
                    <label for='nombre'><i class='fas fa-user'></i> Nombre completo *</label>
                    <input type='text' name='nombre' id='nombre' placeholder='Tu nombre completo' required>
                </div>
                <div class='form-group'>
                    <label for='correo'><i class='fas fa-envelope'></i> Correo electrónico *</label>
                    <input type='email' name='correo' id='correo' placeholder='ejemplo@correo.com' required>
                </div>
            </div>
            
            <div class='form-row'>
                <div class='form-group'>
                    <label for='telefono'><i class='fas fa-phone'></i> Teléfono</label>
                    <input type='tel' name='telefono' id='telefono' placeholder='+57 300 123 4567'>
                </div>
                <div class='form-group'>
                    <label for='tipo_consulta'><i class='fas fa-question-circle'></i> Tipo de consulta *</label>
                    <select name='tipo_consulta' id='tipo_consulta' required>
                        <option value=''>Selecciona una opción</option>
                        <option value='informacion'>Información general</option>
                        <option value='soporte'>Soporte técnico</option>
                        <option value='comercial'>Consulta comercial</option>
                        <option value='capacitacion'>Capacitación</option>
                        <option value='otro'>Otro</option>
                    </select>
                </div>
            </div>
            
            <div class='form-group'>
                <label for='area'><i class='fas fa-building'></i> Área o categoría *</label>
                <select name='area' id='area' required>
                    <option value=''>Selecciona un área</option>
                    <option value='cloud'>Arquitectura Cloud</option>
                    <option value='devops'>DevOps</option>
                    <option value='containers'>Contenedores</option>
                    <option value='databases'>Bases de datos</option>
                    <option value='security'>Seguridad</option>
                    <option value='frontend'>Frontend</option>
                    <option value='backend'>Backend</option>
                    <option value='mobile'>Desarrollo móvil</option>
                </select>
            </div>

            <div class='form-group'>
                <label for='mensaje'><i class='fas fa-comment'></i> Mensaje *</label>
                <textarea name='mensaje' id='mensaje' rows='5' placeholder='Describe tu consulta, comentario o sugerencia...' required></textarea>
            </div>

            <button type='submit' class='btn-submit'>
                <i class='fas fa-paper-plane'></i> Enviar mensaje
            </button>
        </form>
    </section>

    <section class='table-section' id='table-section'>
        <div class='section-header'>
            <h3><i class='fas fa-database'></i> Mensajes registrados</h3>
            <p>Histórico de mensajes y consultas recibidas</p>
        </div>
        <div class='table-wrapper'>
            <table class='messages-table'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Área</th>
                        <th>Mensaje</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($mensajes) > 0): ?>
                        <?php foreach ($mensajes as $fila): ?>
                            <tr>
                                <td><?= $fila['id'] ?></td>
                                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                <td><?= htmlspecialchars($fila['correo']) ?></td>
                                <td><?= $fila['telefono'] ? htmlspecialchars($fila['telefono']) : '<span class="no-data">No proporcionado</span>' ?></td>
                                <td><span class="badge badge-<?= $fila['tipo_consulta'] ?>"><?= ucfirst(htmlspecialchars($fila['tipo_consulta'])) ?></span></td>
                                <td><span class="area-tag"><?= ucfirst(htmlspecialchars($fila['area'])) ?></span></td>
                                <td class="message-cell"><?= htmlspecialchars(substr($fila['mensaje'], 0, 100)) . (strlen($fila['mensaje']) > 100 ? '...' : '') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($fila['fecha'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan='8' class='no-records'><i class='fas fa-inbox'></i> Sin registros aún.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
<footer>
    <div class='footer-content'>
        <div class='footer-section'>
            <h4><i class='fas fa-graduation-cap'></i> CloudTech Solutions</h4>
            <p>Especialistas en arquitectura cloud y contenedores Docker. Formando profesionales para el futuro tecnológico.</p>
            <div class='social-links'>
                <a href='#'><i class='fab fa-linkedin'></i></a>
                <a href='#'><i class='fab fa-github'></i></a>
                <a href='#'><i class='fab fa-twitter'></i></a>
                <a href='#'><i class='fab fa-docker'></i></a>
            </div>
        </div>
        
        <div class='footer-section'>
            <h4><i class='fas fa-envelope'></i> Contacto</h4>
            <p><i class='fas fa-user'></i> Docente: Juan Carlos López Henao</p>
            <p><i class='fas fa-envelope'></i> info@cloudtech.edu.co</p>
            <p><i class='fas fa-phone'></i> +57 300 123 4567</p>
            <p><i class='fas fa-map-marker-alt'></i> Medellín, Colombia</p>
        </div>
        
        <div class='footer-section'>
            <h4><i class='fas fa-cog'></i> Tecnologías</h4>
            <div class='tech-badges'>
                <span class='tech-badge'><i class='fab fa-docker'></i> Docker</span>
                <span class='tech-badge'><i class='fab fa-php'></i> PHP</span>
                <span class='tech-badge'><i class='fas fa-database'></i> MySQL</span>
                <span class='tech-badge'><i class='fab fa-html5'></i> HTML5</span>
                <span class='tech-badge'><i class='fab fa-css3-alt'></i> CSS3</span>
                <span class='tech-badge'><i class='fab fa-js'></i> JavaScript</span>
            </div>
        </div>
    </div>
    
    <div class='footer-bottom'>
        <p>&copy; 2025 CloudTech Solutions - Taller Docker. Todos los derechos reservados.</p>
        <p class='version'>Versión 2.0 | Última actualización: <?= date('d/m/Y') ?></p>
    </div>
</footer>
</body>
</html>
