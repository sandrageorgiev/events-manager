<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Image;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class EventController extends Controller
{
    protected EventRepositoryInterface $eventRepository;
    protected ImageRepositoryInterface $imageRepository;

    public function __construct(EventRepositoryInterface $eventRepository, ImageRepositoryInterface $imageRepository){
        $this->eventRepository = $eventRepository;
        $this->imageRepository = $imageRepository;
    }


    // /api/events/upload - raboti
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        // Add comprehensive request debugging
        Log::debug('Request content type: ' . $request->header('Content-Type'));
        Log::debug('All request data:', $request->except('files')); // Don't log file data

        // Process event data from form
        $eventData = [];

        // Check if we have a 'event' field with JSON
        if ($request->has('event')) {
            $eventJson = $request->input('event');
            if (is_string($eventJson)) {
                try {
                    $eventData = json_decode($eventJson, true, 512, JSON_THROW_ON_ERROR);
                    Log::debug('Event from form field:', $eventData);
                } catch (\JsonException $e) {
                    Log::error('Failed to parse event JSON: ' . $e->getMessage());
                    return response()->json(['error' => 'Invalid event JSON'], 400);
                }
            } else {
                $eventData = $eventJson;
            }
        }
        // For regular form submissions
        else {
            $eventData = $request->except('files');
        }

        // Debug the extracted event data
        Log::debug('Final event data for creation:', $eventData);

        // Validate minimum required data
        if (empty($eventData) || !isset($eventData['name'])) {
            Log::error('Missing required event data');
            return response()->json([
                'error' => 'Missing required event data (name is required)',
                'received_data' => $eventData
            ], 400);
        }

        // Create the event
        try {
            $event = $this->eventRepository->create($eventData);
            Log::debug('Event created successfully', ['event_id' => $event->id]);
        } catch (\Exception $e) {
            Log::error('Failed to create event: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create event: ' . $e->getMessage()], 500);
        }

        // Process uploaded files
        $uploadedFiles = $request->file('files');
        $fileCount = 0;

        if ($uploadedFiles) {
            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    try {
                        $this->imageRepository->saveImage($event, $file);
                        $fileCount++;
                    } catch (\Exception $e) {
                        Log::error('Failed to save uploaded file: ' . $e->getMessage());
                    }
                }
            } else {
                // Single file upload
                try {
                    $this->imageRepository->saveImage($event, $uploadedFiles);
                    $fileCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to save uploaded file: ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'event' => $event,
            'message' => 'Event created successfully with ' . $fileCount . ' files'
        ], 201);
    }



    // /api/events - raboti
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

    // /api/events/{$id} - raboti
    public function show(int $id): \Illuminate\Http\JsonResponse
    {

        $event = $this->eventRepository->find($id);

        return response()->json($event, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $event = $this->eventRepository->find($id);
        $event = $this->eventRepository->update($event, $request->all());

//        return response()->json($event, 201);
        return response()->json(($event), 201);

    }


    // raboti
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $event = $this->eventRepository->find($id);
        $this->eventRepository->delete($event);

        return response()->json(null, 204);
    }



    public function getImageByEventId($id, $num)
    {
        try {
            Log::debug('EventImageController::getImageByEventId called', [
                'event_id' => $id,
                'image_num' => $num
            ]);

            $images = $this->imageRepository->findAllByEvent($id);

            // Convert $num to integer and adjust for zero-based indexing
            $index = (int)$num;

            // Add debug information
            Log::debug('Image access attempt', [
                'requested_index' => $index,
                'array_count' => count($images),
                'indexes_available' => array_keys($images)
            ]);

            // Check if the index exists in the array
            if (isset($images[$index])) {
                $image = $images[$index];

                // Convert resource to string if needed
                $data = $image->data;
                if (is_resource($data)) {
                    $data = stream_get_contents($data);
                }

                return response($data)
                    ->header('Content-Type', $image->content_type);
            }

            Log::debug('Image not found', [
                'event_id' => $id,
                'image_num' => $index,
                'available_images' => count($images)
            ]);

            return response()->noContent(404);
        } catch (\Exception $e) {
            Log::error('Exception in EventImageController::getImageByEventId: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

}
