
    <div class="user-offline-alert d-flex mt-40">
        

        <div class="offline-icon offline-icon-right ml-auto d-flex align-items-stretch">
            <div class="stars-card d-flex align-items-center mt-5">
                @foreach($course->reviews as $review)

                <div class="comments-card shadow-lg rounded-sm border px-20 py-15 mt-30" data-address="/reviews/store-reply-comment" data-csrf="{{ csrf_token() }}" data-id="{{ $review->id }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="user-inline-avatar d-flex align-items-center mt-10">
                            <div class="avatar bg-gray200">
                                <img src="{{ $review->creator->getAvatar() }}" class="img-cover" alt="">
                            </div>
                            <div class="d-flex flex-column ml-5">
                                <span class="font-weight-500 text-secondary">{{ $review->creator->full_name }}</span>

                                @include(getTemplate() . '.includes.webinar.rate',[
                                        'rate' => $review->rates,
                                        'dontShowRate' => true,
                                        'className' => 'justify-content-start mt-0'
                                    ])
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="font-12 text-gray mr-10">{{ dateTimeFormat($review->created_at, 'j M Y | H:i') }}</span>

                            <div class="btn-group dropdown table-actions">
                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-vertical" height="20"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="/reviews/store-reply-comment" class="webinar-actions d-block text-hover-primary reply-comment">{{ trans('panel.reply') }}</a>

                                    @if(!empty($user) and $user->id == $review->creator_id)
                                        <a href="/reviews/{{ $review->id }}/delete" class="webinar-actions d-block mt-10 text-hover-primary">{{ trans('public.delete') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-20 text-gray font-14">
                        {!! clean($review->description,'description') !!}
                    </div>

                    @if($review->comments->count() > 0)
                        @foreach($review->comments as $comment)
                            <div class="shadow-lg rounded-sm border px-20 py-15 mt-30">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="user-inline-avatar d-flex align-items-center mt-10">
                                        <div class="avatar bg-gray200">
                                            <img src="{{ $comment->user->getAvatar() }}" class="img-cover" alt="{{ $comment->user->full_name }}">
                                        </div>
                                        <div class="d-flex flex-column ml-5">
                                            <span class="font-weight-500 text-secondary">{{ $comment->user->full_name }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <span class="font-12 text-gray mr-10">{{ dateTimeFormat($comment->created_at, 'j M Y | H:i') }}</span>

                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="" class="webinar-actions d-block text-hover-primary reply-comment">{{ trans('panel.reply') }}</a>
                                                <a href="/comments/{{ $comment->id }}/delete" class="webinar-actions d-block mt-10 text-hover-primary">{{ trans('public.delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-20 text-gray">
                                    {!! clean($comment->comment,'comment') !!}
                                </div>
                            </div>
                        @endforeach
                    @endif
            </div>
        </div>
    </div>


