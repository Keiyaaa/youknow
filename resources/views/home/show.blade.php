@extends('layouts.app')
@section('content')
    <div class="member_show">
        <div class="topBack">
            <div class="top">
                <div class="member_pic_container">
                    @if ($member->image && file_exists(storage_path('app/public/' . $member->image)))
                        <img class="member_pic" src="/storage/{{ $member->image }}" alt="member_image">
                    @else
                        <img class="member_pic" src="{{ asset('images/dummy_icon.png') }}" alt="member_image">
                    @endif
                </div>
                <h1>{{ $member->name }}</h1>
                <p class="kana">{{ $member->kana }}</p>
                <p class="party">{{ $member->affiliation }}</p>
                <div class="tagsContainer">
                    @foreach ($name as $key => $tag)
                        <div class="tag" style="background-color: {{ $color[$key] }};">
                            <label>#{{ $tag }}</label></div>
                    @endforeach
                </div>
                <hr>
                <div class="iconsContainer">
                    <div class="snsContainer">
                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('images/icon_facebook.png') }}">
                        </a>
                        <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('images/icon_X.png') }}">
                        </a>
                    </div>
                    <div class="likeContainer">
                        <p>いいね</p>
                        <img src="{{ asset('images/icon_like.png') }}">
                    </div>
                </div>
            </div>
        </div>

        <h2>{{ $member->name }}議員の発言の割合</h2>
        <div class="percent">
            <div class="canvasContainer">
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
            <div class="legendContainer">
                <table>
                    @for ($i = 0; $i < count($graph_alphabet); $i++)
                        <tr>
                            <td>
                                <div class="color" style="background-color: {{ $graph_color[$i] }};">
                                    {{ $graph_alphabet[$i] }}</div>
                            </td>
                            <td class="name">{{ $graph_name[$i] }}</td>
                            <td class="percent_display">{{ $graph_persent[$i] }}%</td>
                        </tr>
                    @endfor
                </table>
            </div>
        </div>

        <h2>{{ $member->name }}議員の議会での発言</h2>
        <div class="speech">
            @foreach ($member->questions as $questionIndex => $question)
                <details class="questionContainer">
                    <summary>
                        <div>
                            <h3>{{ $question->title }}</h3>
                            <div class="tagsContainer">
                                @foreach ($question->tags as $tag)
                                    @if($tag->category)
                                        <div class="tag" style="background-color: {{$tag->category->searchTag->color}}">
                                            <label>#{{ $tag->tag }}</label>
                                        </div>
                                    @else
                                        <div class="tag" style="background-color: #818181">
                                            <label>#{{ $tag->tag }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="tri">
                            <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24"
                                width="24">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </div>
                    </summary>
                    <hr>
                    <p class="meetingName">{{ $question->mtg }}</p>
                    <div class="questionContent">
                        <div class="kindTitle">質問</div>
                        <div class="question">{{ preg_replace('/( |　)/', '', $question->content) }}</div>
                    </div>
                    <div class="questionAnswer">
                        <div class="kindTitle">回答</div>
                        <div class="answer">{{ preg_replace('/( |　)/', '', $question->answer) }}</div>
                    </div>
                </details>
            @endforeach
        </div>
    </div>

    <script>
        /**
         * detailsタグの開閉に応じて三角形を回転させる
         * @return {void}
         */
        const triangleUpdate = () => {
            const detailsObjs = document.querySelectorAll("details");
            for (const detailsObj of detailsObjs) {
                const triObj = detailsObj.querySelector(".tri");
                const open = detailsObj.open;
                if (open) {
                    triObj.style.transform = "rotate(180deg)";
                } else {
                    triObj.style.transform = "rotate(0deg)";
                }
            }
        };

        /**
         * myChartに円グラフを描画する
         * @return {void}
         */
        (() => {
            var ctx = document.getElementById("myChart");
            var label = <?php echo json_encode($graph_alphabet); ?>;
            var persent = <?php echo json_encode($graph_persent); ?>;
            var color = <?php echo json_encode($graph_color); ?>;
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: label,
                    percent: persent,
                    datasets: [{
                        backgroundColor: color,
                        data: persent
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    plugins: {
                        datalabels: {
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            color: 'white',
                            font: {
                                size: 13,
                            },
                            formatter: (value, context) => {
                                return context.chart.data.labels[context.dataIndex];
                            }
                        },
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        })();

        /**
         * detailsタグの開閉に応じて三角形を回転させるのをイベントリスナーに登録
         * @return {void}
         */
        (() => {
            const detailsObjs = document.querySelectorAll("details");
            for (const detailsObj of detailsObjs) {
                detailsObj.addEventListener("toggle", triangleUpdate);
            }

            // 初期化で実行
            triangleUpdate();
        })();
    </script>
@endsection
