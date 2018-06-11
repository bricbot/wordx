<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;

use App\User;
use App\Models\Paper;
use App\Models\Question;
use App\Models\DtWord;

use UUID;

class DemoController extends Controller
{
    //
    function __construct()
    {
        \Debugbar::enable();
    }

    // done
    public function index(User $u, Paper $pp)
    {
        // 获取用户状态
        $user_data = [];
        $users = $u->all();

        if ($users->count() > 0)
        {
            $user_data['count'] = $users->count();
            foreach ($users as $key => $user) {
                $user_data['users'][] = [
                    'id' => $user['id'],
                    'account' => $user['account']
                ];
            }
        } else {
            $user_data['count'] = 0;
        }

        // 获取试卷详情
        $paper_data = [];
        $papers = $pp->all();

        if ($papers->count() > 0)
        {
            $paper_data['count'] = $papers->count();
            foreach ($papers as $key => $paper) {
                $paper_data['papers'][] = [
                    'id' => $paper['id'],
                    'alias' => $paper['alias'],
                    'uuid' => $paper['uuid']
                ];
            }
        } else {
            $paper_data['count'] = 0;
        }
        
        return view('demo.index', [
            'user_data' => $user_data,
            'paper_data' => $paper_data
        ]);
    }

    // done
    public function check_word_dt(DtWord $dt_word)
    {
        $words = $dt_word
                ->where([
                    ['id', '>', '6075'], 
                    ['id', '<', '6105']
                ])->get();
        
        $tra_word = [];
        foreach ($words->toArray() as $key => $value) {
            $tra_word[$key] = [
                'subject' => 'english',
                'book' => 'vocabulary-' . $value['book'],
                'unit' => $value['category'],
                'chapter' => '',
                'list' => $value['list'],
                'page' => $value['page'],
                'number' => $value['number'],
                'quiz' => json_encode([
                    'word' => $value['word'],
                    'symbol' => $value['symbol'],
                    'meaning' => $value['pos'] . ' ' . $value['meaning'],
                    'omeaning' => $value['omeaning']
                ]),
                'key' => ''
            ];
        }
        
        if (empty($tra_word))
        {
            return "没有数据";
        } else {
            dump($tra_word);
        }
    }

    // done
    public function refill_words(DtWord $dt_word, Request $request)
    {
        $total = 0;
        $succ = 0;
        $fail = 0;

        if ($dt_word->all()->count() > 0)
        {
            Debugbar::info('Starting refill!');
            DtWord::chunk(200, function($words) use (&$total, &$succ, &$fail, &$request) {
                $exit = 0;
                foreach ($words as $key => $value) {
                    ++$total;
                    $question = new Question;
                    $question->subject = 'english';
                    $question->book = 'vocabulary-' . $value->book;
                    $question->unit = $value->category;
                    $question->chapter = '';
                    $question->list = $value->list;
                    $question->page = $value->page;
                    $question->number = $value->number;
                    $question->quiz = json_encode([
                        'word' => $value->word,
                        'symbol' => $value->symbol,
                        'meaning' => $value->pos . ' ' . $value->meaning,
                        'omeaning' => $value->omeaning
                    ]);
                    $question->key = '';
    
                    if ($question->save())
                    {
                        ++$succ;
                    } else {
                        ++$fail;
                    }
                    
                    if ($request->fill !== null && $total > $request->fill)
                    {
                        $exit = 1;
                        return;
                    }
                }
    
                if($exit === 1)
                {
                    return false;
                }
            });
        } else {
            Debugbar::error('Source data not exist! Please using Sequel Pro to import correct sql file in "wordx" database.');
        }
        
        return $total . ' / ' . $succ . ' / ' . $fail . "\n";
    }

    //done
    public function gen_character(User $u)
    {
        $admin = [
            'account' => 'admin_test',
            'email' => 'admin@wordx.dev',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'real_name' => '管理员_测试'
        ];
        
        $teacher = [
            'account' => 'teacher_test',
            'email' => 'teacher@wordx.dev',
            'password' => bcrypt('123456'),
            'role' => 'teacher',
            'real_name' => '老师_测试'
        ];

        $assistant = [
            'account' => 'assistant_test',
            'email' => 'assistant@wordx.dev',
            'password' => bcrypt('123456'),
            'role' => 'assistant',
            'real_name' => '家长/助理_测试'
        ];

        $student = [
            'account' => 'student_test',
            'email' => 'student@wordx.dev',
            'password' => bcrypt('123456'),
            'role' => 'student',
            'real_name' => '学生_测试'
        ];

        dump($u->insert([
            $admin, $teacher, $assistant, $student
        ]));
    }

    //done
    public function gen_paper(Question $qz, Paper $pp, User $u, Request $r)
    {
        // Date

        $d = $r->pratice_time;
        $ts = mktime(0, 0, 0, substr($d, 2, 2), substr($d, 4, 2), '20'.substr($d, 0, 2));

        $start_id = random_int(1, 14000);
        $questions = $qz->where([
            ['id', '>', $start_id], 
            ['id', '<', ($start_id + 25)]])->get();
        dump($questions->toArray());

        $users = $u->all()->toArray();
        $uuid = (string)UUID::generate();

        $questions_data = [
            'uuid' => $uuid,
            'alias' => '测试卷' . substr($uuid, 0, 8),
            'quiz_ids' => '',
            'quizes' => '',
            'correct_template' => 'english_word',
            'create_time' => date('Y-m-d H:i:s'),
            'pratice_time' => date("Y-m-d", $ts),
            'teacher_id' => 2,
            'assistant_id' => 3,
            'student_id' => 4
        ];

        $quiz_ids = [];
        $quizes = [];
        foreach ($questions->toArray() as $key => $value) {
            $arrayQuiz = json_decode($value['quiz']);
            
            $q = [
                'id' => $value['id'],
                'show' => $arrayQuiz->word . '【' . $arrayQuiz->symbol . '】',
                'correction' => [
                    'meaning' => $arrayQuiz->meaning
                ],
                'optional' => [
                    'omeaning' => $arrayQuiz->omeaning
                ]
            ];

            $quiz_ids[] = $value['id'];
            $quizes[] = $q;
        }

        $questions_data['quiz_ids'] = json_encode($quiz_ids);
        $questions_data['quizes'] = json_encode($quizes);

        dump($questions_data);

        dump($pp->insert($questions_data));
    }

    //done
    public function reset_all(Paper $pp, User $u, $psk = null)
    {
        if ($psk === null)
        {
            return view("demo.confirm");
        } else {
            $now_papers = (int)($pp->all()->count());
            $now_users = (int)($u->all()->count());
            $pp->truncate();
            $u->truncate();
            $papers = $now_papers - (int)($pp->all()->count());
            $users = $now_users - (int)($u->all()->count());

            return "total papers: {$now_papers}, total users: {$now_users} |" . "| delete papers: {$papers}, delete users: {$users}";
        }
    }
}
