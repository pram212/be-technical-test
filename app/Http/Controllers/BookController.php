<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return BookResource::collection(Book::cursorPaginate(15));
        } catch (\Throwable $th) {
            return response([
                'message' => 'internal server error' . $th->getMessage()
            ], status:500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $book = Book::create($request->all());

            DB::commit();

            return response(content:[
                'message' => 'data saved successfully',
                'data' => $book
            ], status:201);

        } catch (\Throwable $th) {
            Db::rollBack();
            return response([
                'message' => 'internal server error' . $th->getMessage()
            ], status:500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = Book::find($id);
        
            if (!$book) return response(content:["message" => "Data not found"], status:404);

            $book = new BookResource($book);

            return response(content:["data" => $book]);

        } catch (\Throwable $th) {

            return response(['message' => "Internal server error"], status:500);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, $id)
    {
        try {
            $book = Book::find($id);
        
            if (!$book) {
                return response(content:["message" => "Data not found"], status:404);
            }

            DB::beginTransaction();
            
            $book->update($request->all());

            DB::commit();

            return response(content:[
                'message' => 'data updated successfully',
                'data' => $book
            ], status:201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response(content: [ "message" =>'Internal server error' ], status:500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $book = Book::find($id);
        
            if (!$book) {
                return response(content:["message" => "Data not found"], status:404);
            }

            DB::beginTransaction();

            $book->delete();

            DB::commit();

            return response(content:['message' => 'data deleted successfully'], status:200);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response(content:['message' => 'Internal server error'], status:500);
        }
        
    }

    public function getBookByAuthor($id) 
    {
        $bookQuery = Book::whereRelation('author', 'id', $id)->paginate(10);
        return BookResource::collection($bookQuery);
    }
}
