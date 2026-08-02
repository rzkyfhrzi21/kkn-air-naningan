<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/SiteData.php';

final class DashboardController
{
    public function index(): void
    {
        // ── Auth guard ────────────────────────────────────────────────────────
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin'])) {
            $base = defined('APP_BASE') ? APP_BASE : '';
            header('Location: ' . $base . '/admin/login');
            exit;
        }

        // ── Data untuk stat cards ─────────────────────────────────────────────
        $datasets  = SiteData::all();
        $summaries = [];
        foreach ($datasets as $key => $records) {
            $summaries[$key] = count($records);
        }

        // ── Aktivitas terbaru (placeholder — bisa diisi log nyata nanti) ──────
        $recentActivity = [];

        require __DIR__ . '/../../Views/admin/dashboard/index.php';
    }
}
