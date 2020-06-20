<?php

namespace App\Http\Controllers;

use App\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{

    public function index()
    {   
        return $lesson = Lesson::with('subjects')->get();
    }

    public function getBySubjectId($id)
    {   
        if ($id) {
            $lesson = Lesson::where('subject_id', $id)
            ->with('subjects')->get();
            return $lesson;
        } else {
            return response([]);
        }
    }

    public function save(Request $request )
    {
        if ($request->id) {
            $lesson = Lesson::find($request->id);
            if ($lesson) {
                $lesson->theme          = $request->theme;
                $lesson->content        = $request->content;
                $lesson->subject_id     = $request->subject_id;
            } else {
                $lesson = new Lesson([
                    'theme'             => $request->theme,
                    'content'           => $request->content,
                    'subject_id'        => $request->subject_id
                ]);
            }
        } else {
            $lesson = new Lesson([
                'theme'         => $request->theme,
                'content'       => $request->content,
                'subject_id'    => $request->subject_id
            ]);
        }
        $lesson->save();
        return response(['success' => $lesson]);
    }

    public function show(Request $request )
    {
        if ($request->id) {
            $lesson = Lesson::find($request->id);
            if ($lesson) {
                return response(['success' => $lesson]);
            } else {
                return response([]);
            }
        } else { 
            return response([]);
        }
    }

    public function delete(Request $request)
    {
        if ($request->id) {
            $lesson = Lesson::find($request->id);
            if ($lesson) {
                $lesson->delete();
                return response(['success' => $lesson]);
            }
        }
        return response([]);
    }

}
