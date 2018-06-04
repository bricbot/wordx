<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DtWord;
use App\Models\Question;

class TestController extends Controller
{
    //

    public function test(Request $r)
    {
        return var_dump($r->fill !== null);
    }

    public function test_combind_words(DtWord $dt_word)
    {
        $words = $dt_word
                ->where('id', '>', '6075')
                ->where('id', '<', '6105')
                ->get();
        foreach ($words as $key => $value) {
            $tra_word[$key] = [
                'subject' => 'english',
                'book' => 'vocabulary-' . $value->book,
                'unit' => $value->category,
                'chapter' => '',
                'list' => $value->list,
                'page' => $value->page,
                'number' => $value->number,
                'quiz' => json_encode([
                    'word' => $value->word,
                    'symbol' => $value->symbol,
                    'meaning' => $value->pos . ' ' . $value->meaning,
                    'omeaning' => $value->omeaning
                ]),
                'key' => ''
            ];
        }
        
        return function() {
            if (empty($tra_word))
            {
                return "没有数据";
            } else {
                return var_dump($tra_word);
            }
        };
    }

    public function refill_words(DtWord $dt_word, Request $request)
    {
        $total = 0;
        $succ = 0;
        $fail = 0;
        $tra_word = array();
        DtWord::chunk(200, function($words) use(&$total, &$succ, &$fail, &$tra_word, &$request) {
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

                $tra_word[$key] = [
                    'subject' => 'english',
                    'book' => 'vocabulary-' . $value->book,
                    'unit' => $value->category,
                    'chapter' => '',
                    'list' => $value->list,
                    'page' => $value->page,
                    'number' => $value->number,
                    'quiz' => json_encode([
                        'word' => $value->word,
                        'symbol' => $value->symbol,
                        'meaning' => $value->pos . ' ' . $value->meaning,
                        'omeaning' => $value->omeaning
                    ]),
                    'key' => ''
                ];

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

        return $total . ' / ' . $succ . ' / ' . $fail . "\n" . var_dump($tra_word);
    }
}
