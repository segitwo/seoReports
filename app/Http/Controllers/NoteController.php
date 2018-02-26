<?php

namespace App\Http\Controllers;

use App\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function update(Request $request){
        $noteId = $request->get('id');
        $text = $request->get('text');
        $note = Note::find($noteId);
        if($note){
            $note->setAttribute('text', $text);
            $note->save();
        }
        $data = [
            'updated_at' => Carbon::parse($note->updated_at)->format('d.m.Y')
        ];
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
