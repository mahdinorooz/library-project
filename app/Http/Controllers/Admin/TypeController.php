<?php

namespace App\Http\Controllers\Admin;

use App\Models\Row;
use App\Models\Type;
use NumberFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TypeResource;
use App\Models\ColumnRow;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        return TypeResource::collection($types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:100000000|regex:/^[ا-ی]+$/u',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        $type = Type::create($request->all());
        ColumnRow::whereIn('id', $request->columns)->update(['type_id' => $type->id]);
        return 'done';
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

    public function columnRowList()
    {
        $columnRows = ColumnRow::all();
        $columnRowNames = [];
        foreach ($columnRows as $columnRow) {
            $rowName = $columnRow->row()->get()->pluck('name');
            $columnName = $columnRow->column()->get()->pluck('name');
            $columnRowNames[] = [ 'id' => $columnRow->id ,'value' => "ردیف" . " " . $rowName[0] . "-" . "ستون" . " " . $columnName[0]];
        }
        return response()->json($columnRowNames);
    }
}
