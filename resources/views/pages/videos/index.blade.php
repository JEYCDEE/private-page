<div class="media videos">

    <ul class="videos-list">
    @foreach ($videos as $key => $video)

        <li hidden>
            <div
             style="background-image: url('{{ $video->thmb }}');"
             class="with-effect"
             url="{{ $video->path }}"
             meta-name="{{ $video->name }}"
             meta-size="{{ round($video->size / 1024, 2) }}"
             meta-date="{{ date('d M, Y', $video->date) }}"
             onclick="DOM_MODS.showFullMedia(this, ANIM_NONE), DOM_MODS.setCurrentMedia(this)"
             onmouseenter="DOM_MODS.showMediaMeta(this)"
             onmouseleave="DOM_MODS.hideMediaMeta(this)">
            </div>
        </li>

    @endforeach
    </ul>

</div>

{{-- 1. Build grid for videos according to user device. --}}
{{-- 2. Show / hide media while scrolling top / bottom. --}}
<script>

    $(document).ready(function() {

        /* Define grid - number of columns per each line. */
        var grid = IS_DESKTOP ? 7 : IS_TABLET ? 5 : IS_PHONE ? 3 : 0;

        /* Rebuild videos grid according to user's screen. */
        DOM_FIXES.buildMediaGrid('ul.videos-list', grid);

        /* Enable automatic pagination for videos. */
        DOM_MODS.turnOnMediaPagination();

    });

</script>