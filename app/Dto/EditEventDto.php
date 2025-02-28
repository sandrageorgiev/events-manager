<?php

namespace App\Dto;

use app\Category;

use Illuminate\Support\Carbon;

class EditEventDto
{
    public string $name;
    public string $description;
    public string $longitude;
    public string $latitude;
    public Category $category; // Using CategoryEnum
    public string $tagsNames;
    public Carbon $dateStart;
    public Carbon $dateFinish;
    public string $timeStart;
    public string $timeFinish;
    public string $meetingUrl;
    public string $type;

    // Constructor to initialize the DTO
    public function __construct(
        string $name,
        string $description,
        string $longitude,
        string $latitude,
        Category $category, // CategoryEnum type
        string $tagsNames,
        Carbon $dateStart,
        Carbon $dateFinish,
        string $timeStart,
        string $timeFinish,
        string $meetingUrl,
        string $type
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->category = $category;
        $this->tagsNames = $tagsNames;
        $this->dateStart = $dateStart;
        $this->dateFinish = $dateFinish;
        $this->timeStart = $timeStart;
        $this->timeFinish = $timeFinish;
        $this->meetingUrl = $meetingUrl;
        $this->type = $type;
    }

    // Static method to create a DTO from an array or request data
    public static function fromRequest(array $data): self
    {
        return new self(
            $data['name'],
            $data['description'],
            $data['longitude'],
            $data['latitude'],
            Category::from($data['category']), // Convert string to enum
            $data['tagsNames'],
            Carbon::parse($data['dateStart']),
            Carbon::parse($data['dateFinish']),
            $data['timeStart'],
            $data['timeFinish'],
            $data['meetingUrl'],
            $data['type']
        );
    }
}
