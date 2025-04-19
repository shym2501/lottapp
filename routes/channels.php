<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('spin-display.{userId}', function ($user, $userId) {
//     return (int) $user->id === (int) $userId;
// });

Broadcast::channel('spin-display.{formId}', function ($user, $formId) {
    return true; // atau validasi sesuai kebutuhan user dan form ID
});
