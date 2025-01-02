<?php

namespace Acelle\Console\Commands;

use Acelle\Model\KeywordHistory;
use Acelle\Model\User;
use Illuminate\Console\Command;
use Google\Client;
use Google\Service\SearchConsole;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;
use Google\Service\SearchConsole\ApiDimensionFilter;
use Google\Service\SearchConsole\ApiDimensionFilterGroup;

class KeywordRank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keywordsearch:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run for keyword search rank and update rank';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clients = User::select('id','website')->where('id','!=',1)->where('website','!=','')->get();
        foreach($clients as $key=>$client){
            $allKey = \Acelle\Model\keyword::where('uid', $client->id)->pluck('keyword')->toArray();
            if($allKey){
                $rankings = \Acelle\Helpers\keywordSearch($allKey, $client['website']);
                $totalRanks = [];
                foreach ($rankings as $val) {
                    if ($val['found']) {
                        $keyId = \Acelle\Model\keyword::select('id')->where('keyword', $val['keyword'])->first();
                        if(isset($keyId->id) && !empty($keyId->id)){
                            $rank = round($val['position'], 2);
                            $totalRanks[] = [
                                'uid' => $client['id'],
                                'keyword_id' => $keyId->id,
                                'ranking' => $rank,
                                'date_time' => now(),
                            ];
                            \Acelle\Model\Keyword::where('id',$keyId->id)->update(['ranking'=>$rank,'date_time'=>date('Y-m-d H:i:s')]);
                        }
                    }
                }
                KeywordHistory::insert($totalRanks);
            }
        }
        // \Log::info('Command executed successfully.');
        return Command::SUCCESS;
    }
}
