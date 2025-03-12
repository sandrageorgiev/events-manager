<?php


namespace App\Repositories\impl;

use App\Models\Event;
use App\Models\Image;
use App\Repositories\ImageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImageRepository implements ImageRepositoryInterface
{


    public function saveImage(Event $event, $file)
    {
        try {
            Log::debug('ImageRepository::saveImage called', [
                'event_id' => $event->id,
                'file_type' => is_array($file) ? 'array' : get_class($file),
                'file_name' => $file->getClientOriginalName()
            ]);

            // Get database connection
            $pdo = DB::connection()->getPdo();

            // Prepare statement with parameters
            $stmt = $pdo->prepare("INSERT INTO images (event_id, name, content_type, data, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?) RETURNING id");

            // Get values
            $eventId = $event->id;
            $name = $file->getClientOriginalName();
            $contentType = $file->getClientMimeType();
            $data = file_get_contents($file->getRealPath());
            $now = now()->format('Y-m-d H:i:s');

            // Bind parameters - important to use PDO::PARAM_LOB for binary data
            $stmt->bindParam(1, $eventId);
            $stmt->bindParam(2, $name);
            $stmt->bindParam(3, $contentType);
            $stmt->bindParam(4, $data, \PDO::PARAM_LOB);
            $stmt->bindParam(5, $now);
            $stmt->bindParam(6, $now);

            // Execute and get ID
            $stmt->execute();
            $id = $stmt->fetchColumn();

            Log::debug('Image saved with PDO', [
                'id' => $id,
                'file_name' => $name,
                'content_type' => $contentType,
                'data_size' => strlen($data)
            ]);

            // Return Eloquent model
            return Image::find($id);

        } catch (\Exception $e) {
            Log::error('Exception in ImageRepository::saveImage: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }



    /**
     * Find all images by event ID
     *
     * @param int $eventId The event ID
     * @return array Array of Image objects
     */
    public function findAllByEvent($eventId)
    {
        try {
            Log::debug('ImageRepository::findAllByEvent called', [
                'event_id' => $eventId
            ]);

            // Using raw PDO to match your style for binary data handling
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("SELECT * FROM images WHERE event_id = ? ORDER BY created_at");
            $stmt->bindParam(1, $eventId);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $images = [];
            foreach ($results as $row) {
                $image = new Image();
                $image->id = $row['id'];
                $image->event_id = $row['event_id'];
                $image->name = $row['name'];
                $image->content_type = $row['content_type'];

                // Handle binary data - convert to string if it's a resource
                if (isset($row['data']) && is_resource($row['data'])) {
                    $image->data = stream_get_contents($row['data']);
                } else {
                    $image->data = $row['data'];
                }

                $image->created_at = $row['created_at'];
                $image->updated_at = $row['updated_at'];

                $images[] = $image;
            }

            Log::debug('Images found for event', [
                'event_id' => $eventId,
                'count' => count($images)
            ]);

            return $images;
        } catch (\Exception $e) {
            Log::error('Exception in ImageRepository::findAllByEvent: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

}

