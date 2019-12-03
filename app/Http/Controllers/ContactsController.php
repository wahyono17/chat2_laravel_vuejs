<?php

namespace App\Http\Controllers;
use App\User;
use App\Message;

use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function get(){
        $contact = User::where('id','!=',auth()->id())->get();
        return response()->json($contact);
    }
    public function getMessagesFor($id){
        $messages = Message::where('from',$id)->orwhere('to',$id)->get();
        return response()->json($messages);
    }
    public function send(Request $request){
        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);
        return response()->json($message);
    }    
}
