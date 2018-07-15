<?php

namespace App\Http\Controllers;

use App\Journey;
use App\User;
use Illuminate\Http\Request;

class JourneyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Journey::with('user')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'start_location' => 'required|min:2|max:255',
            'end_location' => 'required|min:2|max:255',
            'date' => 'required|date|before:now',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i',
            'driver_name' => 'nullable|max:255',
            'notes' => 'nullable|max:2048',
        ]);        
        
        $journey = $request
            ->user()
            ->journeys()
            ->create($data);
        
        return $journey->load('user');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Journey  $journey
     * @return \Illuminate\Http\Response
     */
    public function show(Journey $journey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Journey  $journey
     * @return \Illuminate\Http\Response
     */
    public function edit(Journey $journey)
    {
        return view('journey.edit', ['journey' => $journey]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Journey  $journey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Journey $journey)
    {
        $data = $request->validate([
            'start_location' => 'required|min:2|max:255',
            'end_location' => 'required|min:2|max:255',
            'date' => 'required|date|before:now',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i',
            'driver_name' => 'nullable|max:255',
            'notes' => 'nullable|max:2048',
        ]);
        
        $journey
            ->fill($data)
            ->save();

        return $journey->load('user');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Journey  $journey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Journey $journey)
    {
        $journey->delete();
    }
}
