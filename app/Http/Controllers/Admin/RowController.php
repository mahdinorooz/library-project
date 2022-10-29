<?php

namespace App\Http\Controllers\Admin;

use App\Models\Row;
use Illuminate\Http\Request;
use App\Http\Resources\RowResource;
use App\Http\Controllers\Controller;

class RowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Row::all();
        return RowResource::collection($rows);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $row = Row::create(['name' => $request->name]);
        return RowResource::make($row);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Row $row)
    {
        $row = Row::find($row);
        return RowResource::make($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Row $row)
    {
        $row->update(['name' => $request->name]);
        $rows = Row::all();
        return RowResource::collection($rows);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Row $row)
    {
        $row->delete();
        $rows = Row::all();
        return RowResource::collection($rows);
    }

    public function columnRow(Row $row, Request $request)
    {
        $row->columns()->sync($request->columns);
        $rows = Row::all();
        return RowResource::collection($rows);
    }
}
