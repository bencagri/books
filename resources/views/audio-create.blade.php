@extends("layout.page")
@section("title", "Create a new post")
@section("script")
    function formatRepo(book)
    {
        if (book.loading)
            return book.text;

        var markup  = "<div class='select2-result-repository clearfix'>" +
                      "<div class='select2-result-repository__meta'>" +
                      "<div class='select2-result-repository__title'><strong>" + book.name + "</strong></div>";
            markup += "<div class='select2-result-repository__statistics'>" +
                      "<div class='select2-result-repository__forks'>" + book.author + "</div>" +
                      "<div class='select2-result-repository__stargazers'>" + book.language + "</div>" +
                      "</div>" +
                      "</div></div>";
        return markup;
    }

    function formatRepoSelection(book) {
        if(book.name && book.author)
            return book.name + " - " + book.author;
        else
            return "Search for books";
    }

    $("#book").select2({
        placeholder: "Search for books",
        allowClear: true,
        ajax: {
            url: "{{ url("action/book") }}",
            dataType: 'json',
            delay: 500,
            data: function(params) {
                return {
                    name: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

    $("#tags").select2({
        placeholder: "Select an option",
        maximumSelectionLength: 2,
        allowClear: true,
        tags: true,
        ajax: {
            url: "{{ url("action/audio-tags") }}",
            dataType: 'json',
            delay: 500,
            data: function(params) {
                return {
                    name: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $("#audio-create-form").submit(function(){
        mApp.blockPage({
            overlayColor: '#000000',
            type: 'loader',
            state: 'success',
            message: 'Please wait...'
        });
    });
@endsection
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">Post</h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <form class="m-form m-form--label-align-right" action="{{ url("audio") }}" method="post" enctype="multipart/form-data" id="audio-create-form">
                    {{ csrf_field() }}
                    <div class="m-portlet__body">
                        @include("layout.partials.copyright-alert")
                        <div class="row">
                            <div class="col-xl-10 offset-xl-1">
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">* Book:</label>
                                    <div class="col-xl-9 col-lg-9">
                                        <select class="form-control m-select2" id="book" name="book">
                                            @if(!is_null($book))
                                                <option value="{{ $book['id'] }}" selected="selected">{{ $book['text'] }}</option>
                                            @else
                                                <option></option>
                                            @endif
                                        </select>
                                        <span class="m-form__help">Please choose a book</span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">* Post Title:</label>
                                    <div class="col-xl-9 col-lg-9">
                                        <input type="text" name="title" class="form-control m-input" placeholder="Title for your contribution" value="{{ old("title") }}">
                                        <span class="m-form__help">The title will be displayed as Steemit post title.</span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">* Post Body:</label>
                                    <div class="col-xl-9 col-lg-9">
                                        <textarea name="content" class="form-control" data-provide="markdown" rows="15">{{ old("content") }}</textarea>
                                        <span class="m-form__help">Steemit post body and a text space for proof-of-work</span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">* Chapter:</label>
                                    <div class="col-xl-9 col-lg-9">
                                        <input type="text" name="chapter" class="form-control m-input" placeholder="Chapter" value="{{ !empty(old("chapter")) ? old("chapter") : 1 }}">
                                        <span class="m-form__help">Chapter</span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label" for="audio">* Audio:</label>
                                    <div class="col-xl-9 col-lg-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="audio" id="audio">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                        <span class="m-form__help">Browse your local device and choose a file to upload</span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Tags:</label>
                                    <div class="col-xl-9 col-lg-9">
                                        <select class="form-control m-select2" id="tags" name="tags[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag }}" selected="selected">{{ $tag }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid text-center">
                            <button type="submit" class="btn btn-brand">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection