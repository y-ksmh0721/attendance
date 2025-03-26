<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $works = Work::all();

        return view('contact.index', compact('works', 'user'));
    }
    public function confirm(ContactRequest $request){

        return view('contact.confirm', compact('request'));
    }
    public function complete(Request $request){
        $user = $request->user();

        $contact = new Contact();
        $contact->user_name = $user->name;
        $contact->name = $request->name;
        $contact->site = $request->site;
        $contact->title = $request->title;
        $contact->content = $request->content;
        $contact->save();

        return redirect()->route('contact.list');
    }
    public function list(){
        $contacts = new Contact() ->orderby('created_at', 'desc')->paginate(12);
        return view('contact.list', compact('contacts'));
    }

    public function info($id){
        $contact = Contact::find($id);
        // dd($contact);

        return view('contact.info', compact('contact'));
    }
}
