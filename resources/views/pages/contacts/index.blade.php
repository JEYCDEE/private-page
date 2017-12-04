<table id="contacts">

    <thead>
        <tr>
            <th class="with-effect with-shadow-2">@lang('common.firstName')</th>
            <th class="with-effect with-shadow-2">@lang('common.lastName')</th>
            <th class="with-effect with-shadow-2">@lang('common.email')</th>
            <th class="with-effect with-shadow-2">@lang('common.phone')</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($contacts as $key => $person)
        <tr>
            <td>
                <a
                 href=""
                 class="with-effect">
                    {{ $person->{"First Name"} }}
                </a>
            </td>
            <td>
                <a
                 href=""
                 class="with-effect">
                    {{ $person->{"Last Name"} }}
                </a>
            </td>
            <td>
                <a
                 href="mailto:{{ $person->{"Email"} }}"
                 class="with-effect">
                    {{ $person->{"Email"} }}
                </a>
            </td>
            <td>
                <a
                 href="tel:{{ $person->{"Phone"} }}"
                 class="with-effect">
                    {{ $person->{"Phone"} }}
                </a>
            </td>
        </tr>
        @endforeach

    </tbody>

</table>

<script>
    $('#contacts').tablesorter();
</script>
