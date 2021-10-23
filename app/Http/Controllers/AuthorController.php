<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return Author::all();
    }
    //
}
