<?php

namespace Acelle\Console\Commands;

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
        // \Log::info('Command executed successfully.');
        $rankings = \Acelle\Helpers\keywordSearch($request->all(), $clientDetail);
        $keyword = $data['keyword'];
        $client = new Client();
        $client->setApplicationName('RankSeo');
        $client->setAuthConfig(storage_path('app/google_service_account.json')); // Path to your service account credentials
        $client->setScopes([SearchConsole::WEBMASTERS_READONLY]);

        // Create a SearchConsole service instance
        $searchConsole = new SearchConsole($client);

        // Set the site URL
        $siteUrl = $clientDetail->website;

        // Set the date range for the query
        $startDate = now()->subDays(3)->toDateString(); // Last 3 days
        $endDate = now()->toDateString(); // Current date

        // Set the request body parameters
        $requestBody = new SearchAnalyticsQueryRequest();
        $requestBody->setStartDate($startDate);
        $requestBody->setEndDate($endDate);
        $requestBody->setDimensions(['query']);  // Query dimension (keywords)
        $requestBody->setRowLimit(10);  // Limit the response to the top 10 rows

        // Define the filter for a single keyword
        $filter = new ApiDimensionFilter([
            'dimension' => 'query',
            'operator' => 'equals',
            'expression' => $keyword // Replace with the single keyword
        ]);

        // Group the filter into a filter group
        $filterGroup = new ApiDimensionFilterGroup([
            'filters' => [$filter]
        ]);

        // Add the filter group to the request body
        $requestBody->setDimensionFilterGroups([$filterGroup]);

        // Make the request to the Google API
        $response = $searchConsole->searchanalytics->query($siteUrl, $requestBody);

        // Handle the response
        $rankings = $response->getRows();
        dd($rankings);
        if ($rankings) {
            return $rankings;
        }

        return Command::SUCCESS;
    }
}
