@extends('layouts.app')

@section('content')
    <main id="main" class="max-w-screen-xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-8 has-reading-table">

        <div class="comic-search mb-6">
            <form action="search" method="get" class="w-full flex lg:flex-row sm:flex-col">
                <input type="text" v-model="searchQuery" name="search" placeholder="Thor, Captain America ..." class="comic-search-input lg:mr-4 flex-grow border border-gray-500 p-3" >
                <button @click.prevent="searchComics" class="search-button inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Search Comics</button>
            </form>
        </div>

        <div v-if="searchList.length" class="search-actions text-center mb-5" v-cloak><a href="#" @click.prevent="searchList = []; searchQuery = ''; noResults = false " role="button">Clear Results</a></div>

        <div class="reading-list-section">
            <ul v-if="searchList.length" class="search-list flex flex-row flex-wrap" v-cloak>
                <li v-for="item in searchList">
                    <img :src="item.thumbnail.path+'.'+item.thumbnail.extension | https" :alt="item.title">
                    <p>@{{ item.title }}</p>
                    <div class="item-actions">
                        <a href="#" @click.prevent="addToReadingList(item.id, $event)" role="button">&plus; Add to Reading List</a>
                        <a :href="'/comics/'+item.id" role="button">View Comic &rarr;</a>
                    </div>
                </li>
            </ul>
            <div v-show="noResults" class="text-center p-6" v-cloak><p><strong>No results found.</strong></p></class>
        </div>

        <section class="reading-list-section">
            <h2 class="text-2xl font-light text-center uppercase mb-4">My Reading List</h2>

            <p v-if="!readingList.length" class="text-center" v-cloak>
                <strong>You do not have any items saved yet. Use the search above to find something you will like to read.</strong>
            </p>

            <div v-if="readingList.length" class="reading-table table mx-auto mb-3">
                <div class="flex flex-row">
                    <div style="width: 60px;"></div>
                    <div style="width: 100px;">Cover</div>
                    <div style="max-width: 300px; width: 100%;" @click="orderBy('title')" class="order-by"><strong>Title</strong></div>
                    <div style="max-width: 40px;"></div>
                </div>
            </div>

            <draggable v-model="readingList" handle=".handle" start="drag=true" @end="dragEnd" class="reading-table table-list table mx-auto" >
                <div class="flex flex-row" v-for="(comic, index) in readingList" :key="comic.id">
                    <div class="p-3 text-center" style="max-width: 50px;">
                        <span class="handle"><img src="/images/sort-circle-light.svg" alt="Sort"></span>
                    </div>
                    <div class="p-2" style="max-width: 100px;"><a :href="'/comics/'+comic.comic_id"><img :src="comic.cover | https" :alt="comic.title"></a></div>
                    <div class="p-2" style="max-width: 300px; width: 100%;"><a :href="'/comics/'+comic.comic_id" class="text-2xl font-medium">@{{comic.title}}</a></div>
                    <div class="p-2 text-center" style="max-width: 40px; margin-left: auto;"><a :href="'/comics/'+comic.comic_id" @click.prevent="removeFromReadingList(comic, index)" class="text-2xl"><span>&times;</span></a></div>
                </row>
            </draggable>

        </section>

    </main>
@endsection
