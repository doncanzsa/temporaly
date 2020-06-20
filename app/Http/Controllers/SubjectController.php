<?php

namespace App\Http\Controllers;

use App\Subject;
use App\Http\Resources\SubjectCollection;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return Subject::all();
    }

    public function save(Request $request)
    {
        if ($request->id) {
            $subject = Subject::find($request->id);
            if ($subject) {
                $subject->name          = $request->name;
                $subject->description   = $request->description;
            } else {
                $subject = new Subject([
                    'name'          => $request->name,
                    'description'   => $request->description
                ]);
            }
        } else {
            $subject = new Subject([
                'name'          => $request->name,
                'description'   => $request->description
            ]);
        }
        $subject->save();
        return response(['success' => $subject]);
    }

    public function delete(Request $request)
    {
        return $request->id;
        if ($request->id) {
            $subject = Subject::find($request->id);
            if ($subject) {
                $subject->delete();
                return response(['success' => $subject]);
            }
        }
        return response([]);
    }

    public function store(Request $request)
    {
        //
    }

   

    public function edit(Subject $subject)
    {
        //
    }

    public function update(Request $request, Subject $subject)
    {
        //
    }

    public function destroy(Subject $subject)
    {
        //
    }
}
