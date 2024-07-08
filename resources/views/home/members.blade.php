@extends('layouts.app')

@section('content')
    <div class="member-section">
        @if ($search_flg)
            <div class="select-tag-area">
                <h4>情報を見たい議員を選んでください。</h4>
                <div class="selected-tags">
                    @foreach ($display_categories as $category)
                        @if ($category->searchTag)
                            <div class="select-tag" style="background-color: {{ $category->searchTag->color }}">
                                <div class="close-icon" style="color: {{ $category->searchTag->color }};">×</div>
                                {{ $category->name }}
                            </div>
                        @else
                            <div class="select-tag" style="background-color: #818181">
                                <div class="close-icon" style="color: #818181;">×</div>
                                {{ $category->name }}
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="add-tag-button" href="#" onclick="history.back()">
                    <button class="button-title">＜ タグを追加する</button>
                </div>
            </div>
        @endif
        @foreach ($members as $member)
            @if ($loop->first)
                <div class="card-top-margin"></div>
            @endif
            <a class="member-card-wrap" href="/members/show/{{ $member->id }}">
                <div class="member-card">
                    <div class="card-body">
                        <div class="member-image">
                            @if ($member->image && file_exists(storage_path('app/public/' . $member->image)))
                                <img src="/storage/{{ $member->image }}" alt="member_image">
                            @else
                                <img src="{{ asset('images/dummy_icon.png') }}" alt="member_image">
                            @endif
                        </div>
                        <div class="member-detail">
                            <h3 class="member-name">
                                {{ $member->name }}
                            </h3>
                            <p class="member-ruby">
                                {{ $member->kana }}
                            </p>
                            <div class="member-affiliation">
                                {{ $member->affiliation }}
                            </div>
                        </div>
                    </div>
                    <div class="card-tags">
                        @foreach ($member->categories as $category)
                            @if ($category->searchTag)
                                <div class="tag" style="background-color: {{ $category->searchTag->color }}">
                                    # {{ $category->name }}
                                </div>
                            @else
                                <div class="tag" style="background-color: #818181">
                                    # {{ $category->name }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @if ($search_flg)
                    <div class="match-tag">
                        マッチ<span>{{$member->category_count}}</span>タグ
                    </div>
                @endif
            </a>
        @endforeach
    </div>
@endsection
