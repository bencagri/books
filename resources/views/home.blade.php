@extends("layout.metronic")
@section("title", "Home")
@section("content")
    <div class="masthead">
        <div class="intro-body text-center m--font-light">
            <h1 class="presentation-title mt-5">{{ config("app.name") }}</h1>
            <h3>Watch out! Here comes the slogan...</h3>
            <br>
            <p class="lead m-5">
                We let you revoice public e-books to broadcast with your voice. Join the
                <span class="m--font-accent">community</span>, do a <span class="m--font-accent">favour</span>,
                get <span class="m--font-accent">paid!</span>
            </p>
            <hr class="m-5">
            <a class="btn m-btn--pill m-btn--air btn-info btn-lg m-btn m-btn--custom" href="{{ url("/road-map") }}">Road Map</a>
            &nbsp;
            <a class="btn m-btn--pill m-btn--air btn-danger btn-lg m-btn m-btn--custom" href="{{ url("/login") }}">Join now!</a>
            <p class="m-5">Not a Steemian yet? Join <a href="{{ url("https://signup.steemit.com/") }}" class="m-link m--font-boldest m-link--success">Steemit!</a></p>
        </div>
    </div>
@endsection