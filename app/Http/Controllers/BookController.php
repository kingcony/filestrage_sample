<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use App\Services\BookService;

class BookController extends Controller
{
    protected $serv;

    public function __construct(BookService $_serv) {
        $this->serv = $_serv;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::get();
        return view("books.index",["books"=>$books]);
    }

    public function views()
    {
        $books = Book::get();
        return view("books.view",["books"=>$books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("books.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book = new Book();
        $book->title = $request->title;
        $book->memo = $request->memo;
        $book->save();

        return redirect(route("books.edit",$book->id))->with("flash_message","保存しました");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        return view("books.edit",["book"=>$book]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $book->title = $request->title;
        $book->memo = $request->memo;
        $book->save();
        return redirect(route("books.edit",$book->id))->with("flash_message","保存しました");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect(route("books.index"))->with("flash_message","削除しました");
    }

    public function uploadImg(Request $request, Book $book) {
        $book->img = $this->serv->uploadImg($request,$book);
        $book->save();
        return response()->json(["ret"=>"true"]);
    }
}
