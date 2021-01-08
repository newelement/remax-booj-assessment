<template>

</template>

<script>
const axios = require('axios');
const default_layout = "default";
const HTTP = axios.create();

export default {
    computed: {},
    data() {
        return {
            searchQuery: '',
        }
    },
    methods: {
        searchComics(){
            let $searchButton = document.querySelector('.search-button');
            let formData = new FormData();
            this.$root.noResults = false;

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
                        this.$root.noResults = true;
                    }

                })
                .catch(e => {
                    $searchButton.innerText = 'Search Comics';
                    $searchButton.disabled = false;
                    $searchButton.classList.remove('disabled');
                });

            }
        }
    }
};
</script>
