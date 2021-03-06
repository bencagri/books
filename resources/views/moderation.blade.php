@extends("layout.page")
@section("title", "Moderation")
@section("content")
    <div class="modal fade" id="mod-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Contribution Moderation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mod-readable-zone">
                        <div id="amplitude-player">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 columns" id="amplitude-left">
                                    <div id="player-left-bottom">
                                        <div id="time-container">
                                        <span class="current-time">
                                            <span class="amplitude-current-minutes" amplitude-main-current-minutes="true"></span>:<span class="amplitude-current-seconds" amplitude-main-current-seconds="true"></span>
                                        </span>
                                            <input type="range" class="amplitude-song-slider" amplitude-main-song-slider="true" step=".1"/>
                                            <span class="duration">
                                            <span class="amplitude-duration-minutes" amplitude-main-duration-minutes="true"></span>:<span class="amplitude-duration-seconds" amplitude-main-duration-seconds="true"></span>
                                        </span>
                                        </div>
                                        <div id="control-container">
                                            <div id="repeat-container">
                                                <div class="amplitude-repeat" id="repeat"></div>
                                            </div>
                                            <div id="central-control-container">
                                                <div id="central-controls">
                                                    <div class="amplitude-prev" id="previous"></div>
                                                    <div class="amplitude-play-pause" amplitude-main-play-pause="true" id="play-pause"></div>
                                                    <div class="amplitude-next" id="next"></div>
                                                </div>
                                            </div>
                                            <div id="shuffle-container">
                                                <div class="amplitude-shuffle amplitude-shuffle-off" id="shuffle"></div>
                                            </div>
                                        </div>
                                        <div id="meta-container">
                                            <span amplitude-song-info="name" amplitude-main-song-info="true" class="song-name"></span>
                                            <div class="song-artist-album">
                                                <span amplitude-song-info="artist" amplitude-main-song-info="true"></span>
                                                <span amplitude-song-info="album" amplitude-main-song-info="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="moderation-action" value="{{ url("moderation/action") }}">
                        <div class="m--space-30"></div>
                        <blockquote class="blockquote blockquote-reverse">
                            <p class="mb-0"><i class="fa fa-book"></i> <span id="moderation-book-name"></span></p>
                            <footer class="blockquote-footer">By
                                <cite id="moderation-book-author"></cite>
                            </footer>
                        </blockquote>
                        <div id="moderation-modal-body">
                        </div>
                    </div>
                    <div id="mod-reject" class="d-none">
                        <form id="moderation-reject" method="POST">
                            <input type="hidden" name="_token" class="csrf_field">
                            <div class="bg-secondary p-5">
                                <div class="media">
                                    <img class="align-self-start mr-3" src="{{ asset(Auth::user()->getProfileImage()) }}" width="50" alt="{{ Auth::user()->getAccount() }}">
                                    <div class="media-body">
                                        <h5 class="mt-0">{{ '@'.Auth::user()->getAccount() }} · <span class="m--font-metal">00 hours ago</span></h5>
                                        <p>
                                            Your contribution cannot be approved because it does not follow the
                                            <a href="{{ config("steem.rules") }}">{{ config("app.name") }} Rules</a>.
                                        </p>
                                        <textarea class="form-control m-input" name="comment" cols="30" rows="10" placeholder="YOU CAN ADD YOUR MESSAGE HERE"></textarea>
                                        <p>
                                            <strong>[MODERATOR]</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="m-alert m-alert--icon alert alert-danger" role="alert">
                                <div class="m-alert__icon">
                                    <i class="fa fa-warning"></i>
                                </div>
                                <div class="m-alert__text">
                                    <strong>Attention!</strong> This contribution will be rejected.
                                </div>
                                <div class="m-alert__actions" style="width: 220px;">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm m-btn m-btn--hover-secondary">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="mod-approve" class="d-none">
                        <form id="moderation-approve" method="POST">
                            <input type="hidden" name="_token" class="csrf_field">
                            <div class="bg-secondary p-5">
                                <div class="media">
                                    <img class="align-self-start mr-3" src="{{ asset(Auth::user()->getProfileImage()) }}" width="50" alt="{{ Auth::user()->getAccount() }}">
                                    <div class="media-body">
                                        <h5 class="mt-0">{{ '@'.Auth::user()->getAccount() }} · <span class="m--font-metal">00 hours ago</span></h5>
                                        <p>
                                            Thank you for the contribution. It has been approved.
                                        </p>
                                        <textarea class="form-control m-input" name="comment" cols="30" rows="10" placeholder="YOU CAN ADD YOUR MESSAGE HERE"></textarea>
                                        <p>
                                            <strong>[MODERATOR]</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="m-alert m-alert--icon alert alert-success" role="alert">
                                <div class="m-alert__icon">
                                    <i class="fa fa-warning"></i>
                                </div>
                                <div class="m-alert__text">
                                    <strong>Attention!</strong> This contribution will be approved.
                                </div>
                                <div class="m-alert__actions" style="width: 220px;">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm m-btn m-btn--hover-secondary">
                                        Approve
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="mod-controls">
                        <hr>
                        <div class="text-center">
                            <button type="button" id="mod-reject-button" class="toggle-mod btn btn-outline-danger m-btn m-btn--outline-2x">Reject</button>
                            <button type="button" id="mod-approve-button" class="toggle-mod btn btn-outline-success m-btn m-btn--outline-2x">Approve</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m--bg-secondary m-portlet--bordered-semi">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Search in Contributions
                            </h3>
                        </div>
                    </div>
                </div>
                <form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                    <div class="m-portlet__body">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group row">
                                <label class="col-lg-2 col-form-label" for="user">User:</label>
                                <div class="col-lg-4">
                                    <div class="input-group m-input-group m-input-group--square">
                                        <div class="input-group-prepend"><span class="input-group-text">@</span></div>
                                        <input type="text" class="form-control m-input" name="user" id="user" placeholder="{{ config("steem.account") }}" value="{{ array_get($filters, 'user') }}">
                                    </div>
                                    <span class="m-form__help">Please start to type and find user in the list</span>
                                </div>
                                <label class="col-lg-2 col-form-label" for="language">Language:</label>
                                <div class="col-lg-4">
                                    <select name="language" id="language" class="form-control m-input">
                                        <option value="">- Choose</option>
                                        @foreach(config("app.languages") as $key => $lang)
                                            <option value="{{ $key }}" @if(array_get($filters, 'language') == $key) selected @endif>{{ $lang }}</option>
                                        @endforeach
                                    </select>
                                    <span class="m-form__help">Choose a language</span>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-lg-2 col-form-label">Status:</label>
                                <div class="col-lg-10">
                                    <div class="m-checkbox-inline">
                                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-warning">
                                            <input type="checkbox" name="status[]" @if(in_array('0', array_get($filters, 'status'))) checked @endif value="0" class="checked"> Pending
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                            <input type="checkbox" name="status[]" @if(in_array('1', array_get($filters, 'status'))) checked @endif value="1"> Approved
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-danger">
                                            <input type="checkbox" name="status[]" @if(in_array('2', array_get($filters, 'status'))) checked @endif value="2"> Rejected
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-dark">
                                            <input type="checkbox" name="status[]" @if(in_array('3', array_get($filters, 'status'))) checked @endif value="3"> No Contribution
                                            <span></span>
                                        </label>
                                    </div>
                                    <span class="m-form__help">Please enter your contact</span>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-lg-2 col-form-label">Order by:</label>
                                <div class="col-lg-10">
                                    <div class="m-radio-inline">
                                        <label class="m-radio m-radio--solid">
                                            <input type="radio" name="order_by" @if(array_get($filters, 'order_by') == 'date') checked @endif value="date" class="checked"> Date
                                            <span></span>
                                        </label>
                                        <label class="m-radio m-radio--solid">
                                            <input type="radio" name="order_by" @if(array_get($filters, 'order_by') == 'language') checked @endif value="language"> Language
                                            <span></span>
                                        </label>
                                        <label class="m-radio m-radio--solid">
                                            <input type="radio" name="order_by" @if(array_get($filters, 'order_by') == 'book') checked @endif value="book"> Book
                                            <span></span>
                                        </label>
                                        <label class="m-radio m-radio--solid">
                                            <input type="radio" name="order_by" @if(array_get($filters, 'order_by') == 'user') checked @endif value="user"> User
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions--solid text-center">
                                <button type="submit" class="btn btn-brand">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12">
            @if($count > 0)
                <div class="m-portlet m--bg-secondary m-portlet--bordered-semi">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                                <h3 class="m-portlet__head-text">
                                    Search Results
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <nav aria-label="Search result pagination">
                            {{ $paginate->render('pagination::bootstrap-4') }}
                        </nav>
                        <br>
                        <div class="fc fc-unthemed fc-ltr">
                            <div class="fc-view-container">
                                <div class="m-alert m-alert--outline m-alert--outline-2x alert alert-info alert-dismissible fade show" role="alert">
                                    <strong>Attention!</strong> Your account has been granted as <u>moderator</u>, so you may make changes in this list shown below.
                                    Bear in your mind that approved posts are stored on <u>blockchain</u> and it may block some changes in the future.
                                </div>
                                <div class="fc-view fc-listWeek-view fc-list-view fc-widget-content">
                                    <div class="fc-scroller">
                                        <div class="table-responsive">
                                            <table class="fc-list-table">
                                                <tbody>
                                                @foreach($content as $post)
                                                    <tr class="fc-list-heading">
                                                        <td class="fc-widget-header" colspan="4">
                                                            {{ $post->getBook()->getName() }} <a class="fc-list-heading-alt">{{ $post->getBook()->getAuthor()->getName() }}</a>
                                                        </td>
                                                    </tr>
                                                    <tr class="fc-list-item m-fc-event--{{ $status[$post->getStatus()]['color'] }}">
                                                        <td class="fc-list-item-time fc-widget-content align-middle">
                                                            @if($post->getStatus() == \App\Repositories\BookAudioRepository::STATUS_PENDING)
                                                                <a href="#" data-href="{{ url("moderation/audio/{$post->getId()}") }}" data-toggle="modal" data-target="#mod-modal" class="btn btn-accent m-btn m-btn--uppercase mod-check">
                                                                    <i class="fa fa-search"></i>
                                                                </a>
                                                            @else
                                                                <a class="btn btn-secondary m-btn m-btn--uppercase mod-check">
                                                                    <i class="fa fa-ban"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td class="fc-list-item-marker fc-widget-content align-middle">
                                                        <span data-toggle="m-tooltip" data-placement="top" data-original-title="{{ config("app.languages.{$post->getBook()->getLanguage()}") }}">
                                                            <img src="{{ asset("assets/custom/img/flags/{$post->getBook()->getLanguage()}.png") }}" width="50" alt="{{ config("app.languages.{$post->getBook()->getLanguage()}") }}">
                                                        </span>
                                                        </td>
                                                        <td class="fc-list-item-marker fc-widget-content align-middle">
                                                            <span class="fc-event-dot" data-toggle="m-tooltip" data-placement="top" data-original-title="{{ $status[$post->getStatus()]['text'] }}"></span>
                                                        </td>
                                                        <td class="fc-list-item-title fc-widget-content">Chapter #{{ $post->getChapter() }} | <a class="m-link" href="{{ url("listen/{$post->getId()}") }}">{{ $post->getName() }}</a>
                                                            <div class="fc-description">By <strong><a href="{{ url("user/{$post->getUser()->getAccount()}") }}" class="m-link">{{ '@'.$post->getUser()->getAccount() }}</a></strong>, on {{ format_date($post->getCreatedAt()) }}</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <nav aria-label="Search result pagination">
                            {{ $paginate->render('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            @else
                <div class="m-alert m-alert--outline m-alert--outline-2x alert alert-warning fade show" role="alert">
                    <strong>Warning!</strong> No matching record found.
                </div>
            @endif
        </div>
    </div>
@endsection