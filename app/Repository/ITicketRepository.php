<?php

namespace App\Repository;

interface ITicketRepository
{
    public function getTicketsByUser(int $userId);

    public function getTicketById(int $ticketId);

    public function createTicket($user, $request);

    public function updateTicket(int $ticket, array $data);

    public function deleteTicket(int $ticketId);

    public function getTickets();
}
