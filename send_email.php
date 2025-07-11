<?php
// Solo procesar si la solicitud es un POST (es decir, el formulario fue enviado)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recoger y sanitizar los datos del formulario
    // htmlspecialchars: Convierte caracteres especiales en entidades HTML para prevenir XSS.
    // trim: Elimina espacios en blanco al inicio y al final.
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $apellido = htmlspecialchars(trim($_POST["apellido"]));
    $email_remitente = htmlspecialchars(trim($_POST["email"])); // El email del usuario que contacta
    $mensaje = htmlspecialchars(trim($_POST["mensaje"]));

    // 2. Validar los datos básicos (opcional pero muy recomendado)
    if (empty($nombre) || empty($apellido) || empty($email_remitente) || empty($mensaje) || !filter_var($email_remitente, FILTER_VALIDATE_EMAIL)) {
        // Si algún campo requerido está vacío o el email no es válido, redirige a una página de error o muestra un mensaje.
        header("Location: error.html?status=invalid_data");
        exit;
    }

    // 3. Configurar los detalles del correo electrónico
    $destinatario = "cuarzocristalempresarial@gmail.com"; // ¡Tu dirección de correo aquí!
    $asunto = "Nuevo Mensaje de Contacto de: " . $nombre . " " . $apellido;

    // Contenido del correo que recibirás
    $contenido_email = "Nombre: " . $nombre . " " . $apellido . "\n";
    $contenido_email .= "Correo Electrónico: " . $email_remitente . "\n\n";
    $contenido_email .= "Mensaje:\n" . $mensaje;

    // 4. Configurar las cabeceras del correo
    // Esto ayuda a que el email no vaya a spam y a que puedas responder directamente al usuario.
    $cabeceras = "From: " . $nombre . " " . $apellido . " <" . $email_remitente . ">\r\n";
    $cabeceras .= "Reply-To: " . $email_remitente . "\r\n";
    $cabeceras .= "MIME-Version: 1.0\r\n";
    $cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Asegura que los caracteres especiales se muestren correctamente

    // 5. Enviar el correo electrónico
    // La función mail() devuelve true si el correo fue aceptado para envío, false en caso contrario.
    // Esto no garantiza que el correo llegue al buzón de destino.
    if (mail($destinatario, $asunto, $contenido_email, $cabeceras)) {
        // Redirige al usuario a una página de éxito
        header("Location: thank_you.html"); // Puedes crear esta página HTML (ej: "¡Gracias por tu mensaje!")
        exit; // Termina la ejecución del script
    } else {
        // Redirige al usuario a una página de error
        header("Location: error.html?status=send_failed"); // Puedes crear esta página HTML (ej: "Lo sentimos, hubo un problema.")
        exit; // Termina la ejecución del script
    }

} else {
    // Si alguien intenta acceder a send_email.php directamente sin enviar el formulario,
    // redirigirlo de vuelta a la página de contacto.
    header("Location: contacto.html");
    exit;
}
?>