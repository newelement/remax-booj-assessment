<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MarvelApiService;
use App\Models\User;
use App\Models\Book;

class ReadingListController extends Controller
{
    /**
     * Search the comics.
     *
     * @param  $request
     * @return json
     */
    public function search(Request $request)
    {
        $q = $request->q;

        $results = MarvelApiService::search($q);

        return response()->json($results);
    }

    /**
     * Get the comic.
     *
     * @param  int $id
     * @return view
     */
    public function getComic(Request $request, $id)
    {
        $results = MarvelApiService::getComic($id);

        if( $results['code'] !== 200 ){
            abort(404);
        }

        // Does this comic exist in reading list?
        $comicId = $results['data']['results'][0]['id'];
        $hasComic = Book::where(['user_id' => Auth()->user()->id, 'comic_id' => $comicId ])->first();

        $results['hasComic'] = $hasComic? $hasComic : false;

        return view('comic', $results);
    }

    public function getReadingList(Request $request)
    {

        $dir = $request->dir? $request->dir : 'asc';
        $sort = $request->sort? $request->sort : 'ordinal';

        $items = Book::where('user_id', Auth()->user()->id )->orderBy($sort, $dir)->get();

        return response()->json(['items' => $items]);
    }

    public function addToReadingList(Request $request)
    {
        $validatedData = $request->validate([
            'comic_id' => 'required',
        ]);

        $comicId = $request->comic_id;

        // Does the user already have this added? Just act like they added it for now...
        $hasComic = Book::where(['user_id' => Auth()->user()->id, 'comic_id' => $comicId ])->first();

        if( $hasComic ){
            return response()->json(['dup' => true]);
        }

        $comic = MarvelApiService::getComic($comicId);

        if( $comic['code'] === 200 ){

            $title = $comic['data']['results'][0]['title'];
            $cover = $comic['data']['results'][0]['thumbnail']['path'].'.'.$comic['data']['results'][0]['thumbnail']['extension'];

            $book = new Book;
            $book->user_id = Auth()->user()->id;
            $book->comic_id = $comicId;
            $book->title = $title;
            $book->cover = $cover;
            $book->save();

            return response()->json(['dup' => false, 'id' => $book->id , 'comic_id' => $comicId, 'title' => $title, 'cover' => $cover, 'ordinal' => 0 ]);

        }

        return response()->json(['success' => false], 400);

    }

    public function orderReadingList(Request $request)
    {
        $ids = $request->ids;
        $i = 0;
        foreach( $ids as $id ){

            $book = Book::where([ 'id' => $id, 'user_id' => Auth()->user()->id])->first();
            $book->ordinal = $i;
            $book->save();

            $i++;
        }

        return response()->json(['success' => true]);
    }

    public function removeFromReadingList(Request $request)
    {
        $validatedData = $request->validate([
            'id.*' => 'required_unless:comic_id.*',
            'comic_id.*' => 'required_unless:id.*'
        ]);

        $id = $request->id;
        $comicId = $request->comic_id;

        if( $id ){
            $book = Book::where([ 'id' => $id, 'user_id' => Auth()->user()->id ])->first();
        } else {
            $book = Book::where([ 'comic_id' => $comicId, 'user_id' => Auth()->user()->id ])->first();
        }

        $book->delete();

        return response()->json(['success' => true]);
    }
}
