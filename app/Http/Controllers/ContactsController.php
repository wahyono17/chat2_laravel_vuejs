<?php

namespace App\Http\Controllers;
use App\User;
use App\Message;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function get(){
        $contacts = User::where('id','!=',auth()->id())->get();
        
        $unreadId = DB::table('messages')
            ->select('from as sender_id',DB::raw('count("from") as messages_count'))
            ->where('to',auth()->id())
            ->where('read',false)
            ->groupBy('from')
            ->get();
        /*
        //yang ini tidak berfungsi
        $unreadId = Message::select('from as sender_id',\DB::raw('count("from")as messages_count'))
            ->where('to',auth()->id())
            ->where('read',false)
            ->groupBy('from')
            ->get();
        dd($unreadId);
        */
        $contacts = $contacts->map(function($contact) use($unreadId){
            $contactUnread = $unreadId->where('sender_id',$contact->id)->first();

            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0 ;  
            
            return $contact;
        });
        
        return response()->json($contacts);
    }
    public function getMessagesFor($id){
        //$messages = Message::where('from',$id)->orwhere('to',$id)->get();
        
        $messages = Message::where(function($q) use($id){
            $q->where('from',auth()->id());
            $q->where('to',$id);
        })->orwhere(function($q) use($id){
            $q->where('from',$id);
            $q->where('to',auth()->id());
        })
        ->get();//(a=1 and b=3)or(c=1 and d=2)
        
        return response()->json($messages);
    }
    public function send(Request $request){
        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);
        broadcast(new NewMessage($message));
        return response()->json($message);
    }    
}
