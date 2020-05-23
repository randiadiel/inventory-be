<?php

namespace App\Http\Controllers;
use App\Item;
use App\Http\Resources\Item as ItemResource;
use App\Http\Resources\ItemCollection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){
        return new ItemCollection(Item::all());
    }
    public function show($id){
        return new ItemResource(Item::findOrFail($id));
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
        ]);
        do{
            $id = Str::random(10);
        }while(Item::find($id) != null);
        $id = array('id' => $id);
        $all = $id + $request->all();
        $item = Item::create($all);

        return (new ItemResource($item))->response()->setStatusCode(201);
    }
    public function update($id, Request $request){
        $item = Item::findOrFail($id);
        $item->name = $request->name;
        $item->qty = $request->qty;
        $item->price = $request->price;
        $item->date_registered = $request->date_registered;
        $item->box_status = $request->box_status;
        $item->location = $request->location;
        $item->save();
        return new ItemResource($item);
    }
    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(null, 204);
    }
}
