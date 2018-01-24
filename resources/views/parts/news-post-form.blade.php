<div id="news-post-form">

    <h2>@lang('common.tellSomethingImportant'):</h2>

    <p
     id="news-post-form_error"
     class="text-error"
    ></p>

    <div class="textarea-area with-shadow-2 with-effect">
        <textarea
         type="text"
         id="news-post-form_description"
         name="news-post-form_description"
        ></textarea>
    </div>

    <div class="float-left with-shadow-2 with-effect">
        <input
         type="submit"
         id="news-post-form_submit"
         name="login-box_submit"
         value="@lang('common.postIt')"
        />
    </div>

    <div class="float-right with-shadow-2 with-effect">
        <input
         type="submit"
         id="news-post-form_cancel"
         name="login-box_cancel"
         value="@lang('common.cancel')"
        />
    </div>

</div>