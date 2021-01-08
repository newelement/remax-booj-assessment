<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarvelApiService
{

    private static $ts;

    private static function endPoint()
    {
        return 'https://gateway.marvel.com/v1/public/comics';
    }

    private static function createHash()
    {
        self::$ts = microtime();
        $hashed = md5(self::$ts.env('MARVEL_PRIVATE_KEY').env('MARVEL_PUBLIC_KEY'));

        return $hashed;
    }

    public static function search($q, $offset = 0)
    {
        $hash = self::createHash();
        $url = self::endPoint().'?format=comic&formatType=comic&titleStartsWith='.$q.'&ts='.self::$ts.'&offset='.$offset.'&apikey='.env('MARVEL_PUBLIC_KEY').'&hash='.$hash;

        $response = Http::get($url);

        return $response->json();

    }

    public static function getComic($id)
    {
        $hash = self::createHash();
        $url = self::endPoint().'/'.$id.'?&ts='.self::$ts.'&apikey='.env('MARVEL_PUBLIC_KEY').'&hash='.$hash;

        $response = Http::get($url);

        return $response->json();

    }

}
