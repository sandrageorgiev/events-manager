<?php

namespace App\Dto;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class CreateEventDto
{
    public UploadedFile $file;
    public array $files;
    public string $name;
    public string $description;
    public string $longitude;
    public string $latitude;
    public string $category;
    public string $tagsNames;
    public Carbon $dateStart;
    public Carbon $dateFinish;
    public Carbon $timeStart;
    public Carbon $timeFinish;
    public string $meetingUrl;
    public string $type;
    public float $price;
    public int $maxPeople;

    /**
     * CreateEventDTO constructor.
     *
     * @param UploadedFile $file
     * @param array $files
     * @param string $name
     * @param string $description
     * @param string $longitude
     * @param string $latitude
     * @param string $category
     * @param string $tagsNames
     * @param Carbon $dateStart
     * @param Carbon $dateFinish
     * @param Carbon $timeStart
     * @param Carbon $timeFinish
     * @param string $meetingUrl
     * @param string $type
     * @param float $price
     * @param int $maxPeople
     */
    public function __construct(
        UploadedFile $file,
        array $files,
        string $name,
        string $description,
        string $longitude,
        string $latitude,
        string $category,
        string $tagsNames,
        Carbon $dateStart,
        Carbon $dateFinish,
        Carbon $timeStart,
        Carbon $timeFinish,
        string $meetingUrl,
        string $type,
        float $price,
        int $maxPeople
    ) {
        $this->file = $file;
        $this->files = $files;
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
        $this->price = $price;
        $this->maxPeople = $maxPeople;
    }

    /**
     * This method can be used to convert the incoming request to the DTO
     * @param $request
     * @return self
     */
    public static function fromRequest($request)
    {
        return new self(
            $request->file('file'),
            $request->file('files'),
            $request->input('name'),
            $request->input('description'),
            $request->input('longitude'),
            $request->input('latitude'),
            $request->input('category'),
            $request->input('tagsNames'),
            Carbon::parse($request->input('dateStart')),
            Carbon::parse($request->input('dateFinish')),
            Carbon::parse($request->input('timeStart')),
            Carbon::parse($request->input('timeFinish')),
            $request->input('meetingUrl'),
            $request->input('type'),
            $request->input('price'),
            $request->input('maxPeople')
        );
    }
}
