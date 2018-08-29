<?php

use App\Models\UserDocument;

$factory->define(UserDocument::class, function () {
    return [
        'document' => 'seed/user-documents/nid-front.jpg'
    ];
});
