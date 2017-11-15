<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Our Media library</title>

        {{-- Fonts --}}
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        {{-- Styles --}}
        <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/desktop.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/tablet.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/phone.css') }}">

        {{-- Scripts --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="{{ asset('js/jquery.tablesorter.js') }}"></script>

    </head>

    <body token="{{ csrf_token() }}">

        <nav>
            <ul>
                <a
                 href="home"
                 class='with-effect page-switcher active'>
                    <i class='material-icons'>rss_feed</i>
                    <li>News</li>
                </a>

                <a
                 onclick="LOAD_PAGE(this, 'photos')"
                 class='with-effect page-switcher'>
                    <i class='material-icons'>photo_camera</i>
                    <li>Photos</li>
                </a>

                <a
                 onclick="LOAD_PAGE(this, 'videos')"
                 class='with-effect page-switcher'>
                    <i class='material-icons'>videocam</i>
                    <li>Videos</li>
                </a>

                <a
                 onclick="LOAD_PAGE(this, 'contacts')"
                 class='with-effect page-switcher'>
                    <i class='material-icons'>contacts</i>
                    <li>Contacts</li>
                </a>

                {{--<a
                 onclick="LOAD_PAGE(this, 'calendars')"
                 class='with-effect page-switcher'>
                    <i class='material-icons'>event</i>
                    <li>Calendars</li>
                </a>--}}
            </ul>
        </nav>

        <div
         id="app"
         class="container"
         page="">
            @yield('content')
        </div>

        <div
         id="modal-1"
         class="modal-window"
         onclick="">
            <div
             id="close-modal-1"
             class="close-modal-1"
             onclick="DOM_MODS.closeModalWindow(1)">
            </div>

            <div
             id="data-modal-1"
             class="data-modal">
            </div>
        </div>

        <div
         id="modal-2"
         class="modal-window"
         onclick="DOM_MODS.closeModalWindow(2)">
            <div
             id="close-modal-2"
             class="close-modal-2"
             onclick="DOM_MODS.closeModalWindow(this)">
            </div>

            <div
             id="data-modal-2"
             class="data-modal">
            </div>
        </div>

        <div
         id="modal-3"
         class="modal-window"
         onclick="DOM_MODS.closeModalWindow(3)">
            <div
             id="close-modal-3"
             class="close-modal-3"
             onclick="DOM_MODS.closeModalWindow(this)">
            </div>

            <div
             id="data-modal-3"
             class="data-modal">
            </div>
        </div>

    </body>

    <script type="text/javascript" src="{{ asset('js/js.js') }}"></script>
    @yield('scripts')

</html>
