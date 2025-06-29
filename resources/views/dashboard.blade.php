@extends('layouts.app')

@section('dashboard', 'ダッシュボード')

@section('content')
    {{-- ここからbody --}}
    <div class="container">
        <div class="surf-box">
            @if(in_array($user['permission'], [0, 1]))
                <a href="{{ route('attendance.list') }}">出勤一覧</a>
                <a href="{{ route('user.list') }}">ログイン管理</a>
            @endif

            @if(in_array($user['permission'], [0]))
                <a href="{{ route('management.management') }}">情報登録</a>
            @endif
        </div>
        <h2 class="mb-4">出勤管理</h2>
        {{-- エラー文 --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- 出勤記録フォーム -->
        <form action="{{route('attendance.confirm')}}" method="post">
            @csrf
            <!-- 日付選択 -->
            <div class="mb-3">
                <label for="date" class="form-label">日付</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <!-- 職人名 -->
            @if(in_array($user['permission'], [0]))
                <div class="mb-3">
                    <label for="name" class="form-label">名前</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="" disabled {{ old('name') == '' ? 'selected' : '' }}>選択してください</option>
                        @foreach ($allUser as $userItem)
                            @if ($userItem->is_allowed)
                                <option value="{{ $userItem->id }}" {{ old('name') == $userItem->id ? 'selected' : '' }}>
                                    {{ $userItem->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name='user_id' value='{{$user->id}}'>
                <label style="font-weight: bold;">{{$user->name}}</label><br>
            @endif
                {{-- 労務・外注 --}}
                @if(in_array($user['permission'], [0]))
                人数：
                <select name="count">
                    @for ($i = 1; $i <= 20; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                人<br>
                <label>
                    <input type="checkbox" name="work_type" value="労務" onclick="toggleCheckbox(this)"> 労務
                </label>
                <label>
                    <input type="checkbox" name="work_type" value="外注" onclick="toggleCheckbox(this)"> 外注
                </label>
                @elseif(in_array($user['permission'], [1,2]))
                <label>
                    <input type="checkbox" name="work_type" value="労務" onclick="toggleCheckbox(this)"> 労務
                </label>
                <label>
                    <input type="checkbox" name="work_type" value="外注" onclick="toggleCheckbox(this)"> 外注
                </label>
                <br>
                <label style="font-size:15px;color:red;font-weight:bold;">”時間”は30分刻みで入力してください</label>
                <br>
                <input type="hidden" name="count" value="1">
                 @else
                人数：
                <select name="count">
                    @for ($i = 1; $i <= 20; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                人
                <br>
                <label>
                    <input type="checkbox" name="work_type" value="労務" onclick="toggleCheckbox(this)"> 労務
                </label>
                <label>
                    <input type="checkbox" name="work_type" value="外注" onclick="toggleCheckbox(this)"> 外注
                </label>
                <br>
                <label style="font-size:15px;color:red;font-weight:bold;">”時間”は30分刻みで入力してください</label>
                 @endif
            <!-- 今日の現場（複数追加対応） -->
            <div id="site-container">
                <div class="site-group">
                    <label>現場名</label>
                    <select class="form-control site-select" name="site[]" required onchange="updateAvailableSites()">
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($works as $work)
                            @if ($work->status === 'active')
                                <option value="{{ $work }}">{{ $work->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <br>
                    <label>作業内容</label>
                        <select class="form-control work-content" name="work_content[]" required onchange="toggleOtherInput(this)">
                            <option value="" disabled selected>選択してください</option>
                            <option value="外壁塗装" {{ old('work_content') == '外壁塗装' ? 'selected' : '' }}>外壁塗装</option>
                            <option value="内部塗装" {{ old('work_content') == '内部塗装' ? 'selected' : '' }}>内部塗装</option>
                            <option value="洗浄" {{ old('work_content') == '洗浄' ? 'selected' : '' }}>洗浄</option>
                            <option value="養生" {{ old('work_content') == '養生' ? 'selected' : '' }}>養生</option>
                            <option value="手直し" {{ old('work_content') == '手直し' ? 'selected' : '' }}>手直し</option>
                            <option value="その他" {{ old('work_content') == 'その他' ? 'selected' : '' }}>その他</option>
                        </select>
                    <br>

                    <input type="text" class="form-control other-work-content" name="other_work_content[]" style="display: none;" placeholder="作業内容を入力">

                    <label>開始時間</label>
                    <input type="time" class="form-control start-time" name="start_time[]" value="08:00" step="1800" required>
                    <br>
                    <label>終了時間</label>
                    <input type="time" class="form-control end-time" name="end_time[]" value="17:00" step="1800" required>
                    <br>

                    <button type="button" class="btn btn-success add-site">+</button>
                </div>
            </div>
            <!-- 送信ボタン -->
            <button type="submit" class="btn btn-primary">記録</button>
        </form>
    </div>
@if(in_array($user['permission'], [0]))
    <h4>未提出</h2>
    @foreach ($allUser as $userItem)
    @if ($userItem->is_allowed)
        @php
            $alreadyCheckedIn = \App\Models\Attendance::where('name', $userItem->name)
                                ->where('date', \Carbon\Carbon::today()->format('Y-m-d'))
                                ->exists();
        @endphp

        @if (!$alreadyCheckedIn)
            {{ $userItem->name }}<br>
        @endif
    @endif
    @endforeach
@endif
<br>
<br>
    <a href="#" id="logout-link">ログアウト</a>


<script>
document.getElementById("logout-link").addEventListener("click", function (e) {
    e.preventDefault();

    let form = document.createElement("form");
    form.method = "POST";
    form.action = "{{ route('logout') }}";

    let csrf = document.createElement("input");
    csrf.type = "hidden";
    csrf.name = "_token";
    csrf.value = "{{ csrf_token() }}";

    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
});

function toggleCheckbox(clickedCheckbox) {
    let checkboxes = document.querySelectorAll('input[name="work_type"]');
    checkboxes.forEach(checkbox => {
        if (checkbox !== clickedCheckbox) {
            checkbox.checked = false;  // 他のチェックを外す
        }
    });
}

document.querySelector("form").addEventListener("submit", function(event) {
    let workContent = document.getElementById("work_content");
    let otherWorkContent = document.getElementById("other_work_content");

    if (workContent.value === "その他" && otherWorkContent.value.trim() !== "") {
        workContent.value = otherWorkContent.value;  // 入力した値を work_content にセット
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const maxSites = 5; // 最大5つまで
    const siteContainer = document.getElementById("site-container");

    // + ボタンの処理
    document.querySelector(".add-site").addEventListener("click", function () {
    const lastEndTime = getLastEndTime(); // 最後の終了時間を取得

    if (siteContainer.children.length < maxSites) {
        let newSite = siteContainer.children[0].cloneNode(true);

        // ======= 開始時間（編集不可＆hiddenで送信） =======
        let startInput = newSite.querySelector(".start-time");

        // 値を更新
        startInput.value = lastEndTime;

        // span 表示用作成
        const span = document.createElement("span");
        span.className = "start-time-display";
        span.textContent = lastEndTime;

        // hidden input に変える
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "start_time[]";
        hiddenInput.value = lastEndTime;

        // DOM に追加
        startInput.parentNode.insertBefore(span, startInput);       // 表示だけ
        startInput.parentNode.insertBefore(hiddenInput, startInput); // 送信用 hidden
        startInput.remove(); // 元の input を削除
        // =============================================

        // 終了時間はリセット
        newSite.querySelector(".end-time").value = "17:00";

        // プラスボタン削除
        let addButton = newSite.querySelector(".add-site");
        if (addButton) {
            addButton.remove();
        }

        // マイナスボタン追加
        let minusButton = document.createElement("button");
        minusButton.type = "button";
        minusButton.className = "btn btn-danger remove-site";
        minusButton.textContent = "−";
        minusButton.addEventListener("click", function () {
            this.parentNode.remove();
            updateAvailableSites();
        });

        newSite.appendChild(minusButton);
        siteContainer.appendChild(newSite);

        // 次の開始時間の更新（※任意）
        updateStartTimes();

        updateAvailableSites();
    }
});


    // 最後の終了時間を取得する関数
    function getLastEndTime() {
        let lastEndTime = "08:00"; // デフォルトの開始時間

        // 最後の終了時間を取得
        let endTimes = siteContainer.querySelectorAll(".end-time");
        if (endTimes.length > 0) {
            let lastTime = endTimes[endTimes.length - 1].value;
            lastEndTime = lastTime;
        }

        return lastEndTime;
    }

    // 終了時間が変更された場合に次の開始時間を自動的に更新
    siteContainer.addEventListener("change", function(event) {
        if (event.target.classList.contains("end-time")) {
            updateStartTimes();
        }
    });

    // すべての終了時間を基に開始時間を更新
    function updateStartTimes() {
        const sites = siteContainer.querySelectorAll(".site-entry");
        let previousEndTime = "08:00"; // 最初の開始時間

        sites.forEach((site, index) => {
            const startTimeInput = site.querySelector(".start-time");
            const endTimeInput = site.querySelector(".end-time");

            if (startTimeInput && endTimeInput) {
                // 終了時間が変更されていれば、次の開始時間を設定
                if (startTimeInput.value === "" || startTimeInput.value === previousEndTime) {
                    startTimeInput.value = previousEndTime;
                }

                // 現在の終了時間を次の開始時間として設定
                previousEndTime = endTimeInput.value;
            }
        });
    }

    // 「その他」を選んだときのテキストボックス表示
    window.toggleOtherInput = function(selectElement) {
        let otherInput = selectElement.parentNode.querySelector(".other-work-content");
        if (selectElement.value === "その他") {
            otherInput.style.display = "block";
            otherInput.style.width = "100px";
            otherInput.style.margin = "0 auto";
        } else {
            otherInput.style.display = "none";
        }
    };

    // 現場名の変更を監視して利用できる現場を更新
    document.addEventListener("change", function(event) {
        if (event.target.matches("select[name='site[]']")) {
            updateAvailableSites();
        }
    });
});


// document.addEventListener("DOMContentLoaded", function () {
//     const maxSites = 5; // 最大5つまで
//     const siteContainer = document.getElementById("site-container");

//     // + ボタンの処理
//     document.querySelector(".add-site").addEventListener("click", function () {
//         if (siteContainer.children.length < maxSites) {
//             let newSite = siteContainer.children[0].cloneNode(true);

//             newSite.querySelector("select[name='site[]']").value = "";
//             newSite.querySelector("select[name='work_content[]']").value = "";
//             newSite.querySelector(".other-work-content").value = "";
//             newSite.querySelector(".other-work-content").style.display = "none";
//             newSite.querySelector(".end-time").value = "17:00"; // 終了時間をリセット

//             // クローンにはプラスボタンを付けない
//             let addButton = newSite.querySelector(".add-site");
//             if (addButton) {
//                 addButton.remove();
//             }

//             // マイナスボタンを追加
//             let minusButton = document.createElement("button");
//             minusButton.type = "button";
//             minusButton.className = "btn btn-danger remove-site";
//             minusButton.textContent = "−";
//             minusButton.addEventListener("click", function () {
//                 this.parentNode.remove();
//                 updateAvailableSites();
//             });

//             newSite.appendChild(minusButton);
//             siteContainer.appendChild(newSite);

//             updateAvailableSites();
//         }
//     });

//     // 「その他」を選んだときのテキストボックス表示
//     window.toggleOtherInput = function(selectElement) {
//         let otherInput = selectElement.parentNode.querySelector(".other-work-content");
//         if (selectElement.value === "その他") {
//             otherInput.style.display = "block";
//             otherInput.style.width = "100px";
//             otherInput.style.margin = "0 auto";
//         } else {
//             otherInput.style.display = "none";
//         }
//     };



//     document.addEventListener("change", function(event) {
//         if (event.target.matches("select[name='site[]']")) {
//             updateAvailableSites();
//         }
//     });
// });




</script>
    @endsection

