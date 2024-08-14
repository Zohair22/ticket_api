<?php

namespace App\Services\Ticket;

use App\Exceptions\UnauthorizedUserException;
use App\Models\Ticket;
use App\Repository\ITicketRepository;
use Illuminate\Database\Eloquent\Collection;

class TicketService implements ITicketService
{
    protected ITicketRepository $ticketRepository;

    public function __construct(ITicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function getAllTickets(): Collection
    {
        return $this->ticketRepository->getTickets();
    }

    public function createTicket($user, $request): Object
    {
        return $this->ticketRepository->createTicket($user, $request);
    }

    public function getTicketById($id): Ticket
    {
        return $this->ticketRepository->getTicketById($id);
    }

    /**
     * @throws \App\Exceptions\UnauthorizedUserException
     */
    public function updateTicket($user, $ticket, array $data): Ticket
    {
        if ($user->id !== $ticket->user_id) {
            throw new UnauthorizedUserException;
        }
        return $this->ticketRepository->updateTicket($ticket, $data);
    }

    /**
     * @throws \App\Exceptions\UnauthorizedUserException
     */
    public function deleteTicket($user, $ticket): void
    {
        if ($user->id !== $ticket->user_id) {
            throw new UnauthorizedUserException;
        }
        $this->ticketRepository->deleteTicket($ticket);
    }

    public function getUserTickets($userId): Collection
    {
        return $this->ticketRepository->getTicketsByUser($userId);
    }
}
