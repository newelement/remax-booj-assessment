@extends('layouts.app')

@section('content')
    <div id="main" class="main">
        <div class="home-hero">
            <div class="hero-content">
                <p class="welcome-intro">
                Welcome to the Marvel Reading List
                </p>

                <p>Search and curate your own Marvel Comics reading list. Register to get started or login if you already have an account.</p>

                <div class="hero-actions">
                    <a class="hero-btn" href="/register">Register</a> or <a href="/login">Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
