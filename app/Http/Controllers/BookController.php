<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		//
	}

	/**
	 * Return Books list
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(){
		return $this->successResponse(Book::all());
	}

	/**
	 * Create an instance of Book
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request){
		$this->validate($request, [
			'title'=>'required|max:255',
			'description'=>'required|max:255',
			'price'=>'required|min:1',
			'author_id'=>'required|min:1',
		]);
		$book=Book::create($request->all());
		return $this->successResponse($book, Response::HTTP_CREATED);
	}

	/**
	 * Return an specific Book
	 * @param $author
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($book){
		return $this->successResponse(Book::findOrFail($book));
	}

	/**
	 * Update the information of an existing Book
	 * @param Request $request
	 * @param $book
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function update(Request $request, $book){
		$this->validate($request, [
			'title'=>'max:255',
			'description'=>'max:255',
			'price'=>'min:1',
			'author_id'=>'min:1',
		]);
		$book=Book::findOrFail($book);
		$book->fill($request->all());
		if($book->isClean()){
			return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
		}
		$book->save();
		return $this->successResponse($book);
	}

	/**
	 * Remove on existing Book
	 * @param $book
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($book){
		$book = Book::findOrFail($book);
		$book->delete();
		return $this->successResponse($book);
	}
}
