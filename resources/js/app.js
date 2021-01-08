require('./bootstrap');
import Vue from 'vue';
const _ = require('lodash');
const axios = require('axios');
import Sortable from 'sortablejs';
import draggable from 'vuedraggable';

const HTTP = axios.create();

const app = new Vue({
    el: '#main',
    components: {
        draggable,
    },
    data: {
        searchQuery: '',
        readingList: [],
        searchList: [],
        noResults: false,
        axiosConfig: { headers: {
            Authorization: 'Bearer '+localStorage.getItem('token'),
            'Content-type': 'application/json',
            Accept: 'application/json'
        }},
        dir:'asc',
        drag: false
    },
    filters: {
        https(value){
            return value.replace('http', 'https');
        }
    },
    methods: {
        searchComics(){
            let $searchButton = document.querySelector('.search-button');
            this.noResults = false;

            if( this.searchQuery.length > 2  ){
                $searchButton.innerText = 'Searching...';
                $searchButton.disabled = true;
                $searchButton.classList.add('disabled');

                HTTP.get( '/api/search-comics?q='+this.searchQuery )
                .then(response => {

                    $searchButton.innerText = 'Search Comics';
                    $searchButton.disabled = false;
                    $searchButton.classList.remove('disabled');

                    console.log(response.data.code);
                    console.log(response.data.data.results);

                    this.$root.searchList = response.data.data.results;

                    if( !this.$root.searchList.length ){
                        this.noResults = true;
                    }

                })
                .catch(e => {
                    $searchButton.innerText = 'Search Comics';
                    $searchButton.disabled = false;
                    $searchButton.classList.remove('disabled');
                });

            }
        },
        getReadingList(){
            HTTP.get( '/api/comics' )
            .then(response => {
                this.readingList = response.data.items;
            })
            .catch(e => {
            });
        },
        addToReadingList(comicId, e){
            let formData = new FormData();
            formData.append('_token', window.csrfToken );
            formData.append( 'comic_id', comicId );

            this.showActionMessage('Adding to reading list...');

            HTTP.post( '/api/comics', formData )
            .then(response => {
                this.showActionMessage('Added to Reading List!');
                setTimeout( () => {
                    this.hideActionMessage();
                }, 1500 );

                if( !response.data.dup ){

                    this.readingList.push(response.data);

                }

            })
            .catch(e => {
                this.showActionMessage('!! Problem Adding to Reading List !!');
                setTimeout( () => {
                    this.hideActionMessage();
                }, 2500 );
            });
        },
        removeFromReadingList(item, index){
            let formData = new FormData();
            let self = this;
            formData.append('_token', window.csrfToken );
            formData.append( '_method', 'delete' );
            formData.append( 'id', item.id );

            HTTP.post( '/api/comics', formData )
            .then(response => {
                self.readingList.splice(index, 1);
            })
            .catch(e => {
            });

        },
        addRemoveItemBtn(action, id){
            if( action === 'add' ){
                let formData = new FormData();
                formData.append('_token', window.csrfToken );
                formData.append( 'comic_id', id );

                this.showActionMessage('Adding to reading list...');

                HTTP.post( '/api/comics', formData )
                .then(response => {
                    this.showActionMessage('Added to Reading List!');
                    setTimeout( () => {
                        this.hideActionMessage();
                    }, 1500 );

                    document.querySelector('.add-to-btn').classList.add('hide');
                    document.querySelector('.remove-from-btn').classList.add('show');
                    document.querySelector('.remove-from-btn').classList.remove('hide');

                })
                .catch(e => {
                    this.showActionMessage('!! Problem Adding to Reading List !!');
                    setTimeout( () => {
                        this.hideActionMessage();
                    }, 2500 );
                });
            }

            if( action === 'remove' ){
                let formData = new FormData();
                let self = this;
                formData.append('_token', window.csrfToken );
                formData.append( '_method', 'delete' );
                formData.append( 'comic_id', id );

                HTTP.post( '/api/comics', formData )
                .then(response => {

                    document.querySelector('.remove-from-btn').classList.add('hide');
                    document.querySelector('.add-to-btn').classList.add('show');
                    document.querySelector('.add-to-btn').classList.remove('hide');

                })
                .catch(e => {
                });
            }
        },
        showActionMessage(message){
            let $am = document.querySelector('.action-message');
            document.querySelector('.action-message span').innerHTML = message;
            $am.classList.add('show');
        },
        hideActionMessage(){
            let $am = document.querySelector('.action-message');
            $am.classList.remove('show');
            document.querySelector('.action-message span').innerHTML = '';
        },
        orderBy(key){
            this.dir = this.dir === 'asc'? 'desc' : 'asc';
            HTTP.get( '/api/comics?sort='+key+'&dir='+this.dir )
            .then(response => {
                this.readingList = response.data.items;
            })
            .catch(e => {
            });
        },
        dragEnd(){
            console.log('dragend');
            console.log(this.readingList);
            let formData = new FormData();
            let self = this;
            formData.append('_token', window.csrfToken );

            this.readingList.forEach( (v) => {
               formData.append( 'ids[]', v.id );
            });

            HTTP.post( '/api/comics/sort', formData )
            .then(response => {
            })
            .catch(e => {
            });
        }
    },
    mounted() {
        let $readingTable = document.querySelector('.has-reading-table');
        if( $readingTable ){
            this.getReadingList();
        }
    }
});
