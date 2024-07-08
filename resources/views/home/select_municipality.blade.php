@extends('layouts.app')

@section('content')
<div>
    <!-- メインコンテンツ -->
    <!-- municipalitiesテーブルから市区町村名を取得し、セレクトボックスに表示 -->
    <div class="flex flex-column justify-center items-center pt-10">
        <div class="top-icon">
            <figure>
                <img src="{{ asset('images/icon.svg') }}" alt="top-icon" style="width: 100%;">
            </figure>
        </div>
        <div class="top-logo">
            <figure>
                <img src="{{ asset('images/logo.svg') }}" alt="top-logo" style="width: 100%;">
            </figure>
        </div>
        <div class="search-area">
            <div class="search-title">議員を探す市区町村を選んでください。</div>
            <div class="mx-4">
                <!-- 興味関心から探すボタンと、議員一覧から探すボタンでルーティングを振り分ける -->
                <form method="GET">
                    <div class="select-wrapper">
                        <select name="municipality_id" class="select-municipality" required>
                            <option value="">選択してください</option>
                            @foreach ($municipalities as $municipality)
                                <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-5">
                        <button type="submit" class="btn-origin btn-origin-primary mb-3" formaction="{{route('search')}}">興味関心から探す</button>
                        <button type="submit" class="btn-origin btn-origin-secondary" formaction="{{route('members')}}">議員一覧から探す</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
