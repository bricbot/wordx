<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use File;
use Debugbar;

use App\Models\Paper;
use App\User;

use App\Models\SelfCorrection;
use App\Models\AssistantConfirm;

class PapersController extends Controller
{
    //
    function __construct()
    {
        \Debugbar::enable();
    }

    // done
    public function student($paper_uuid = null, Paper $pp)
    {
        // Essential Var

        $student_vdata = [
            'paper_uuid' => $paper_uuid,
            'student_id' => '',
            'show_interface' => 0,
            'blade_name' => '',
            'correction_data' => [
                'quizes' => null,
                'quiz_ids' => null
            ]
        ];
        // Essential Var
        

        if ($paper_uuid === null)
        {
            session()->flash('danger', '无效访问，缺少UUID！');
        }

        // step1 检查是否可以自我订正

        $current_paper = $pp->where(['uuid' => $paper_uuid])->get();
        //dd($current_paper[0]->id);

        if ($current_paper->count() <= 0)
        {
            session()->flash('danger', '错误的UUID！');
        } elseif ($current_paper[0]->permit_self_correct !== 1) {
            session()->flash('warning', '请先让家长/助理检查确认已经做完！');
        } else {
            $student_vdata['show_interface'] = 1;
            $student_vdata['student_id'] = $current_paper[0]->student_id;

            // step2 进入订正界面
            $student_vdata['blade_name'] = '_' . $current_paper[0]->correct_template;

            $quizes = json_decode($current_paper[0]->quizes, true);
            $quiz_ids = json_decode($current_paper[0]->quiz_ids, true);

            //dump($quizes);
            //dump($quiz_ids);

            $student_vdata['correction_data']['quizes'] = $quizes;
            $student_vdata['correction_data']['quiz_ids'] = $quiz_ids;
        }

        return view('papers.student', $student_vdata);
    }

    public function submit_self_correction($paper_uuid = null, Request $r, Paper $pp, User $u, SelfCorrection $sc)
    {
        if ($paper_uuid === null)
        {
            session()->flash('danger', '无效访问，缺少UUID！');
        }

        //
        $now = date("Y-m-d H:i:s");
        $selfcorrection_data_insert_update = [
            'paper_uuid' => $paper_uuid,
            'student_id' => $r->student_id,
            'quiz_ids' => '',
            'self_corrections' => [],
            'created_at' => $now
        ];
        //

        $current_paper = $pp->where('uuid', $paper_uuid)->get();
        $current_quizs = json_decode($current_paper[0]->quizes, true);
        
        
        for ($i=0; $i < count($current_quizs); $i++) { 
            $t = "quiz_".$i;
            $a = explode('::', $r->request->get('quiz_'.$i));
            
            $s = [
                'id' => $a[0],
                'result' => $a[1],
                'comment' => $a[2]
            ];
            
            $selfcorrection_data_insert_update['self_corrections'][] = $s;
        }

        $selfcorrection_data_insert_update['quiz_ids'] = $current_paper[0]->quiz_ids;
        $selfcorrection_data_insert_update['self_corrections'] = json_encode($selfcorrection_data_insert_update['self_corrections']);

        $is_sc_exist = $sc->where('paper_uuid', $paper_uuid)->get();

        if ($is_sc_exist->count() > 0) {
            $selfcorrection_data_insert_update['updated_at'] = $now;
            unset($selfcorrection_data_insert_update['created_at']);

            $result_selfcorrection_insert_update = $sc
            ->where('paper_uuid', $uuid)
            ->update($selfcorrection_data_insert_update);

        } else {
            $result_selfcorrection_insert_update = $sc->insert($selfcorrection_data_insert_update);
        }

        if ($result_selfcorrection_insert_update) 
        {
            // 更新 Paper 完成状态
            $pp->where('uuid', $paper_uuid)->update([
                'complete_self_correct' => 1,
                'self_correct_time' => $now
            ]);

            session()->flash('success', '订正成功，可以关闭这个页面了！');
        } else {
            session()->flash('danger', '订正失败，请联系管理老师！');
        }

        return redirect()->route('papers.index', $paper_uuid);
    }

