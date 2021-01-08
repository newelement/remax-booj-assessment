<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Book;

class ReadingListTest extends DuskTestCase
{

    public function test_login_and_search()
    {
        Book::truncate();
        $user = User::where('email', env('TEST_EMAIL') )->first();

        if( !$user ){
            $user = User::create([
                'email' => env('TEST_EMAIL'),
                'name' => env('TEST_NAME'),
                'password' => bcrypt( env('TEST_PASSWORD') )
            ]);
        }

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit( '/login' )
                    ->type('email', $user->email)
                    ->type('password', env('TEST_PASSWORD') )
                    ->click('#login-btn')
                    ->waitFor('.reading-list-section')
                    ->assertPathIs('/reading-list')
                    ->type('search', 'thor')
                    ->click('.search-button')
                    ->waitFor('.search-list')
                    ->click('.search-list > li .item-actions > a')
                    ->waitFor('.table-list > div.flex-row');
        });
    }
}
