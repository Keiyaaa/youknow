@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('search_members') }}" class="tagSelectForm">
    @csrf
    <div class="tagSelect">
        <p>あなたの関心のあるタグを選んでください。<br>(最大10個まで選択できます)</p>
        <section>
            @foreach ($searchTags as $searchIndex => $searchTag)
                <div class="aCard">
                    <div class="titleContainer">
                        <div class="iconContainer" style="background-color: {{ $colors[$searchTag["name"]] }};">
                            {{-- iconはここに挿入 --}}
                            @if($searchTag->icon)
                                <img src="/storage/{{ $searchTag->icon }}" alt="">
                            @endif
                        </div>
                        <div class="titleAndCheckbox">
                            <label for="searchTag_{{$searchIndex}}" class="inline-block">{{ $searchTag->name }}</label>
                            <div class="checkboxContainer">
                                <label for="searchTag_{{$searchIndex}}">全てチェック</label>
                                <input type="checkbox" name="searchTag_{{$searchTag->id}}" id="searchTag_{{$searchIndex}}" value="{{ $searchTag->id }}" style="accent-color: {{ $colors[$searchTag['name']] }};">
                            </div>
                        </div>
                    </div>
                    <div class="tagsContainer">
                        @foreach ($searchTag->categories as $index=>$category)
                            <!-- カテゴリーに所属するメンバーの自治体が選択されている場合のみ表示する -->
                            @if($category->member->municipality_id != $municipality_id)
                                @continue
                            @endif
                            <div class="aTag">
                                <input type="checkbox" name="category_{{$searchTag->id}}[{{$index}}]" id="category_{{$searchIndex}}[{{$index}}]" value="{{ $category->name }}" style="accent-color: {{ $colors[$searchTag['name']] }};">
                                <label for="category_{{$searchIndex}}[{{$index}}]" class="inline-block">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
        <input class="hiddenContent" type="text" name="municipality_id" value="{{ $municipality_id }}">
    </div>
    <div id="floatingAdjustment"></div>
    <div class="submitContainer">
        <div class="tagAreaAndButton">
            <div id="selectedTagArea"></div>
            <button type="submit">タグにあった議員を検索する</button>
        </div>
    </div>
