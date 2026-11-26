<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(): AnonymousResourceCollection
    {
        $courses = Course::all();

        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created course.
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = Course::create($request->validated());

        return (new CourseResource($course))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): CourseResource
    {
        return new CourseResource($course);
    }

    /**
     * Update the specified course.
     */
    public function update(UpdateCourseRequest $request, Course $course): CourseResource
    {
        $course->update($request->validated());

        return new CourseResource($course->fresh());
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): JsonResponse
    {
        $course->delete();

        return response()->json(null, 204);
    }
}
