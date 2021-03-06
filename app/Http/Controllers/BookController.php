<?php

namespace App\Http\Controllers;

use App\Entities\Book;
use App\Http\Requests\StoreBook;
use App\Service\BookService;
use App\Support\AppController;
use App\Repositories\BookAudioRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends AppController
{
    /**
     * @var BookService
     */
    private $bookService;

    /**
     * @var BookAudioRepository
     */
    private $audioRepository;

    /**
     * BookController constructor
     *
     * @param BookService $bookService
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('book');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBook|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBook $request)
    {

        try {
            $this->bookService->addBook($request);
            $response['success'] = true;
        }catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return new Response($response);

    }

    /**
     * Display the specified resource
     *
     * @param $id
     * @param BookAudioRepository $audioRepository
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, BookAudioRepository $audioRepository, Request $request)
    {
        /** @var Book $book */
        $book = $this->bookService->getBook($id);
        if ($book)
        {
            $request->request->set('book', $id);

            $feed = $audioRepository->getUserFeed($request, null, 5);

            return view('book',[
                'book'       => $book,
                'count'      => $feed->count(),
                'total'      => $feed->total(),
                'content'    => $feed->getCollection(),
                'pagination' => $feed->appends($request->except('page'))
            ]);
        }

        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
