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
    public function getAll(){
        $contacts=User::where('id','!=',auth()->id())->get();//ini ori ambil semua contact
        //return response()->json($contacts);//json karena di ambil oleh axios
        //dd($contacts);
        return view('allContacts', ['contacts'=>$contacts]);
    }
    public function getAlljson(){
        $contacts=User::where('id','!=',auth()->id())->get();//ini ori ambil semua contact
        return response()->json($contacts);//json karena di ambil oleh axios
        //dd($contacts);
        //return view('allContacts', ['contacts'=>$contacts]);
    }
    public function contactbyId($id){
        $contact = User::where('id',$id)->get();
        return view ('chatwith',compact('contact'));
    }
    public function getbyName($name){
        $contacts=User::where('id','!=',auth()->id())
            ->where('name','LIKE',"%$name%")
            ->get();
        return response()->json($contacts);//json karena di ambil oleh axios
    }
    public function get(){
        //ambil hanya contact yang pernah chat, bukan semua user spt di tutorial
        $contacts = User::select('users.id','users.name','users.email','users.profile_image')
            ->join('messages',function($join){
                $join->on('users.id','messages.from');
                $join->orOn('users.id','messages.to');
                })
            ->where(function($query){
                $query->where('messages.from',auth()->id())->orWhere('messages.to',auth()->id());         
                })
            ->where('users.id','!=',auth()->id())    
            ->groupBy('users.id','users.name','users.email','users.profile_image')
            //->orderBy('messages.id','desc')
            ->get();
        
        
        //$contacts=User::where('id','!=',auth()->id())->get();//ini ori ambil semua contact
        //ini untuk count yang unread
        $unreadId = DB::table('messages')
            ->select('from as sender_id',DB::raw('count("from") as messages_count'))
            ->where('to',auth()->id())
            ->where('read',false)
            ->groupBy('from')
            ->get();
        //ambil chat terakhir dari from 
        //dd($unreadId);
        $messageId = Message::whereRaw('id in (select max(`id`) from messages group by `from`)')->get();
        /*
        //yang ini tidak berfungsi
        $unreadId = Message::select('from as sender_id',\DB::raw('count("from")as messages_count'))
            ->where('to',auth()->id())
            ->where('read',false)
            ->groupBy('from')
            ->get();
        dd($unreadId);
        */
        $contacts = $contacts->map(function($contact) use($unreadId, $messageId){
           
            $contactUnread = $unreadId->where('sender_id',$contact->id)->first(); //ambil unred
            //dd($contactUnread);
            $lastMessageId = $messageId->where('from',$contact->id)->first(); //ambil message yang terakhir dikirim
            //dd($lastMessageId);
            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0 ;  //maping ke contact
            $contact->messageid = $lastMessageId->id; //maping ke contact
            //dd($contact);
            return $contact;
        });

        //$contacts = $contacts->sortByDesc('messageid');// di urutkan berdasarkan yang terakhir kirim chat
    
        return response()->json($contacts);//json karena di ambil oleh axios
    }
    public function getMessagesFor($id){
        //$messages = Message::where('from',$id)->orwhere('to',$id)->get();//ini skrip lama untuk ambil semua
        
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
