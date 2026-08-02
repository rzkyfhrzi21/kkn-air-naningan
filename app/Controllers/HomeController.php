<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/SiteData.php';

final class HomeController
{
    public function index(): void
    {
        $datasets = SiteData::all();
        $totalRecords = array_sum(array_map('count', $datasets));

        require __DIR__ . '/../Views/public/home/index.php';
    }
}
