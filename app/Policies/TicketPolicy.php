<?php
namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the ticket.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Ticket $ticket
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->id !== $ticket->user_id) {
            throw new AuthorizationException('You are not authorized to update this ticket.');
        }
        return true;
    }

    /**
     * Determine whether the user can delete the ticket.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Ticket $ticket
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($user->id !== $ticket->user_id) {
            throw new AuthorizationException('You are not authorized to delete this ticket.');
        }
        return true;
    }
}
