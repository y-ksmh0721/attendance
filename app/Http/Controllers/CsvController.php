<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Work;
use App\Http\Requests\ValidateRequest;
use App\Http\Requests\AttendanceRequest;
use Illuminate\Support\Facades\Response;

class CsvController extends Controller
{
    public function index(Request $request){
        $targetDate = request('date', now()->toDateString());
        //リレーションにて結合したcraftとcompanyをattendanceテーブルと一緒に持ってくる
        $attendances = Attendance::with(['craft.company','work.cliant'])
        ->whereDate('date', $targetDate)
        ->get();



        return view('csv.index',compact('attendances'));
    }

    public function download(Request $request) {
        $targetDate = $request->query('date', now()->toDateString());

        $attendances = Attendance::with(['craft.company', 'work.cliant'])
            ->whereDate('date', $targetDate)
            ->get();

        $csvData = [
            ['日付', '客先名', '現場名', '作業者名', '時間', '種別', '人数', '残業時間', '作業内容'] // ヘッダー
        ];

        // 会社ごとにデータをグループ化（自社以外）
        $groupedAttendances = $attendances
            ->reject(fn($att) => $att->craft->company->name == "Y's tec")
            ->groupBy(fn($att) => $att->date . '_' . $att->site . '_' . $att->craft->company->name);

        foreach ($attendances as $attendance) {
            if ($attendance->craft->company->name == "Y's tec") {
                $csvData[] = [
                    $attendance->date,
                    $attendance->work->cliant->cliant_name,
                    $attendance->site,
                    $attendance->name,
                    $attendance->time_type,
                    $attendance->work_type,
                    $attendance->time_type === '半日' ? '0.5' : '1',
                    $attendance->overtime,
                    $attendance->work_content,
                ];
            }
        }

        foreach ($groupedAttendances as $key => $records) {
            $firstRecord = $records->first();
            $companyName = $firstRecord->craft->company->name;
            $csvData[] = [
                $firstRecord->date,
                $firstRecord->work->cliant->cliant_name,
                $firstRecord->site,
                $companyName,
                $firstRecord->time_type,
                $firstRecord->work_type,
                $records->count(),
                $firstRecord->overtime,
                $firstRecord->work_content,
            ];
        }

        $filename = "attendance_{$targetDate}.csv";
        ob_start();
        $handle = fopen('php://output', 'w');

        foreach ($csvData as $row) {
            fputcsv($handle, array_map(fn($item) => mb_convert_encoding($item, 'SJIS-win', 'UTF-8'), $row));
        }

        fclose($handle);
        $csvContent = ob_get_clean();

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=Shift_JIS',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

}

// //絞り込んだデータの取得
//  $attendances = $attendances->get();

// //attendanceテーブルの日付を曜日に変換する
// foreach($attendances as $attendance){
//     $attendance->day_of_week = Carbon::parse($attendance->date)->locale('ja')->isoFormat('ddd');
// }
