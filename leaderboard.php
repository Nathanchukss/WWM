<?php
// leaderboard.php

/**
 * Read the cookie value, parse it into an array of
 * [ ['name'=>string, 'score'=>int], … ]
 */
function get_leaderboard(): array {
    if (empty($_COOKIE['leaderboard'])) {
        return [];
    }

    $raw = $_COOKIE['leaderboard'];           
    $entries = explode('|', $raw);
    $board = [];

    foreach ($entries as $e) {
        if (strpos($e, ':') === false) {
            continue;
        }
        list($n, $s) = explode(':', $e, 2);
        $name  = urldecode($n);
        $score = intval($s);
        $board[] = ['name' => $name, 'score' => $score];
    }

    return $board;
}

/**
 * Sort descending by score, keep top-5,
 * build and write the cookie as "Name:Score|…"
 */
function save_leaderboard(array $board): void {
    usort($board, fn($a,$b) => $b['score'] <=> $a['score']);
    $top5 = array_slice($board, 0, 5);

    $parts = [];
    foreach ($top5 as $entry) {
        // urlencode in case the name has "|" or ":"
        $parts[] = urlencode($entry['name']) . ':' . $entry['score'];
    }

    $cookieVal = implode('|', $parts);
    setcookie(
      'leaderboard',
      $cookieVal,
      time() + 7*24*60*60,  // 7 days
      '/'
    );
}

/**
 * To be called in result.php after you compute $player and $score.
 */
function record_score(string $player, int $score): void {
    $board = get_leaderboard();
    $board[] = ['name' => $player, 'score' => $score];
    save_leaderboard($board);
}