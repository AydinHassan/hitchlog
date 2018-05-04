<?php

namespace App\Http\Controllers;

use App\Journey;
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
        //
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
        $journey = new Journey;
        $journey->start_location = $request->get('start_location');
        $journey->end_location = $request->get('end_location');
        $journey->start = $request->get('start');
        $journey->end = $request->get('end');
        $journey->driver_name = $request->get('driver_name');
        $journey->notes = $request->get('notes');
        $journey->user_id = $request->user()->id;

        $journey->save();

        flash('Journey created!');
        return redirect('/');
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
        $journey->start_location = $request->get('start_location');
        $journey->end_location = $request->get('end_location');
        $journey->start = $request->get('start');
        $journey->end = $request->get('end');
        $journey->driver_name = $request->get('driver_name');
        $journey->notes = $request->get('notes');
        $journey->user_id = $request->user()->id;

        $journey->save();

        flash('Journey updated!');
        return redirect('/');        
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
        
        flash('Journey deleted!');
        
        return redirect('/');
    }
}
