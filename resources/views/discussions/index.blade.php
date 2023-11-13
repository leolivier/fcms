@extends('layouts.main')
@section('body-id', 'discussions')

@section('content')
<div class="p-5">
    <div class="d-flex justify-content-between">
        <h2>{{ _gettext('Discussions') }}</h2>
        <div>
            <a href="{{ route('discussions.create') }}" class="btn btn-success text-white">{{ _gettext('New Discussion') }}</a>
        </div>
    </div>

@if ($discussions->isEmpty())
    <x-empty-state/>
@else
    <div class="discussions m-5 ms-0">
    @foreach($discussions as $d)
        <div class="discussion d-flex justify-content-between pb-4 border-bottom">
            <div class="d-flex flex-row p-3">
                <div>
                    <img class="avatar rounded-5 mx-3" src="{{ getUserAvatar($d->toArray()) }}" title="{{ _gettext('avatar') }}">
                </div>
                <div>
                    <a href="{{ route('discussions.show', $d->id) }}" class="title h3 py-1 d-block text-black text-decoration-none">{{ $d->title }}</a>
                    <b class="text-primary me-3">{{ getUserDisplayName($d->toArray()) }}</b><span class="text-muted">{{ $d->updated_at->diffForHumans() }}</span>
                    <div class="details d-flex flex-row mt-4">
                        <div class="d-inline-block border p-2 me-5">
                            <i class="bi-chat-square me-2"></i>
                            {{ sprintf(_ngettext('%s reply', '%s replies', $d->comments_count-1), $d->comments_count-1) }}
                        </div>
                        <div class="d-inline-block border p-2">
                            <i class="bi-eye me-2"></i>
                            {{ sprintf(_ngettext('%s view', '%s views', $d->views), $d->views) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3">
            @if($d->comments_count > 20)
                <i class="bi-bookmark-heart-fill fs-1 text-danger"></i>
            @elseif($d->comments_count > 10)
                <i class="bi-bookmark-heart-fill fs-1 text-warning"></i>
            @endif
            </div>
        </div><!-- /.discussion -->
    @endforeach
    </div><!-- /.discussions -->

    <div class="d-flex justify-content-center">
    {{ $discussions->links() }}
    </div>

@endif
</div>
@endsection
