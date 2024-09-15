<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return AuthorResource::collection(Author::cursorPaginate(15));
        } catch (\Throwable $th) {
            return response(content:["message" => "internal server error"], status:500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $author = Author::create($request->all());

            DB::commit();

            return response(content:[
                'message' => 'data saved successfully',
                'data' => $author
            ], status:201);

        } catch (\Throwable $th) {
            Db::rollBack();
            return response([
                'message' => 'internal server error'
            ], status:500);

        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    { 
        try {
            $author = Author::find($id);
        
            if (!$author) return response(content:["message" => "Data not found"], status:404);

            $author = new AuthorResource($author);

            return response(content:$author);

        } catch (\Throwable $th) {

            return response(['message' => "Internal server error"], status:500);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, $id)
    {
        try {
            $author = Author::find($id);
        
            if (!$author) {
                return response(content:["message" => "Data not found"], status:404);
            }

            DB::beginTransaction();
            
            $author->update($request->all());

            DB::commit();

            return response(content:[
                'message' => 'data updated successfully',
                'data' => $author
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
            $author = Author::find($id);
        
            if (!$author) {
                return response(content:["message" => "Data not found"], status:404);
            }

            DB::beginTransaction();

            $booksCount = $author->books()->count();

            if ($booksCount != 0) {
                return response([
                    'message' => "Cannot delete author because they have associated books"
                ], status:409);
            }

            $author->delete();

            DB::commit();

            return response(content:['message' => 'data deleted successfully'], status:204);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response(content:['message' => 'Internal server error'], status:500);
        }
    }
}
