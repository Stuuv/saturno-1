<?php
header('Content-Type: application/json');

require_once '../conexion.php';

try {
    $conexion = Conexion::conectar();
    
    // 1. IMPORTANTE: Agregamos 'foto' a la consulta SQL
    $stmt = $conexion->prepare("SELECT id_tecnico, nombre, direccion, telefono, correo, foto FROM tecnicos ORDER BY nombre");
    $stmt->execute();
 
    // Cambié el nombre de la variable de $clientes a $tecnicos para ser más consistentes
    $tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. LOGICA DE CONVERSIÓN:
    // Recorremos cada técnico para buscar su foto y convertirla
    foreach ($tecnicos as $key => $tecnico) {
        // Verificamos si el campo 'foto' no está vacío ni es nulo
        if (!empty($tecnico['foto'])) {
            // Sobrescribimos el campo 'foto' con su versión en Base64
            $tecnicos[$key]['foto'] = base64_encode($tecnico['foto']);
        }
    }

    echo json_encode($tecnicos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar los técnicos: ' . $e->getMessage()]);
}
?>