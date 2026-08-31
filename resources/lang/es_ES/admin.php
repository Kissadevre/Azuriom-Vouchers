<?php

return [
    'title' => 'Códigos de canje',
    'permission' => 'Administrar códigos de canje',

    'nav' => [
        'settings' => 'Ajustes',
        'codes' => 'Códigos',
        'redemptions' => 'Registros',
    ],

    'settings' => [
        'title' => 'Ajustes de Vouchers',
        'security_title' => 'Disponibilidad y protección',
        'security_description' => 'Controla el acceso global y limita los intentos de canje sospechosos.',
        'enabled' => 'Habilitar el canje de vouchers',
        'enabled_help' => 'Al desactivarlo, se detendrán todos los canjes sin modificar ni deshabilitar los códigos individuales.',
        'user_menu' => 'Mostrar Vouchers en el menú del usuario',
        'user_menu_help' => 'Agrega un acceso a la página de canje en el menú desplegable de los usuarios con sesión iniciada.',
        'user_menu_icon' => 'Icono del menú',
        'user_menu_icon_help' => 'Escribe una clase de <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener noreferrer">Bootstrap Icons</a>, por ejemplo <code>bi-ticket-perforated</code>.',
        'discord_webhook_enabled' => 'Activar notificaciones de Discord',
        'discord_webhook_enabled_help' => 'Envía una notificación cuando se registre un nuevo canje de voucher. Está desactivado por defecto.',
        'discord_webhook_url' => 'URL del webhook de Discord',
        'discord_webhook_url_help' => 'La URL se almacena cifrada y solo se utiliza para las notificaciones de canjes.',
        'discord_webhook_url_invalid' => 'Ingresa una URL oficial válida de webhook de Discord.',
        'discord_webhook_test' => 'Probar webhook',
        'discord_webhook_test_sent' => 'La notificación de prueba se envió correctamente a Discord.',
        'discord_webhook_test_failed' => 'Discord no aceptó la notificación de prueba. Verifica la URL e inténtalo de nuevo.',
        'rate_limit' => 'Límite de intentos de canje',
        'attempts_per_minute' => 'intentos por minuto y dirección IP',
        'rate_limit_help' => 'Limita todos los intentos de una misma dirección IP para reducir la adivinación de códigos y el uso malintencionado.',
        'updated' => 'Los ajustes de Vouchers fueron actualizados.',
    ],

    'redemptions' => [
        'title' => 'Registros de canje',
        'activity_title' => 'Actividad reciente',
        'description' => 'Consulta cada canje, su destinatario, el usuario que lo solicitó y el estado de entrega.',
        'empty' => 'Todavía no se ha canjeado ningún voucher.',
        'reference' => 'Referencia',
        'voucher' => 'Voucher',
        'recipient' => 'Destinatario',
        'redeemer' => 'Canjeado por',
        'guest' => 'Invitado',
        'ip_address' => 'Dirección IP',
        'date' => 'Fecha',
    ],

    'codes' => [
        'title' => 'Códigos de canje',
        'manage_title' => 'Administrar códigos',
        'description' => 'Crea códigos, controla quién puede canjearlos y asigna una o varias recompensas.',
        'form_description' => 'Define la disponibilidad, los límites de uso y las recompensas que recibirá cada cuenta.',
        'create' => 'Crear código',
        'edit' => 'Editar :voucher',
        'empty' => 'Todavía no se ha creado ningún código de canje.',
        'created' => 'El código de canje fue creado.',
        'updated' => 'El código de canje fue actualizado.',
        'disabled' => 'El código de canje fue desactivado.',
        'deleted' => 'El código de canje fue eliminado.',
        'delete_has_redemptions' => 'No se puede eliminar un código con historial de canjes. Desactívalo en su lugar.',
    ],

    'sections' => [
        'identity' => 'Identidad del código',
        'limits' => 'Límites y periodo de validez',
        'access' => 'Acceso y disponibilidad',
    ],

    'fields' => [
        'name' => 'Nombre interno',
        'code' => 'Código',
        'status' => 'Estado',
        'uses' => 'Usos',
        'rewards' => 'Recompensas',
        'max_redemptions' => 'Límite global de canjes',
        'max_redemptions_per_user' => 'Límite de canjes por usuario',
        'starts_at' => 'Fecha de inicio',
        'expires_at' => 'Fecha de finalización',
        'requires_authentication' => 'Requerir que el usuario inicie sesión',
        'is_enabled' => 'Código habilitado',
    ],

    'help' => [
        'code' => 'Usa entre 8 y 14 caracteres. Solo se admiten letras de A a Z, números y guiones.',
        'max_redemptions' => 'Usa 1 para un código de un solo uso o déjalo vacío para permitir canjes ilimitados.',
        'max_redemptions_per_user' => 'Usa 1 para impedir que la misma cuenta canjee este código más de una vez. Déjalo vacío para permitir canjes ilimitados por cuenta.',
        'requires_authentication' => 'Si se desactiva, los invitados deberán indicar el nombre de una cuenta existente en Azuriom.',
        'shop_package' => 'Se excluyen suscripciones, paquetes con variables obligatorias y giftcards de valor dinámico. Los paquetes deshabilitados siguen disponibles como recompensas ocultas.',
        'server_command' => 'Usa {player} o {name} para el destinatario. Escribe un solo comando sin / inicial. Esperar al jugador requiere un servidor AzLink.',
        'internal_role' => 'Asciende la cuenta solo si este rol tiene más poder que su rol actual. Solo se permite una recompensa de rol por voucher. Los roles administrativos están excluidos y los roles vinculados de Discord no se sincronizan.',
    ],

    'actions' => [
        'generate' => 'Generar',
        'disable' => 'Desactivar',
    ],

    'rewards' => [
        'title' => 'Recompensas',
        'description' => 'Se entregarán todas las recompensas. Las recompensas externas se procesan después de reservar el voucher.',
        'add' => 'Agregar recompensa',
        'reward' => 'Recompensa',
        'type' => 'Tipo de recompensa',
        'amount' => 'Puntos',
        'package' => 'Paquete / producto de Shop',
        'select_package' => 'Selecciona un paquete',
        'package_unavailable' => 'no disponible',
        'package_disabled' => 'deshabilitado',
        'shop_unavailable' => 'Shop no disponible',
        'shop_unavailable_help' => 'Este voucher contiene una recompensa de Shop, pero Shop no está habilitado. Habilita Shop o reemplaza la recompensa antes de guardar.',
        'server' => 'Servidor de juego',
        'command' => 'Comando',
        'execution_condition' => 'Condición de ejecución',
        'select_server' => 'Selecciona un servidor',
        'server_unavailable' => 'no disponible',
        'server_unavailable_help' => 'Este voucher apunta a un servidor eliminado o que ya no puede ejecutar comandos. Selecciona otro servidor antes de guardar.',
        'role' => 'Rol interno',
        'select_role' => 'Selecciona un rol',
        'role_unavailable' => 'no disponible',
        'role_unavailable_help' => 'Este voucher apunta a un rol eliminado, administrativo o que ya no puedes administrar. Selecciona otro rol antes de guardar.',
        'unsupported_type' => 'Tipo no compatible: :type',
        'unsupported_type_unknown' => 'Tipo no compatible',
        'types' => [
            'money' => 'Puntos de Shop',
            'shop_package' => 'Paquete / producto de Shop',
            'server_command' => 'Comando de servidor (RCON / AzLink)',
            'internal_role' => 'Rol interno de Azuriom',
        ],
        'conditions' => [
            'immediate' => 'Ejecutar inmediatamente',
            'online' => 'Esperar a que el jugador esté conectado (solo AzLink)',
        ],
    ],

    'status' => [
        'active' => ['label' => 'Activo', 'color' => 'success'],
        'disabled' => ['label' => 'Desactivado', 'color' => 'secondary'],
        'scheduled' => ['label' => 'Programado', 'color' => 'info'],
        'expired' => ['label' => 'Vencido', 'color' => 'warning'],
        'exhausted' => ['label' => 'Agotado', 'color' => 'danger'],
    ],

    'redemption_status' => [
        'processing' => ['label' => 'Procesando', 'color' => 'info'],
        'completed' => ['label' => 'Completado', 'color' => 'success'],
        'partial' => ['label' => 'Entrega parcial', 'color' => 'warning'],
        'review_required' => ['label' => 'Requiere revisión', 'color' => 'warning'],
        'failed' => ['label' => 'Fallido', 'color' => 'danger'],
    ],

    'validation' => [
        'code_format' => 'El código debe contener entre 8 y 14 caracteres y usar únicamente letras de A a Z, números o guiones.',
        'code_unique' => 'Este código de canje ya está en uso.',
        'expires_after_start' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
        'stale_revision' => 'Otro administrador modificó este código. Recarga la página y revisa sus cambios antes de guardar de nuevo.',
        'package_unavailable' => 'El paquete de Shop seleccionado no está disponible o requiere datos no compatibles.',
        'server_unavailable' => 'El servidor seleccionado no existe o ya no puede ejecutar comandos.',
        'online_requirement_unavailable' => 'Solo los servidores AzLink pueden esperar a que el jugador esté conectado.',
        'command_format' => 'Usa un solo comando sin / inicial ni caracteres de control. Solo se admiten las variables {player} y {name}.',
        'role_unavailable' => 'El rol seleccionado no está disponible, es administrativo o está fuera de tu autoridad.',
        'role_limit' => 'Un voucher solo puede contener una recompensa de rol interno.',
        'reward_unavailable' => 'Una integración de recompensa cambió mientras se guardaba el voucher. Revisa las recompensas e inténtalo de nuevo.',
    ],

    'errors' => [
        'generation_failed' => 'No se pudo generar el código. Inténtalo de nuevo.',
    ],

    'unlimited' => 'Ilimitado',

    'logs' => [
        'settings' => 'Actualizó los ajustes de Vouchers.',
        'vouchers-codes' => [
            'created' => 'Creó el código de canje #:id.',
            'updated' => 'Actualizó el código de canje #:id.',
            'deleted' => 'Eliminó el código de canje #:id.',
        ],
        'vouchers-rewards' => [
            'created' => 'Creó la recompensa de código #:id.',
            'updated' => 'Actualizó la recompensa de código #:id.',
            'deleted' => 'Eliminó la recompensa de código #:id.',
        ],
    ],
];
