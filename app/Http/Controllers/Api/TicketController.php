<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnauthorizedUserException;
use App\Http\Requests\Ticket\TicketRequest;
use App\Models\Ticket;
use App\Services\Ticket\ITicketService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends ApiBaseController
{
    protected ITicketService $ticketService;

    public function __construct(ITicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of tickets.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $tickets = $this->ticketService->getAllTickets();
            return $this->respondSuccess($tickets->toArray(), "Tickets retrieved successfully");
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tickets', ['exception' => $e]);
            return $this->respondWithError([], "Failed to retrieve tickets");
        }
    }

    /**
     * Store a newly created ticket.
     *
     * @param TicketRequest $request
     * @return JsonResponse
     */
    public function store(TicketRequest $request): JsonResponse
    {
        $user = auth()->user();
        try {
            $ticket = $this->ticketService->createTicket($user, $request->validated());
            return $this->respondCreated($ticket->toArray(), "Ticket created successfully");
        } catch (\Exception $e) {
            Log::error('Failed to create ticket', ['exception' => $e]);
            return $this->respondWithError([], "Failed to create ticket");
        }
    }

    /**
     * Display the specified ticket.
     *
     * @param int $ticketID
     * @return JsonResponse
     */
    public function show(int $ticketID): JsonResponse
    {
        try {
            $ticket = $this->ticketService->getTicketById($ticketID);
            return $this->respondSuccess($ticket->toArray(), "Ticket retrieved successfully");
        } catch (\Exception $e) {
            Log::error('Ticket not found', ['id' => $ticketID, 'exception' => $e]);
            return $this->respondNotFound([], "Ticket not found");
        }
    }

    /**
     * Update the specified ticket.
     *
     * @param TicketRequest $request
     * @param $ticketId
     * @return JsonResponse
     * @throws \App\Exceptions\UnauthorizedUserException
     */
    public function update(TicketRequest $request, $ticketID): JsonResponse
    {
        $user = auth()->user();
        try {
            $ticket = $this->ticketService->getTicketById($ticketID);
            $ticket = $this->ticketService->updateTicket($user, $ticket, $request->validated());
            return $this->respondSuccess($ticket->toArray(), "Ticket updated successfully");
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound([], "Ticket not found");
        } catch (UnauthorizedUserException $e) {
            throw new $e;
        } catch (\Exception $e) {
            Log::error('Failed to update ticket', ['Ticket' => $ticket, 'exception' => $e]);
            return $this->respondWithError([], "Failed to update ticket");
        }
    }

    /**
     * Remove the specified ticket.
     *
     * @param Ticket $ticket
     * @return JsonResponse
     * @throws \App\Exceptions\UnauthorizedUserException
     */
    public function destroy( Ticket $ticket): JsonResponse
    {
        $user = auth()->user();
        try {
            $this->ticketService->deleteTicket($user, $ticket);
            return $this->respondSuccess([], "Ticket deleted successfully");
        } catch (UnauthorizedUserException $e) {
            throw new $e;
        } catch (\Exception $e) {
            Log::error('Failed to delete ticket', ['Ticket' => $ticket, 'exception' => $e]);
            return $this->respondWithError([], "Failed to delete ticket");
        }
    }

    /**
     * Display tickets for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exceptions\UnauthorizedUserException
     */
    public function getUserTickets(Request $request): JsonResponse
    {
        try {
            $tickets = $this->ticketService->getUserTickets($request->user()->id);
            return $this->respondSuccess($tickets->toArray(), "User tickets retrieved successfully");
        } catch (UnauthorizedUserException $e) {
            throw new $e;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user tickets', ['exception' => $e]);
            return $this->respondWithError([], "Failed to retrieve user tickets");
        }
    }
}
