<div class="media photos">

    <ul class="photos-list">
    @for ($i = 0; $i <= 100; $i++)
        @foreach ($photos as $key => $photo)

        <li hidden>
            <div
             style="background-image: url('{{ $photo->thmb }}');"
             class="with-effect"
             url="{{ $photo->path }}"
             meta-name="{{ $photo->name }}"
             meta-size="{{ round($photo->size / 1024, 2) }}"
             meta-date="{{ date('d M, Y', $photo->date) }}"
             onclick="DOM_MODS.showFullMedia(this, ANIM_NONE), DOM_MODS.setCurrentMedia(this)"
             onmouseenter="DOM_MODS.showMediaMeta(this)"
             onmouseleave="DOM_MODS.hideMediaMeta(this)">
            </div>
        </li>

        @endforeach
    @endfor

    </ul>

</div>

{{-- 1. Build grid for photos according to user device. --}}
{{-- 2. Show / hide media while scrolling top / bottom. --}}
<script>

    $(document).ready(function() {

        /* Define grid - number of columns per each line. */
        var grid = IS_DESKTOP ? 7 : IS_TABLET ? 5 : IS_PHONE ? 3 : 0;

        /* Rebuild photos grid according to user's screen. */
        DOM_FIXES.buildMediaGrid('ul.photos-list', grid);

        /* Enable automatic pagination for photos. */
        DOM_MODS.turnOnMediaPagination();

    });

</script>