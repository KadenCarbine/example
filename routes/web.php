<?php

use Illuminate\Support\Facades\Route;
use App\Models\Job;

Route::get('/', function () {
    return view('home');
});

// Index (All jobs)
Route::get('/jobs', function () {
    $jobs = Job::with('employer')->latest()->simplepaginate(5);
    return view('jobs.index', [
        'jobs' => $jobs
    ]);
});
// Create A Job
Route::get('/jobs/create', function () {
    return view('jobs.create');
});
// Stores a Post a Job to the DB
Route::post('/jobs', function () {
    request()->validate([
        'title' => ['required', 'min:3'],
        'salary' => ['required']
    ]);
    Job::create([
        'title' => request('title'),
        'salary' => request('salary'),
        // This is being hardcoded now, until we do the validation. We can grab the id from that.
        'employer_id' => 1,
    ]);
    return redirect('/jobs');
});
// Show a Job
Route::get('/jobs/{id}', function ($id) {
    $job = Job::find($id);
    return view('jobs.show', ['job' => $job]);
});
// Edit a Job
Route::get('/jobs/{id}/edit', function ($id) {
    $job = Job::find($id);
    return view('jobs.edit', ['job' => $job]);
});
// Update a Job
Route::patch('/jobs/{id}', function ($id) {
    // Validate (Duplication for now)
    request()->validate([
        'title' => ['required', 'min:3'],
        'salary' => ['required']
    ]);
    // Authorize (On Hold...)


    $job = Job::findOrFail($id);
    
    $job->update([
        'title' => request('title'),
        'salary' => request('salary')
    ]);
    
    return redirect('/jobs/' . $job->id);
});
// Destory a Job
Route::delete('/jobs/{id}', function ($id) {
    
    Job::findOrFail($id)->delete();

    return redirect('/jobs');
});
Route::get('/contact', function () {
    return view('contact');
});
