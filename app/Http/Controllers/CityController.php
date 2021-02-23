<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\City;
use App\Http\Requests;
use DateTime;
use DB;
use URL;
use Auth;
use Mail;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('app.cities list');
        $cities = DB::table('cities')->
        select(
            'cities.*',
            'regions.name as region_name'
        )->
        join('regions', 'regions.id', '=', 'cities.region_id')->
        where('regions.id', '=', 13)->
        get()->toArray();
        $regions = DB::table('regions')->get()->toArray();
        return view('city.list', compact('title', 'cities', 'regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('app.adding new cities');
        $regions = DB::table('regions')->get()->toArray();
        return view('city.add', compact('title', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->get('name');
        $region = $request->get('region');
        $city = new City;
        $city->name = $name;
        $city->region_id = $region;
        $city->save();
        return redirect('city/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