    // done
    public function assistant($paper_uuid = null, Paper $pp)
    {
        if ($paper_uuid === null)
        {
            return "无效访问，缺少UUID";
        }

        // 基本数据
        $assistant_vdata = [
            'paper_uuid' => $paper_uuid,
            'total_pages' => '',
            'uploaded_pages' => '',
            'paper_name' => '',
            'student_name' => ''
        ];
        // 基本数据

        // step1 检查是否可以自我订正

        $current_paper = $pp->where(['uuid' => $paper_uuid])->get();
        $current_student = $pp->find($current_paper[0]->student_id)->student;
        //dd($current_paper[0]->id);

        if ($current_paper->count() <= 0)
        {
            session()->flash('danger', '错误的UUID！');
        } else {
            if ($current_paper[0]->permit_self_correct === 1) {
                // 已经批准过订正

                if ($current_paper[0]->complete_self_correct === 0) {
                    session()->flash('success', '学生正在订正，请等候学生提交。');
                } elseif ($current_paper[0]->complete_self_correct === 1) {
                    session()->flash('success', '学生已经完成了订正。');
                } else {
                    session()->flash('danger', '这张试卷有异常，请联系管理员。');
                }
            }

            $uploaded_img = json_decode($current_paper[0]->img_path,true);

            $assistant_vdata['paper_name'] = $current_paper[0]->alias;
            $assistant_vdata['student_name'] = $current_student->real_name;
            $assistant_vdata['total_pages'] = $current_paper[0]->total_pages;

            $assistant_vdata['uploaded_pages'] = ($uploaded_img === null) ? 0 : count($uploaded_img);
        }
        
        return view('papers.assistant', $assistant_vdata);

    }

    // done
    public function upload_and_permit($paper_uuid = null, Request $r, Paper $pp, User $u, AssistantConfirm $ac)
    {
        if ($paper_uuid === null)
        {
            return "无效访问，缺少UUID";
        }

        // 基本数据
        $current_paper = $pp->where('uuid', $paper_uuid)->get();
        $paper_data_update = [
            'img_path' => [],
            'permit_self_correct' => 0,
            'confirm_time' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ];
        // 基本数据


        $uploaded = 0;
        if ((int)count($r->files) !== (int)$r->total_img)
        {
            session()->flash('danger', '上传的图片数量错误');
            return redirect()->route('papers.assistant', $paper_uuid);
        } 

        for ($i=0; $i < (int)$r->total_img; $i++) { 
            $file = $r->file('pic_img_' . ($i+1));

            if ($file->isValid())
            {
                $original_name = $file->getClientOriginalName();
                $ext_name = $file->getClientOriginalExtension();
                $real_path = $file->getRealPath();
                $type = $file->getClientMimeType();

                $target_path = public_path('papers_img') . '/' . $paper_uuid;
                if ( ! File::exists($target_path) )
                {
                    dump(File::makeDirectory($target_path, '0755'));
                }
                // 增加一个 助手ID 标识
                $target_filename = 'paper_page_' . ($i+1) . '_assistant' . '.' . $ext_name;
                $isUpload = Storage::disk('papers_img')->put($paper_uuid . '/' . $target_filename, file_get_contents($real_path));
                if ($isUpload)
                {
                    ++$uploaded;
                    $assistant_confirm_insert = [
                        'paper_uuid' => $paper_uuid,
                        'student_id' => $current_paper[0]->student_id,
                        'img_order' => ($i+1),
                        'img_path' => $target_path . '/' . $target_filename
                    ];
                    if ($ac->insert($assistant_confirm_insert))
                    {
                        $paper_data_update['img_path'][] = [
                            'upload_by' => 'assistant',
                            'order' => ($i+1),
                            'file' => $target_path . '/' . $target_filename
                        ];
                    }
                }
                dump("pages {($i+1)} : {$isUpload}");
            }
        }

        if ($uploaded === (int)$r->total_img)
        {
            session()->flash('success', '上传完成');
            $paper_data_update['permit_self_correct'] = 1;
            $paper_data_update['img_path'] = json_encode($paper_data_update['img_path']);
            dump($paper_data_update);

            $result_paper_update = $pp->where('uuid', $paper_uuid)->update($paper_data_update);
            dump($result_paper_update);
        } else {
            session()->flash('warning', "上传未完成，{$uploaded}成功，{((int)$r->total_img - $uploaded)}失败");
        }
        return redirect()->route('papers.index', $paper_uuid);
    }

    // done
    public function teacher($paper_uuid = null)
    {
        if ($paper_uuid === null)
        {
            return "无效访问，缺少UUID";
        }

        // 跳转到页面
        return Redirect::route('dash.detail', $paper_uuid);
    }
}
