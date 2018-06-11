<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DtWord;
use App\Models\Question;

use Debugbar;

class TestController extends Controller
{
    //

    function __construct()
    {
        \Debugbar::enable();
    }

    public function test(Request $r)
    {
        return var_dump($r->fill !== null);
    }

    public function test_combind_words(DtWord $dt_word)
    {
        
    }

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
}
