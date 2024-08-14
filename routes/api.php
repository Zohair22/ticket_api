<?php

use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['ApiGuardMiddleware'])->name('api.')->prefix('/v1/tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);
    Route::post('/', [TicketController::class, 'store']);
    Route::get('/{ticket}', [TicketController::class, 'show']);
    Route::get('/user/{user}', [TicketController::class, 'getUserTickets']);

    Route::put('/{ticket}', [TicketController::class, 'update']);
    Route::delete('/{ticket}', [TicketController::class, 'destroy']);
});
