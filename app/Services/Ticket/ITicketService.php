<?php

namespace App\Services\Ticket;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Ticket;
interface ITicketService
{
    public function getAllTickets(): Collection;
    public function createTicket($user, $request);
    public function getTicketById($id): Ticket;
    public function updateTicket($user, $ticket, array $data): Ticket;
    public function deleteTicket($user, $ticket): void;
    public function getUserTickets($userId): Collection;
}
