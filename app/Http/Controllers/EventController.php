<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
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


//    public function upload(Request $request): \Illuminate\Http\JsonResponse
//    {
//        // Get all request data
//        $eventData = $request->except('files'); // Exclude files from event creation
//        //$files = $request->file('files', []); // Get uploaded files (default to empty array)
//
//        // Create event
//        $event = $this->eventRepository->create($eventData);
//
//        // Save images
////        foreach ($files as $file) {
////            $this->imageRepository->saveImage($event, $file);
////        }
////        foreach ($files as $fileData) {
////            try {
////                $this->imageRepository->saveImage($event, $fileData);
////                print("Vlaga li tuka");
////            } catch (\Exception $e) {
////                Log::error('Failed to save image: ' . $e->getMessage());
////                // Decide if you want to continue or return an error response
////            }
////        }
//
//        // In your controller method
//        $files = $request->input('files', []);
//        Log::debug('Files data:', ['count' => count($files), 'files' => $files]);
//
//        foreach ($files as $index => $fileData) {
//            Log::debug('Processing file ' . $index, [
//                'filename' => $fileData['filename'] ?? 'unknown',
//                'has_data' => isset($fileData['data']),
//                'data_length' => isset($fileData['data']) ? strlen($fileData['data']) : 0
//            ]);
//
//            try {
//                $image = $this->imageRepository->saveImage($event, $fileData);
//                Log::debug('Image saved successfully', ['image_id' => $image->id ?? 'unknown']);
//            } catch (\Exception $e) {
//                Log::error('Failed to save image: ' . $e->getMessage(), [
//                    'exception' => get_class($e),
//                    'trace' => $e->getTraceAsString()
//                ]);
//            }
//        }
//
//        return response()->json(['event' => $event], 201);
//    }


//    public function upload(Request $request): \Illuminate\Http\JsonResponse
//    {
//        // Add comprehensive request debugging
//        Log::debug('Request content type: ' . $request->header('Content-Type'));
//        Log::debug('Raw request body: ' . $request->getContent());
//        Log::debug('All request data:', $request->all());
//
//        // Try to determine if we're dealing with a form, JSON, or multipart request
//        $eventData = [];
//
//        // Check if we have a JSON content type
//        if ($request->isJson()) {
//            // For JSON requests, the data is in the request body
//            $content = json_decode($request->getContent(), true);
//            Log::debug('JSON request content:', $content ?: []);
//            $eventData = $content;
//
//            // Extract files array if it exists
//            $jsonFiles = $eventData['files'] ?? [];
//            unset($eventData['files']); // Remove files from event data
//        }
//        // Check if we have a form with an 'event' field (common for multipart uploads)
//        elseif ($request->has('event')) {
//            $eventJson = $request->input('event');
//            if (is_string($eventJson)) {
//                try {
//                    $eventData = json_decode($eventJson, true, 512, JSON_THROW_ON_ERROR);
//                    Log::debug('Event from form field:', $eventData);
//                } catch (\JsonException $e) {
//                    Log::error('Failed to parse event JSON: ' . $e->getMessage());
//                    return response()->json(['error' => 'Invalid event JSON'], 400);
//                }
//            } else {
//                $eventData = $eventJson;
//            }
//
//            $jsonFiles = []; // No JSON files in this case
//        }
//        // For other form submissions, try to get all fields except files
//        else {
//            $eventData = $request->except('files');
//            Log::debug('Form data (except files):', $eventData);
//            $jsonFiles = [];
//        }
//
//        // Debug the extracted event data
//        Log::debug('Final event data for creation:', $eventData);
//
//        // Validate minimum required data
//        if (empty($eventData) || !isset($eventData['name'])) {
//            Log::error('Missing required event data');
//            return response()->json([
//                'error' => 'Missing required event data (name is required)',
//                'received_data' => $eventData
//            ], 400);
//        }
//
//        // Create the event
//        try {
//            $event = $this->eventRepository->create($eventData);
//            Log::debug('Event created successfully', ['event_id' => $event->id]);
//        } catch (\Exception $e) {
//            Log::error('Failed to create event: ' . $e->getMessage());
//            return response()->json(['error' => 'Failed to create event: ' . $e->getMessage()], 500);
//        }
//
//        // Process uploaded files (multipart form)
//        $uploadedFiles = $request->file('files');
//        $fileCount = 0;
//
//        if ($uploadedFiles) {
//            if (is_array($uploadedFiles)) {
//                foreach ($uploadedFiles as $file) {
//                    try {
//                        $this->imageRepository->saveImage($event, $file);
//                        $fileCount++;
//                    } catch (\Exception $e) {
//                        Log::error('Failed to save uploaded file: ' . $e->getMessage());
//                    }
//                }
//            } else {
//                // Single file upload
//                try {
//                    $this->imageRepository->saveImage($event, $uploadedFiles);
//                    $fileCount++;
//                } catch (\Exception $e) {
//                    Log::error('Failed to save uploaded file: ' . $e->getMessage());
//                }
//            }
//        }
//
//        // Process JSON files
//        if (!empty($jsonFiles)) {
//            foreach ($jsonFiles as $fileData) {
//                try {
//                    // Ensure required file fields exist
//                    if (!isset($fileData['filename']) || !isset($fileData['data'])) {
//                        Log::warning('Skipping file with missing data', ['file' => $fileData]);
//                        continue;
//                    }
//
//                    // Create a temporary file
//                    $tempFile = tmpfile();
//                    $tempFilePath = stream_get_meta_data($tempFile)['uri'];
//
//                    // Decode and write base64 data
//                    $decodedData = base64_decode($fileData['data']);
//                    if ($decodedData === false) {
//                        Log::error('Invalid base64 data for file: ' . $fileData['filename']);
//                        continue;
//                    }
//
//                    file_put_contents($tempFilePath, $decodedData);
//
//                    // Create a file object
//                    $file = new \Illuminate\Http\UploadedFile(
//                        $tempFilePath,
//                        $fileData['filename'],
//                        $fileData['content_type'] ?? 'application/octet-stream',
//                        null,
//                        true
//                    );
//
//                    // Save the image
//                    $this->imageRepository->saveImage($event, $file);
//                    $fileCount++;
//
//                    // Close temporary file
//                    fclose($tempFile);
//                } catch (\Exception $e) {
//                    Log::error('Failed to save JSON file: ' . $e->getMessage());
//                }
//            }
//        }
//
//        return response()->json([
//            'event' => $event,
//            'message' => 'Event created successfully with ' . $fileCount . ' files'
//        ], 201);
//    }



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