</form>
<script>
    let selectCount = 0;
    const maxSelectCount = 10;
    const searchTags = @json($searchTags);
    const colors = @json($colors);

    /**
     * フローティングボタンのエリアにコンテンツが被らないように、下の隙間を調整する。
     * @return {void}
     */
    const floatingAdjust = () => {
        const floatingAdjustment = document.querySelector("#floatingAdjustment");
        const submitContainer = document.querySelector(".submitContainer");
        const submitContainerHeight = submitContainer.clientHeight;
        floatingAdjustment.style.height = `${submitContainerHeight}px`;
    };

    /**
     * タグを選択した状態にし、下部のエリアに追加する。
     * @param {string} tagStr タグの文字列
     * @param {string} inputタグのname属性
     * @return {void}
     */
    const entryTagArea = (tagStr, id) => {
        const selectedTagArea = document.querySelector("#selectedTagArea");
        const selectedTag = document.createElement("div");
        selectedTag.classList.add("aTag");
        selectedTag.setAttribute("link", id); // 上部のチェックボックスとの紐付け

        // もしついていなければ上部タグのチェックボックスをつける
        const checkbox = document.querySelector(`input[id="${id}"]`);
        if(checkbox.checked === false){
            checkbox.checked = true;
        }

        // 文字関係
        const p = document.createElement("p");
        p.innerHTML = tagStr;
        p.style.color = "white";

        // 色を追加して背景に適用
        const color = (()=>{
            for(const searchTag of searchTags){
                for(const category of searchTag["categories"]){
                    if (category["name"] === p.innerHTML) {
                        return colors[searchTag["name"]];
                    }
                }
            }
        })();
        selectedTag.style.backgroundColor = color;

        // タグを削除するボタンを追加
        const deleteButton = document.createElement("div");
        deleteButton.innerHTML = "×";
        deleteButton.style = `
            display: inline;
            background-color: white;
            color: ${color};
            border: none;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            text-align: center;
            cursor: pointer;
            line-height: 14px;
        `;
        deleteButton.addEventListener('click', () => {
            removeTagArea(tagStr);
            exceptionChecker();
        });

        selectedTag.appendChild(deleteButton);
        selectedTag.appendChild(p);
        selectedTagArea.appendChild(selectedTag);
        selectCount++;
    };

    /**
     * タグを選択解除した状態にし、下部のエリアから削除する。
     * @param {string} tagStr タグの文字列
     * @return {void}
     */
    const removeTagArea = (tagStr) => {
        const selectedTagArea = document.querySelector("#selectedTagArea");
        // categoryから一致するタグエレメントを検索
        const allTags = selectedTagArea.querySelectorAll(`div.aTag`);
        for(const target of allTags){
            if(target.querySelector("p").innerHTML === tagStr){
                selectedTagArea.removeChild(target);
                // 上部のチェックボックスを外す
                const id = target.getAttribute("link");
                const checkbox = document.querySelector(`input[id="${id}"]`);
                checkbox.checked = false;
                selectCount--;
            }

        }

    };

    /**
     * チェックボックスのルールに基づく是正
     * 例えば、最大数が10個の場合は、11個目を超えるような選択をするチェックボックスを無効化にする。
     * @return {void}
     */
    const exceptionChecker = () => {
        const allCards = document.querySelectorAll(".aCard");
        const remainCount = maxSelectCount - selectCount;
        // カードごとのチェック項目
        for(const aCard of allCards){
            // 全てのタグが選択されている場合はタイトルにもチェックをいれる そうでなければ外す
            const titleAndCheckbox = aCard.querySelector(".titleAndCheckbox");
            const searchTag = titleAndCheckbox.querySelector("input");
            const allTags = aCard.querySelectorAll(".aTag");
            let allChecked = true;
            for(const aTag of allTags){
                const checkbox = aTag.querySelector("input");
                if(checkbox.checked === false){
                    allChecked = false;
                }
            }
            if(allChecked){
                searchTag.checked = true;
            }else{
                searchTag.checked = false;
            }

            // タグの数をチェックし、選択できないチェックボックスを無効化
            if(allTags.length == 0){
                searchTag.disabled = true;
            }else{
                // タグの選択されている数をカウント
                let checkedCount = 0;
                for(const aTag of allTags){
                    const checkbox = aTag.querySelector("input");
                    if(checkbox.checked === true){
                        checkedCount++;
                    }
                }

                // もし全てのタグが選択されていたら、「全てチェック」を有効化
                if(checkedCount == allTags.length){
                    searchTag.disabled = false;
                }else{
                    // 現在のカウント数が選択されている数を超えていたら、全てのタグにおいてチェック欄を選択できないようにする
                    if(remainCount < allTags.length - checkedCount){
                        if(searchTag.checked === false){
                            searchTag.disabled = true;
                        }
                    }else{
                        searchTag.disabled = false;
                    }
                }
            }

            // 全てのタグにおいて最大数を超えていたら、全てチェック欄を選択できないようにする
            if(remainCount <= 0){
                for(const aTag of allTags){
                    const checkbox = aTag.querySelector("input");
                    if(checkbox.checked === false){
                        checkbox.disabled = true;
                    }
                }
            }else{
                for(const aTag of allTags){
                    const checkbox = aTag.querySelector("input");
                    checkbox.disabled = false;
                }
            }

            // タグの選択がなければ次のページに進めなくする
            if(selectCount == 0){
                const submitButton = document.querySelector(".submitContainer button");
                submitButton.disabled = true;
                submitButton.style.opacity = 0.5;
            }else{
                const submitButton = document.querySelector(".submitContainer button");
                submitButton.disabled = false;
                submitButton.style.opacity = 1;
            }
        }
        // 下部のタグをソートする
        sortTags();
        // ページ下部の被りを防ぐ
        floatingAdjust();
    }

    /**
     * 下部に表示されたタグをソートする
     * @return {void}
     */
    const sortTags = () => {
        const selectedTagArea = document.querySelector("#selectedTagArea");
        const allTags = selectedTagArea.querySelectorAll(`div.aTag`);
        // allTagsをlink属性の値でソートする
        const sortedTags = Array.from(allTags).sort((a, b) => {
            const aLink = a.getAttribute("link");
            const bLink = b.getAttribute("link");
            if(aLink < bLink) return -1;
            if(aLink > bLink) return 1;
            return 0;
        });
        // selectedTagAreaをクリアにする
        selectedTagArea.innerHTML = "";
        selectCount = 0;

        // ソートされたタグを追加する
        for(const tag of sortedTags){
            // ラベルのforがtagのlinkと一致しているものを探す
            const tagName = document.querySelector(`[for="${tag.getAttribute('link')}"]`).innerHTML;
            entryTagArea(tagName, tag.getAttribute("link"));
        }
    };

    // ページ読み込み時に実行
    (()=>{
        // チェックボックスのルールに基づく是正
        exceptionChecker();

        // 大内容のチェックボックスをクリックしたときに、項目のカテゴリーを全て登録する
        (()=>{
            const allCards = document.querySelectorAll(".aCard");
            for(const aCard of allCards){
                const titleAndCheckbox = aCard.querySelector(".titleAndCheckbox");
                const searchTag = titleAndCheckbox.querySelector("input");
                searchTag.addEventListener("click", ()=>{
                    const allTags = aCard.querySelectorAll(".aTag");
                    if(searchTag.checked){
                        for(const aTag of allTags){
                            if(aTag.querySelector("input").checked === false){
                                entryTagArea(aTag.querySelector("label").innerHTML, aTag.querySelector("input").id);
                                exceptionChecker();
                            }
                        }
                    }else{
                        for(const aTag of allTags){
                            if(aTag.querySelector("input").checked === true){
                                removeTagArea(aTag.querySelector("label").innerHTML);
                                exceptionChecker();
                            }
                        }
                    }
                });
            }
        })();

        // 各カテゴリー内容が押された時に、それを下部に反映する
        (()=>{
            const allTags = document.querySelectorAll(".aTag");
            for(const aTag of allTags){
                const checkbox = aTag.querySelector("input");
                checkbox.addEventListener("click", ()=>{
                    if(checkbox.checked){
                        entryTagArea(aTag.querySelector("label").innerHTML, checkbox.id);
                        exceptionChecker();
                    }else{
                        removeTagArea(aTag.querySelector("label").innerHTML);
                        exceptionChecker();
                    }
                });
            }
        })();

        // チェックボックスが選択された時、ページを読み込まれた際に現在の内容を取得する
        window.addEventListener("load", ()=>{
            const tagSelect = document.querySelector(".tagSelect");
            const allTags = tagSelect.querySelectorAll(".aTag");
            for(const aTag of allTags){
                const checkbox = aTag.querySelector("input");
                if(checkbox.checked){
                    entryTagArea(aTag.querySelector("label").innerHTML, checkbox.id);
                    exceptionChecker();
                }
            }
        });


    })();
</script>
@endsection
