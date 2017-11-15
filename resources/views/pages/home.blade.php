@extends('layouts.master')

@section('content')
    <div id="news">

        <h1>Our news:</h1>

        @foreach ($newsPosts as $date => $posts)

            <div class="news-post with-static-shadow-8">

                <h2>{{ Carbon\Carbon::parse($date)->format('D, d M Y') }}</h2>

                @foreach ($posts as $time => $info)

                    <div class="post">

                        <h4 class="news-post-time">{{ $time }}</h4>
                        <p class="news-post-info">{{ $info }}</p>

                    </div>

                @endforeach

            </div>

        @endforeach

    </div>

@endsection