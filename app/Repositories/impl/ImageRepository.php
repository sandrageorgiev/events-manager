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

//    public function saveImage(Event $event, $file)
//    {
//        try {
//            Log::debug('ImageRepository::saveImage called', [
//                'event_id' => $event->id,
//                'file_type' => is_array($file) ? 'array' : get_class($file)
//            ]);
//
//            // Get file data and convert to base64
//            $fileContent = file_get_contents($file->getRealPath());
//            $base64Data = base64_encode($fileContent);
//
//            Log::debug('File data converted to base64', [
//                'original_length' => strlen($fileContent),
//                'base64_length' => strlen($base64Data)
//            ]);
//
//            // Create the image using standard Eloquent ORM
//            $image = Image::query()->create([
//                'event_id' => $event->id,
//                'name' => $file->getClientOriginalName(),
//                'content_type' => $file->getClientMimeType(),
//                'data' => $base64Data,
//                'is_base64' => true
//            ]);
//
//            Log::debug('Image record created', ['image_id' => $image->id]);
//            return $image;
//        } catch (\Exception $e) {
//            Log::error('Exception in ImageRepository::saveImage: ' . $e->getMessage(), [
//                'trace' => $e->getTraceAsString()
//            ]);
//            throw $e;
//        }
//    }
}
