<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FieldTimeEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        return view('field.time_entry.index', compact('data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
    public function clockoutSurvay(Request $request)
    {
        $data = array();
        return view('field.time_entry.clockout_survay', compact('data'));
    }

}
