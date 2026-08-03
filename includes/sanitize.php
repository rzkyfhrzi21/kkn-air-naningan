<?php

declare(strict_types=1);

/**
 * Sanitize rich HTML produced by admin content editors (narasi profil,
 * konten berita) to a safe whitelist of tags. Strip script, event handlers,
 * and javascript: URLs.
 */
function sanitize_rich_html(string $html): string
{
    $allowed = '<p><br><strong><b><em><i><u><h2><h3><h4><blockquote><ul><ol><li><a><span>';
    $html    = strip_tags($html, $allowed);
    $html    = preg_replace('/\son\w+\s*=\s*("(?:[^"]*)"|\'(?:[^\']*)\'|[^\s>]+)/i', '', $html) ?? $html;
    $html    = preg_replace('/href\s*=\s*("javascript:[^"]*"|\'javascript:[^\']*\')/i', 'href="#"', $html) ?? $html;
    return trim($html);
}
