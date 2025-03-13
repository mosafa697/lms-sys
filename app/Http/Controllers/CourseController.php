<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subscription;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $course = Course::create($request->only(['title', 'description', 'price']));
        return response()->json($course);
    }

    public function update(Request $request, Course $course)
    {
        $course->update($request->only(['title', 'description', 'price']));
        return response()->json($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }

    public function index()
    {
        return response()->json(Course::all());
    }

    public function show(Course $course)
    {
        $user = auth()->user();

        $isSubscribed = Subscription::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();

        if (!$isSubscribed) {
            return response()->json(['error' => 'Access denied. Subscription required.'], 403);
        }

        return response()->json($course);
    }
}
