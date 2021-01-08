@extends('layouts.app')

@section('content')
    <main id="main" class="main max-w-screen-xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        <p>
        <a href="/reading-list">&larr; Back to Reading List</a>
        </p>

        <div class="comic-main">
            <div class="comic-cover">
                <img src="{{ str_replace('http', 'https', $data['results'][0]['images'][0]['path']) }}.{{$data['results'][0]['images'][0]['extension']}}" alt="{{ $data['results'][0]['title'] }}">
            </div>
            <div class="comic-info">
                <h1 class="text-3xl mb-2 font-semibold">{{ $data['results'][0]['title'] }}</h1>
                <p>

                    <a href="#" role="button" @click.prevent="addRemoveItemBtn('add', {{ $data['results'][0]['id'] }} )" class="action-btn add-to-btn {{ !$hasComic? 'show' : 'hide' }}">Add to Reading List</a>

                    <a href="#" role="button" @click.prevent="addRemoveItemBtn('remove', {{ $data['results'][0]['id'] }} )" class="action-btn remove-from-btn {{ !$hasComic? 'hide' : 'show' }}">Remove From Reading List</a>

                </p>

                <p>
                <strong>Published:</strong>
                <?php
                    foreach($data['results'][0]['dates'] as $date){
                        if( $date['type'] === 'onsaleDate' ){
                            echo \Carbon\Carbon::create($date['date'])->format('F j, Y');
                        }
                    }
                ?>
                <br>

                <strong>Issue:</strong> {{ $data['results'][0]['issueNumber'] }}<br>
                <strong>Pages:</strong> {{ $data['results'][0]['pageCount'] }}<br>
                <strong>ISSN:</strong> {{ $data['results'][0]['issn'] }}</p>

                <h3 class="text-lg mb-3 font-semibold">Creators</h3>
                <p class="creators">
                @foreach( $data['results'][0]['creators']['items'] as $creator )
                {{ $creator['name'] }} / {{ $creator['role'] }}<br>
                @endforeach
                </p>
            </div>
        </div>
    </main>
@endsection
