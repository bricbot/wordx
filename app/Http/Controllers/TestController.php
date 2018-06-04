<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Models\DtWord;
use App\Models\Question;

class TestController extends Controller
{
    //

    public function test(DtWord $dt_word)
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
        
        return var_dump($tra_word);
    }

    public function test2(DtWord $dt_word)
    {
        $total = 0;
        $succ = 0;
        $fail = 0;
        $tra_word = array();
        DtWord::chunk(200, function($words) use(&$total, &$succ, &$fail, &$tra_word) {
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
            }
        });

        return $total . ' / ' . $succ . ' / ' . $fail;
        //return $tra_word;
    }
}
