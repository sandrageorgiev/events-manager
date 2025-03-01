<?php

namespace App\Repositories\impl;

use App\Category;
use App\Models\Event;
use App\Models\Tag;
use App\Repositories\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{

    public function all(): Collection
    {
        return Event::with(['creator', 'images', 'tags'])->get();
    }

    public function find(int $id): Event
    {
        return Event::with(['creator', 'images', 'tags'])->find($id);
    }

    public function create(array $data): Event
    {
        // Ensure tags is an array
        $tagsNames = is_array($data['tags']) ? $data['tags'] : explode(',', $data['tags']);
        $tags = collect();

        foreach ($tagsNames as $tagName) {
            $tagName = trim($tagName);
            $tags->push(Tag::query()->firstOrCreate(['name' => $tagName]));
        }

        $event = Event::query()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'available_tickets' => $data['available_tickets'],
            'longitude' => $data['longitude'],
            'latitude' => $data['latitude'],
            'date_start' => $data['date_start'],
            'date_finish' => $data['date_finish'],
            'time_start' => $data['time_start'],
            'time_finish' => $data['time_finish'],
            'category' => Category::from($data['category'])->value,
            'type' => $data['type'],
            'price' => $data['price'],
            'meeting_url' => $data['meeting_url'],
            //'creator_id' => auth()->id() ?? null,
             'creator_id' => 2,// Use authenticated user if available
        ]);

        // Attach tags using names instead of IDs
        foreach ($tags as $tag) {
            $event->tags()->attach($tag->name);
        }

        return $event;

    }

    public function update(Event $event, array $data): Event
    {
        $tagsNames = is_array($data['tags']) ? $data['tags'] : explode(',', $data['tags']);
        $tags = collect();

        foreach ($tagsNames as $tagName) {
            $tagName = trim($tagName);
            $tags->push(Tag::query()->firstOrCreate(['name' => $tagName]));
        }

        // Update the event fields
        $event->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'available_tickets' => $data['max_people'],
            'longitude' => $data['longitude'],
            'latitude' => $data['latitude'],
            'date_finish' => $data['date_finish'],
            'date_start' => $data['date_start'],
            'time_start' => $data['time_start'],
            'time_finish' => $data['time_finish'],
            'category' => Category::from($data['category'])->value,
            'type' => $data['type'],
            'price' => $data['price'],
            'meeting_url' => $data['meeting_url'],
            // 'creator_id' => null, // Assuming creator doesn't change
        ]);

        // Sync the tags with the event (this will ensure that only the tags in the array are attached)
        $event->tags()->sync($tags->pluck('name')->toArray());

        return $event;
    }

    public function delete(Event $event): bool
    {
        // Optionally, detach the tags if you want to remove the relationship from the pivot table manually
        $event->tags()->detach();

        // Delete the event
        return $event->delete();
    }
}
