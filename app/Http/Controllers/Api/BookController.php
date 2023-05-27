<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Book\IndexBookRequest;
use App\Http\Requests\Api\Book\StoreBookRequest;
use App\Http\Requests\Api\Book\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexBookRequest $request)
    {
        $page_size = $request->query('page_size', 10);
        $books = Book::paginate($page_size);

        return response()->json($books, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->only(['title', 'author', 'published_year', 'genre', 'stock']);
        $book = Book::create($data);

        return response()->json($book, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json($book, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $data = $request->only(['title', 'author', 'published_year', 'genre', 'stock']);
        $book->updated($data);

        return response()->json($book, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(Response::HTTP_OK);
    }
}
