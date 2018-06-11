<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Paper;
use App\User;

use App\Models\AssistantConfirm;
use App\Models\SelfCorrection;
use App\Models\TeacherCorrection;

class DashboardController extends Controller
{
    //

    // Done，缺错误率
    public function index(Paper $pp, User $u)
    {
        // 0、工具栏：选择日期

        $now = time();
        $index_data = [
            'is_history' => 0,
            'year' => date("Y年", $now),
            'date' => date("n月j日", $now),
            'papers' => [],
            'count' => [
                'paper' => 0,
                'finished' => 0,
                'unfinished' => 0,
                'self_correct' => 0
            ],
            'rates' => [
                'complete_rate' => 0,
                'correction_rate' => 0,
                'error_rate' => 0
            ]
        ];

        // 1、获取现有，当天的Paper——list
        
        $today_papers = $pp->where("pratice_time", date("Y-m-d", $now))->get()->toArray();
        if (count($today_papers) > 0)
        {
            foreach ($today_papers as $key => $paper) {
                // 基本资料
                $std = $u->where('id', $paper['student_id'])->get()->toArray();
    
                ++$index_data['count']['paper'];
    
                $index_data['papers'][$key] = [
                    'order' => $key + 1,
                    'uuid' => $paper['uuid'],
                    'alias' => $paper['alias'],
                    'student' => $std[0]['real_name'],
                    'student_id' => $paper['student_id'],
                    'progress' => 0
                ];
                
                // 完成率
    
                if ($paper['confirm_time'] !== null)
                {
                    ++$index_data['count']['finished'];
                    $index_data['papers'][$key]['progress'] += 0.33;
                } else {
                    ++$index_data['count']['unfinished'];
                }
    
                // 订正率
    
                if ($paper['self_correct_time'] !== null)
                {
                    ++$index_data['count']['self_correct'];
                    $index_data['papers'][$key]['progress'] += 0.33;
                }
    
                // 【未完成】错误率统计
            }
    
            // 2、显示基本数据：完成率、订正率、正误率
            // 【未完成】正确率分析
            $index_data['rates']['complete_rate'] = round($index_data['count']['finished'] / $index_data['count']['paper'], 2);
    
            $index_data['rates']['correction_rate'] = round($index_data['count']['self_correct'] / $index_data['count']['paper'], 2);
        } else {
            session()->flash('danger', "今天没有布置作业");
        }
        
        // 3、提供 Detail 跳转选项 UUID

        return view('dash.index', ['index_data' => $index_data]);
    }

    // Done，缺错误率
    public function history(Paper $pp, User $u, Request $r)
    {
        // 指定历史事件的 index()
        $d = $r->history_date;
        $ts = mktime(0, 0, 0, substr($d, 2, 2), substr($d, 4, 2), '20'.substr($d, 0, 2));
        $pratice_time = date("Y-m-d", $ts);

        $index_data = [
            'is_history' => 1,
            'year' => date("Y年", $ts),
            'date' => date("n月j日", $ts),
            'papers' => [],
            'count' => [
                'paper' => 0,
                'finished' => 0,
                'unfinished' => 0,
                'self_correct' => 0
            ],
            'rates' => [
                'complete_rate' => 0,
                'correction_rate' => 0,
                'error_rate' => 0
            ]
        ];

        // 1、获取现有，当天的Paper——list
        
        $thisday_papers = $pp->where("pratice_time", $pratice_time)->get()->toArray();

        if (count($thisday_papers) > 0)
        {
            foreach ($thisday_papers as $key => $paper) {
                // 基本资料
                $std = $u->where('id', $paper['student_id'])->get()->toArray();
    
                ++$index_data['count']['paper'];
    
                $index_data['papers'][$key] = [
                    'order' => $key + 1,
                    'uuid' => $paper['uuid'],
                    'alias' => $paper['alias'],
                    'student' => $std[0]['real_name'],
                    'student_id' => $paper['student_id'],
                    'progress' => 0
                ];
                
                // 完成率
    
                if ($paper['confirm_time'] !== null)
                {
                    ++$index_data['count']['finished'];
                    $index_data['papers'][$key]['progress'] += 0.33;
                } else {
                    ++$index_data['count']['unfinished'];
                }
    
                // 订正率
    
                if ($paper['self_correct_time'] !== null)
                {
                    ++$index_data['count']['self_correct'];
                    $index_data['papers'][$key]['progress'] += 0.33;
                }
    
                // 【未完成】错误率统计
            }
    
            // 2、显示基本数据：完成率、订正率、正误率
            // 【未完成】正确率分析
            $index_data['rates']['complete_rate'] = round($index_data['count']['finished'] / $index_data['count']['paper'], 2);
    
            $index_data['rates']['correction_rate'] = round($index_data['count']['self_correct'] / $index_data['count']['paper'], 2);
            
            // 3、提供 Detail 跳转选项 UUID
            //dd($index_data);
            
        } else {
            session()->flash('danger', "今天没有布置作业");
        }

        return view('dash.index', ['index_data' => $index_data]);
    }

