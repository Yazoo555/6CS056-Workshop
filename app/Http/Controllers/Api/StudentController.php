<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(): AnonymousResourceCollection
    {
        $students = Student::all();

        return StudentResource::collection($students);
    }

    /**
     * Store a newly created student.
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = Student::create($request->validated());

        return (new StudentResource($student))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student): StudentResource
    {
        return new StudentResource($student);
    }

    /**
     * Update the specified student.
     */
    public function update(UpdateStudentRequest $request, Student $student): StudentResource
    {
        $student->update($request->validated());

        return new StudentResource($student->fresh());
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json(null, 204);
    }
}
