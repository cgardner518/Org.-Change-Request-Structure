<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Gate;
use File;
use Storage;
use App\Email;
use App\Project;
use App\EOSRequest;
use Illuminate\Http\Request;
use App\Http\Requests\EditEosRequest;
use App\Http\Requests\CreateEosRequest;

class EOSRequestsController extends Controller
{
    public function index()
    {
        $eosrequests = EOSRequest::with('users')->get();

        $user = Auth::user();

        $projects = Project::allProjects();

      if(Gate::allows('eosAdmin')){
        return view('requests.index', compact('eosrequests', 'user', 'projects'));
      }
      return view('requests.limited.index', compact('eosrequests', 'user', 'projects'));
    }

    public function show($id)
    {
      $eos = EOSRequest::findOrFail($id);

      return view('requests.show', compact('eos'));
    }

    public function reject(Request $request){
      // dd($request->all());
      $id = $request->id;
      $modalId = $request->modalId;

      return view('requests.modals.reject', compact('modalId', 'id'));
    }

    public function rejected($id, Request $request)
    {
      // dd($request->message);
      $eos = EOSRequest::find($id);

      $email = new Email;
      $email->user_id = $eos->users->id;
      $email->email_message = $request->message;
      $email->save();

      $eos->status = 3;
      $eos->save();

      return redirect('/requests');
    }

    public function change($id)
    {
      $eos = EOSRequest::find($id);
      $eos->status ++;
      $eos->save();
      Auth::user()->notify(new \FlashWarning("The status has been changed for ".$eos->name));

      return redirect('/requests');
    }

    public function create(Request $request)
    {
      $eos = new EOSRequest;

      $projects = Project::projectsForUser();
      $projects[0] = 'Not a project';
      ksort($projects);

      return view('requests.create', compact('eos', 'projects'));
    }

    public function store(CreateEosRequest $request)
    {
      Auth::user()->notify(new \FlashSuccess("Your request has been submitted."));

      $thisRequest = $request->all();

      // Get uploaded file info
      $fileName = time() . '-' . $request->file('stl')->getClientOriginalName();
      $request->file('stl')->storeAs('stlFiles', $fileName);
      $thisRequest['stl'] = $fileName;

      // Save the EOS Request, save the world
      $eos = EOSRequest::create($thisRequest);
      $eos->user_id = Auth::user()->id;
      $eos->save();

      return redirect('/requests');
    }

    public function download($file_name)
    {
      $file = '../storage/app/stlFiles/'. $file_name;
      if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
      }

    }

    public function edit($id)
    {
      $eos = EOSRequest::findOrFail($id);

      $projects = Project::projectsForUser($eos->user_id);
      $projects[0] = 'Not A Project';
      ksort($projects);

      return view('requests.edit', compact('eos', 'projects'));
    }

    public function update(EditEosRequest $request, $id)
    {
      // dd($request->all());
      Auth::user()->notify(new \FlashSuccess($request->name ." has been updated."));

      $eos = EOSRequest::findOrFail($id);

      // Change status from string to integer
      switch ($request->status){
        case 'pending':
          $request->status = 0;
          break;
        case 'in-process':
          $request->status = 1;
          break;
        case 'complete':
          $request->status = 2;
          break;
        default:
          $request->status = $eos->status;
      }
      // I set the status in the switch stateent so I'll leave it out in the update method
      $collection = collect($request->all());
      $eos->update($collection->forget('status','stl')->toArray());

      $eos->status = $request->status;
      $eos->save();

       return redirect('/requests');
    }

    public function destroy($id)
    {
      Auth::user()->notify(new \FlashError("Your request has been submitted."));

      EOSRequest::destroy($id);

      return redirect('/requests');
    }
    public function loggery()
    {
      // Auth::loginUsingId('855bf786-c83c-11e5-a306-08002777c33d');  // Donny Developer
  	  //  Auth::loginUsingId('c5ad9b2d-b59e-11e6-8fb9-0aad45e20ffe');  // Sampson
      	Auth::loginUsingId('5f23d3c6-b1b3-11e6-8fb9-0aad45e20ffe'); // CTG
        // Auth::loginUsingId('48356e60-b576-11e6-8fb9-0aad45e20ffe');  // Michael Jackson

      return $this->index();
    }

    public function solo(){
       Auth::loginUsingId('9a2fe30b-bbc7-11e6-8fb9-0aad45e20ffe');  // Ben Solo
      return $this->index();
    }

}
