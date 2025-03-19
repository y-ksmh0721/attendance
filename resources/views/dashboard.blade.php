@extends('layouts.app')

@section('dashboard', 'ダッシュボード')

@section('content')
    {{-- ここからbody --}}
    <div class="container">


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
            <div class="mb-3">
                <label for="name" class="form-label">名前</label>
                <select class="form-control" id="name" name="name" required>
                    <option value="" disabled {{ old('name') == '' ? 'selected' : '' }}>選択してください</option>
                    @foreach ($crafts as $craft)
                    @if ($craft->status === 'active')
                    <option value="{{ $craft->name }}" {{old('name') ==$craft->name ? 'selected' : '' }}>
                        {{ $craft->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                {{-- 労務・外注 --}}
                <label>
                    <input type="checkbox" name="work_type" value="労務" onclick="toggleCheckbox(this)"> 労務
                </label>
                <label>
                    <input type="checkbox" name="work_type" value="外注" onclick="toggleCheckbox(this)"> 外注
                </label>
                <br>
            <!-- 今日の現場（複数追加対応） -->
            <div id="site-container">
                <div class="site-group">
                    <label>今日の現場</label>
                    <select class="form-control site-select" name="site[]" required onchange="updateAvailableSites()">
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($works as $work)
                            @if ($work->status === 'active')
                                <option value="{{ $work->name }}">{{ $work->name }}</option>
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


                    <label>終了時間</label>
                    <input type="time" class="form-control end-time" name="end_time[]" value="17:00" required>
                    <br>

                    <button type="button" class="btn btn-success add-site">+</button>
                </div>
            </div>
            <!-- 送信ボタン -->
            <button type="submit" class="btn btn-primary">記録する</button>
        </form>
    </div>
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
        if (siteContainer.children.length < maxSites) {
            let newSite = siteContainer.children[0].cloneNode(true);

            newSite.querySelector("select[name='site[]']").value = "";
            newSite.querySelector("select[name='work_content[]']").value = "";
            newSite.querySelector(".other-work-content").value = "";
            newSite.querySelector(".other-work-content").style.display = "none";
            newSite.querySelector(".end-time").value = "17:00"; // 終了時間をリセット

            // クローンにはプラスボタンを付けない
            let addButton = newSite.querySelector(".add-site");
            if (addButton) {
                addButton.remove();
            }

            // マイナスボタンを追加
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

            updateAvailableSites();
        }
    });

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

    // 現在選択されている現場をリストから除外する
    window.updateAvailableSites = function() {
        let selectedSites = new Set();
        let siteSelects = document.querySelectorAll("select[name='site[]']");

        siteSelects.forEach(select => {
            if (select.value) {
                selectedSites.add(select.value);
            }
        });

        siteSelects.forEach(select => {
            let options = select.querySelectorAll("option");
            options.forEach(option => {
                if (option.value && option.value !== "休み") {
                    option.hidden = selectedSites.has(option.value) && option.value !== select.value;
                }
            });
        });
    };

    document.addEventListener("change", function(event) {
        if (event.target.matches("select[name='site[]']")) {
            updateAvailableSites();
        }
    });
});




</script>
    @endsection

