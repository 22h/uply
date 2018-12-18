<?php

return [
    'status_code'  => [
        'success' => 'Der Status Code von (%url%) hat wieder auf %expectedStatusCode% zurück gewechselt.',
        'danger'  => 'Der Status Code von (%url%) ist %actualStatusCode%, müsste aber %expectedStatusCode% sein. %refreshUrl%',
        'error'   => 'Der Status Code von (%url%) konnte nicht geprüft werden. Fehler: (%exception%)',
    ],
    'certificate'  => [
        'success' => 'Das Zertifikat für %domain% läuft erst wieder in %days% ab.',
        'warning' => 'Das Zertifikat für %domain% läuft bereits in %days% ab.',
        'danger'  => 'Das Zertifikat für %domain% ist nicht mehr gültig.',
        'error'   => 'Das Zertifikat für %domain% konnte nicht abgerufen werden. Fehler: (%exception%)',
    ],
    'content_hash' => [
        'success' => 'Der Content Hash von (%url%) hat wieder den gewünschten Hash (%expectedHash%).',
        'danger'  => 'Der Content Hash von (%url%) ist (%actualHash%), müsste aber (%expectedHash%) sein. %refreshUrl%',
        'error'   => 'Der Content Hash von (%url%) konnte nicht geprüft werden. Fehler: (%exception%)',
    ],
];