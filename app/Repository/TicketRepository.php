<?php

namespace App\Repository;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository implements ITicketRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTickets(): Collection
    {
        return Ticket::all();
    }

    /**
     * @param $user
     * @param $request
     * @return Object
     */
    public function createTicket($user, $request): Object
    {
        return $user->tickets()->create($request);
    }

    /**
     * @param $ticketId
     * @return \App\Models\Ticket
     */
    public function getTicketById($ticketId): Ticket
    {
        return Ticket::findOrFail($ticketId);
    }

    /**
     * @param Ticket $ticket
     * @param array $data
     * @return \App\Models\Ticket
     */
    public function updateTicket($ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket;
    }

    /**
     * @param $ticketId
     * @return void
     */
    public function deleteTicket($ticketId): void
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->delete();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTicketsByUser($userId): Collection
    {
        return Ticket::where('user_id', $userId)->get();
    }
}
