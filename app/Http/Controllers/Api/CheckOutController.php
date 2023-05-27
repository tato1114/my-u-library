<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckOut\IndexCheckOutRequest;
use App\Http\Requests\Api\CheckOut\UpdateCheckOutRequest;
use App\Models\Book;
use App\Models\CheckOut;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCheckOutRequest $request)
    {
        $page_size = $request->query('page_size', 10);
        $filter = $request->query('filter', '');
        $check_outs = CheckOut::filter($filter)
            ->with(['book', 'user'])
            ->orderBy('check_out_date', 'desc')
            ->paginate($page_size);

        return response()->json($check_outs, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Book $book)
    {
        if ($book->stock == 0) {
            return response()->json(['error' => 'Book out of stock'], Response::HTTP_METHOD_NOT_ALLOWED);
        }
        $data = [
            'book_id' => $book->id,
            'user_id' => auth()->user()->id,
        ];
        $check_out = CheckOut::create($data);
        $book->decrement('stock');

        return response()->json($check_out->load(['book', 'user']), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(CheckOut $check_out)
    {
        return response()->json($check_out->load('book'), Response::HTTP_OK);
    }

    public function update(CheckOut $check_out)
    {
        if ($check_out->status == 'returned') {
            return response()->json(['error' => 'Book already returned'], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        $check_out->status = 'returned';
        $check_out->return_date = now();
        $check_out->save();
        $book = $check_out->book;
        $book->increment('stock');

        return response()->json($check_out, Response::HTTP_OK);
    }
}