    // Done
    public function preview($paper_uuid = null, Paper $pp)
    {
        // 1、获取题面
        // 2、获取二维码
        // (opt)3、获取答案参考样式

        $modelPaper = $pp->where("uuid", $paper_uuid)->get();

        $arrayPaper = $modelPaper->toArray();
        $arrayPaper[0]['quiz_ids'] = json_decode($arrayPaper[0]['quiz_ids'], true);
        $arrayPaper[0]['quizes'] = json_decode($arrayPaper[0]['quizes'], true);

        //dd($arrayPaper);
        $preview_data = [
            'count' => count($arrayPaper[0]['quizes']),
            'uuid' => $arrayPaper[0]['uuid'],
            'alias' => $arrayPaper[0]['alias'],
            'quizes' => [],
            'keys' => []
        ];

        if ($modelPaper->count() === 1)
        {
            foreach ($arrayPaper[0]['quizes'] as $key => $quiz) {
                $quiz_id = $arrayPaper[0]['quiz_ids'][$key];
                $q = [
                    'id' => $quiz_id,
                    'quiz' => $quiz['show']
                ];
                $k = [
                    'id' => $quiz_id,
                    'key' => $quiz['correction']['meaning'],
                    'exkey' => $quiz['optional']['omeaning']
                ];

                $preview_data['quizes'][] = $q;
                $preview_data['keys'][] = $k;
            }
        } else {
            session()->flash("danger", "试卷UUID错误");
        }

        //dd($preview_data);

        return view('dash.preview', ['preview_data' => $preview_data]);
    }

    public function detail($paper_uuid = null, Paper $pp, User $u)
    {
        // 1、功能区：批准修订、查看二维码、查看原卷
        // 2、状态区：等待完成、已检查完成、已订正
        // 3、题目预览区：正确错误、订正结果、批改内容

        //
        $detail_vdata = [
            'paper_uuid' => $paper_uuid,
            'paper_alias' => '',
            'student' => '',
            'status' => [
                'pratice' => 0,
                'confirm' => 0,
                'corrected' => 0
            ],
            'imgs' => [],
            'quizes' => [],
            'quiz_ids' => [],
            'self_corrections' => []
        ];
        //

        $current_paper = $pp->where('uuid', $paper_uuid)->get();
        $current_student = $pp->find($current_paper[0]->id)->student()->first();
        $current_paper_sc = $pp->find($current_paper[0]->id)->self_corrections()->first();

        $detail_vdata['paper_alias'] = $current_paper[0]->alias;
        $detail_vdata['student'] = $current_student->real_name;

        if ($current_paper[0]->complete_self_correct === 1)
        {
            $detail_vdata['status']['corrected'] = 1;
        } elseif ($current_paper[0]->permit_self_correct === 1) {
            $detail_vdata['status']['confirm'] = 1;
        } else {
            $detail_vdata['status']['pratice'] = 1;
        }

        $detail_vdata['imgs'] = json_decode($current_paper[0]->img_path, true);
        $detail_vdata['quizes'] = json_decode($current_paper[0]->quizes, true);
        $detail_vdata['quiz_ids'] = json_decode($current_paper[0]->quiz_ids, true);

        $detail_vdata['self_corrections'] = json_decode($current_paper_sc->self_corrections, true);

        //dump($detail_vdata);

        return view('dash.detail', $detail_vdata);
    }

    public function permit()
    {
        // 参考 papers/upload and permit
    }
}
