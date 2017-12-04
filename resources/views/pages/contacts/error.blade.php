<div class="errors">
    <h1>@lang('common.errorSomethingWentWrong')</h1>

    @foreach ($errors as $counter => $error)
    <p>
        <span class="error-text">{{ ++$counter }} : </span>
        <strong>{{ $error }}</strong>
    </p>
    @endforeach

</div>