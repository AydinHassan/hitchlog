<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    protected $fillable = [
        'start_location',  
        'end_location',
        'date',
        'start_time',
        'end_time',
        'driver_name',
        'notes'
    ];
    
    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getStartTimeAttribute(string $startTime) : string
    {
        return $this->parseHourAndMinute($startTime);
    }
    
    public function getEndTimeAttribute(string $endTime) : string
    {
        return $this->parseHourAndMinute($endTime);
    }

    private function parseHourAndMinute(string $time) : string
    {
        $parts = explode(':', $time);
        return $parts[0] . ':' . $parts[1];
    }
}
