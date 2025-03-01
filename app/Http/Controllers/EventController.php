<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\impl\EventRepository;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository){
        $this->eventRepository = $eventRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->eventRepository->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = $this->eventRepository->create($request->all());

        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $event = $this->eventRepository->find($id);

        return response()->json($event, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $event = $this->eventRepository->find($id);
        $event = $this->eventRepository->update($event, $request->all());

        return response()->json($event, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $event = $this->eventRepository->find($id);
        $this->eventRepository->delete($event);

        return response()->json(null, 204);
    }
}
