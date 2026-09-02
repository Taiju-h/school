<?php
/*
 * SCHOOL Git Deployer
 * Runtime location: /home/heartf/heartf.com/public_html/school/__deploy/index.php
 * Source location:  .deploy/school/index.php
 * Compatible with PHP 5.3+
 */

date_default_timezone_set('Asia/Tokyo');
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');

if (session_id() === '') {
    session_start();
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function secureEquals($known, $given)
{
    $known = (string)$known;
    $given = (string)$given;
    if (strlen($known) !== strlen($given)) {
        return false;
    }
    $result = 0;
    $length = strlen($known);
    for ($i = 0; $i < $length; $i++) {
        $result |= ord($known[$i]) ^ ord($given[$i]);
    }
    return $result === 0;
}

function createToken()
{
    if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(32);
        if ($bytes !== false) {
            return bin2hex($bytes);
        }
    }
    return hash('sha256', uniqid(mt_rand(), true));
}

function runCommand($command, &$exitCode)
{
    $output = array();
    $exitCode = 127;
    if (!function_exists('exec')) {
        return 'ERROR: PHP exec() is disabled.';
    }
    exec($command . ' 2>&1', $output, $exitCode);
    return implode("\n", $output);
}

function runGit($repoRoot, $arguments, &$exitCode)
{
    $git = is_executable('/usr/bin/git') ? '/usr/bin/git' : 'git';
    $command = 'cd ' . escapeshellarg($repoRoot)
        . ' && GIT_TERMINAL_PROMPT=0 ' . $git . ' ' . $arguments;
    return runCommand($command, $exitCode);
}

function gitValue($repoRoot, $arguments, $fallback)
{
    $exitCode = 0;
    $value = trim(runGit($repoRoot, $arguments, $exitCode));
    return $exitCode === 0 && $value !== '' ? $value : $fallback;
}

function getClientIp()
{
    $candidates = array('HTTP_X_REAL_IP', 'MMDB_ADDR', 'REMOTE_ADDR');
    foreach ($candidates as $key) {
        if (!empty($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP)) {
            return $_SERVER[$key];
        }
    }
    return 'unknown';
}

function readAllowedIps($path)
{
    if (!is_file($path)) {
        return array();
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return array();
    }
    $result = array();
    foreach ($lines as $line) {
        $ip = trim($line);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $result[$ip] = $ip;
        }
    }
    return array_values($result);
}

function writeAllowedIps($path, $ips)
{
    sort($ips, SORT_STRING);
    $contents = count($ips) ? implode("\n", $ips) . "\n" : '';
    return file_put_contents($path, $contents, LOCK_EX) !== false;
}

function appendHistory($path, $entry)
{
    $json = json_encode($entry);
    if ($json === false) {
        return false;
    }
    return file_put_contents($path, $json . "\n", FILE_APPEND | LOCK_EX) !== false;
}

function readHistory($path, $limit)
{
    if (!is_file($path)) {
        return array();
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return array();
    }
    $lines = array_reverse($lines);
    $rows = array();
    foreach ($lines as $line) {
        $row = json_decode($line, true);
        if (is_array($row)) {
            $rows[] = $row;
        }
        if (count($rows) >= $limit) {
            break;
        }
    }
    return $rows;
}

function clipOutput($text, $maxLength)
{
    $text = (string)$text;
    if (strlen($text) <= $maxLength) {
        return $text;
    }
    return substr($text, 0, $maxLength) . "\n... output truncated ...";
}

function trackedTreeIsClean($repoRoot, &$details)
{
    $exitCode = 0;
    $details = runGit($repoRoot, 'status --short --untracked-files=no', $exitCode);
    return $exitCode === 0 && trim($details) === '';
}

function refreshRuntime($repoRoot, $runtimeDir)
{
    $sourceDir = $repoRoot . '/.deploy/school';
    if (!is_file($sourceDir . '/index.php')) {
        return true;
    }
    if (!is_dir($runtimeDir) && !@mkdir($runtimeDir, 0755, true)) {
        return false;
    }
    $ok = copy($sourceDir . '/index.php', $runtimeDir . '/index.php');
    if (is_file($sourceDir . '/.htaccess')) {
        $ok = copy($sourceDir . '/.htaccess', $runtimeDir . '/.htaccess') && $ok;
    }
    @chmod($runtimeDir . '/index.php', 0644);
    @chmod($runtimeDir . '/.htaccess', 0644);
    return $ok;
}

$translations = array(
    'ja' => array(
        'title' => 'SCHOOL デプロイヤー',
        'subtitle' => 'school.heartf.com / GitHub 本番反映',
        'environment' => '環境',
        'production' => '本番',
        'target' => '対象',
        'path' => 'パス',
        'branch' => 'ブランチ',
        'commit' => '現在のコミット',
        'commit_time' => 'コミット日時',
        'client_ip' => '接続元IP',
        'user' => '認証ユーザー',
        'working_tree' => '作業ツリー',
        'clean' => '変更なし',
        'dirty' => '未反映のローカル変更あり',
        'actions' => '操作',
        'diff' => 'GitHubとの差分確認',
        'deploy' => '本番へ反映',
        'rollback' => '1つ前へ戻す',
        'reload' => '表示更新',
        'history' => '修正・デプロイ履歴',
        'no_history' => '履歴はまだありません。',
        'time' => '日時',
        'operation' => '操作',
        'from' => '変更前',
        'to' => '変更後',
        'result' => '結果',
        'ip_control' => 'デプロイ許可IP',
        'ip_empty' => '未登録のため、Basic認証済みユーザーを許可しています。',
        'add_current_ip' => '現在のIPを追加',
        'remove' => '削除',
        'allowed' => '許可済み',
        'blocked' => '未許可（反映・ロールバック不可）',
        'output' => '実行結果',
        'confirm_deploy' => 'GitHubのmainを本番へ反映します。よろしいですか？',
        'confirm_rollback' => '本番を1コミット前へ戻します。よろしいですか？',
        'confirm_remove' => 'このIPを削除しますか？',
        'github' => 'GitHubを開く',
        'note' => '画面表示だけではfetch・pull・resetを実行しません。',
    ),
    'en' => array(
        'title' => 'SCHOOL Deployer',
        'subtitle' => 'school.heartf.com / GitHub production deployment',
        'environment' => 'Environment',
        'production' => 'Production',
        'target' => 'Target',
        'path' => 'Path',
        'branch' => 'Branch',
        'commit' => 'Current commit',
        'commit_time' => 'Commit time',
        'client_ip' => 'Client IP',
        'user' => 'Authenticated user',
        'working_tree' => 'Working tree',
        'clean' => 'Clean',
        'dirty' => 'Local tracked changes detected',
        'actions' => 'Actions',
        'diff' => 'Compare with GitHub',
        'deploy' => 'Deploy to production',
        'rollback' => 'Roll back one commit',
        'reload' => 'Refresh status',
        'history' => 'Change and deployment history',
        'no_history' => 'No history yet.',
        'time' => 'Time',
        'operation' => 'Operation',
        'from' => 'From',
        'to' => 'To',
        'result' => 'Result',
        'ip_control' => 'Deployment IP allowlist',
        'ip_empty' => 'Empty: authenticated users are currently allowed.',
        'add_current_ip' => 'Add current IP',
        'remove' => 'Remove',
        'allowed' => 'Allowed',
        'blocked' => 'Not allowed (deploy and rollback disabled)',
        'output' => 'Command result',
        'confirm_deploy' => 'Deploy GitHub main to production?',
        'confirm_rollback' => 'Roll production back by one commit?',
        'confirm_remove' => 'Remove this IP?',
        'github' => 'Open GitHub',
        'note' => 'Opening this page does not run fetch, pull, or reset.',
    ),
);

$lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : (isset($_SESSION['school_deploy_lang']) ? $_SESSION['school_deploy_lang'] : 'ja');
if ($lang !== 'en') {
    $lang = 'ja';
}
$_SESSION['school_deploy_lang'] = $lang;

function t($key)
{
    global $translations, $lang;
    return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
}

$authUser = '';
if (!empty($_SERVER['REMOTE_USER'])) {
    $authUser = $_SERVER['REMOTE_USER'];
} elseif (!empty($_SERVER['PHP_AUTH_USER'])) {
    $authUser = $_SERVER['PHP_AUTH_USER'];
}
if ($authUser === '') {
    header('HTTP/1.1 403 Forbidden');
    exit('SCHOOL Deployer: Basic authentication is not configured.');
}

$repoRoot = realpath(dirname(__FILE__) . '/..');
$runtimeDir = dirname(__FILE__);
$stateDir = '/home/heartf/.school-deployer';
$historyFile = $stateDir . '/history.jsonl';
$allowedIpFile = $stateDir . '/allowed-ips.txt';
$lockFile = $stateDir . '/deploy.lock';
$bootError = '';

if ($repoRoot === false || !is_dir($repoRoot . '/.git')) {
    $bootError = 'Git repository was not found.';
} elseif (!is_dir($stateDir) && !@mkdir($stateDir, 0700, true)) {
    $bootError = 'State directory could not be created: ' . $stateDir;
} else {
    @chmod($stateDir, 0700);
    if (!is_file($allowedIpFile)) {
        @file_put_contents($allowedIpFile, '');
        @chmod($allowedIpFile, 0600);
    }
}

if (empty($_SESSION['school_deploy_csrf'])) {
    $_SESSION['school_deploy_csrf'] = createToken();
}
$csrfToken = $_SESSION['school_deploy_csrf'];
$clientIp = getClientIp();
$allowedIps = readAllowedIps($allowedIpFile);
$ipRestricted = count($allowedIps) > 0;
$ipAllowed = !$ipRestricted || in_array($clientIp, $allowedIps, true);
$message = '';
$messageType = 'info';
$commandOutput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $bootError === '') {
    $postedToken = isset($_POST['csrf']) ? $_POST['csrf'] : '';
    if (!secureEquals($csrfToken, $postedToken)) {
        $message = 'Invalid CSRF token. Reload the page.';
        $messageType = 'error';
    } else {
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        $lockHandle = @fopen($lockFile, 'c');
        if ($lockHandle === false || !flock($lockHandle, LOCK_EX | LOCK_NB)) {
            $message = 'Another deployment operation is running.';
            $messageType = 'error';
        } else {
            if ($action === 'add_ip') {
                if (filter_var($clientIp, FILTER_VALIDATE_IP) && !in_array($clientIp, $allowedIps, true)) {
                    $allowedIps[] = $clientIp;
                }
                if (writeAllowedIps($allowedIpFile, $allowedIps)) {
                    $message = 'IP added: ' . $clientIp;
                    $messageType = 'success';
                } else {
                    $message = 'Could not update the IP allowlist.';
                    $messageType = 'error';
                }
            } elseif ($action === 'remove_ip') {
                $removeIp = isset($_POST['ip']) ? trim($_POST['ip']) : '';
                if (!filter_var($removeIp, FILTER_VALIDATE_IP)) {
                    $message = 'Invalid IP address.';
                    $messageType = 'error';
                } else {
                    $newIps = array();
                    foreach ($allowedIps as $ip) {
                        if ($ip !== $removeIp) {
                            $newIps[] = $ip;
                        }
                    }
                    if (writeAllowedIps($allowedIpFile, $newIps)) {
                        $message = 'IP removed: ' . $removeIp;
                        $messageType = 'success';
                    } else {
                        $message = 'Could not update the IP allowlist.';
                        $messageType = 'error';
                    }
                }
            } elseif ($action === 'diff') {
                $exitCode = 0;
                $fetchOutput = runGit($repoRoot, 'fetch --prune origin main', $exitCode);
                if ($exitCode !== 0) {
                    $message = 'Git fetch failed.';
                    $messageType = 'error';
                    $commandOutput = $fetchOutput;
                } else {
                    $logCode = 0;
                    $log = runGit($repoRoot, 'log --oneline --decorate HEAD..origin/main', $logCode);
                    $statCode = 0;
                    $stat = runGit($repoRoot, 'diff --stat HEAD..origin/main', $statCode);
                    $nameCode = 0;
                    $names = runGit($repoRoot, 'diff --name-status HEAD..origin/main', $nameCode);
                    $commandOutput = "Commits:\n" . ($log !== '' ? $log : '(none)')
                        . "\n\nFiles:\n" . ($names !== '' ? $names : '(none)')
                        . "\n\nSummary:\n" . ($stat !== '' ? $stat : '(no differences)');
                    $message = 'Comparison completed.';
                    $messageType = 'success';
                }
            } elseif ($action === 'deploy' || $action === 'rollback') {
                $allowedIps = readAllowedIps($allowedIpFile);
                $ipRestricted = count($allowedIps) > 0;
                $ipAllowed = !$ipRestricted || in_array($clientIp, $allowedIps, true);
                if (!$ipAllowed) {
                    $message = 'This IP is not allowed to deploy: ' . $clientIp;
                    $messageType = 'error';
                } else {
                    $dirtyDetails = '';
                    if (!trackedTreeIsClean($repoRoot, $dirtyDetails)) {
                        $message = 'Deployment stopped: tracked local changes exist.';
                        $messageType = 'error';
                        $commandOutput = $dirtyDetails;
                    } else {
                        $before = gitValue($repoRoot, 'rev-parse HEAD', 'unknown');
                        if ($action === 'deploy') {
                            $fetchCode = 0;
                            $fetchOutput = runGit($repoRoot, 'fetch --prune origin main', $fetchCode);
                            if ($fetchCode !== 0) {
                                $message = 'Deployment stopped: git fetch failed.';
                                $messageType = 'error';
                                $commandOutput = $fetchOutput;
                            } else {
                                $branch = gitValue($repoRoot, 'rev-parse --abbrev-ref HEAD', 'unknown');
                                if ($branch !== 'main') {
                                    $message = 'Deployment stopped: current branch is not main (' . $branch . ').';
                                    $messageType = 'error';
                                } else {
                                    $mergeCode = 0;
                                    $mergeOutput = runGit($repoRoot, 'merge --ff-only origin/main', $mergeCode);
                                    $after = gitValue($repoRoot, 'rev-parse HEAD', 'unknown');
                                    if ($mergeCode === 0 && refreshRuntime($repoRoot, $runtimeDir)) {
                                        $message = $before === $after ? 'Already up to date.' : 'Deployment completed.';
                                        $messageType = 'success';
                                        $commandOutput = $mergeOutput;
                                        appendHistory($historyFile, array(
                                            'time' => date('Y-m-d H:i:s T'),
                                            'action' => 'deploy',
                                            'from' => $before,
                                            'to' => $after,
                                            'user' => $authUser,
                                            'ip' => $clientIp,
                                            'result' => 'success',
                                        ));
                                    } else {
                                        $message = 'Deployment failed.';
                                        $messageType = 'error';
                                        $commandOutput = $mergeOutput;
                                        appendHistory($historyFile, array(
                                            'time' => date('Y-m-d H:i:s T'),
                                            'action' => 'deploy',
                                            'from' => $before,
                                            'to' => $after,
                                            'user' => $authUser,
                                            'ip' => $clientIp,
                                            'result' => 'failed',
                                        ));
                                    }
                                }
                            }
                        } else {
                            $targetCode = 0;
                            $target = trim(runGit($repoRoot, 'rev-parse HEAD^', $targetCode));
                            if ($targetCode !== 0 || !preg_match('/^[0-9a-f]{40}$/', $target)) {
                                $message = 'Rollback stopped: there is no previous commit.';
                                $messageType = 'error';
                            } else {
                                $resetCode = 0;
                                $resetOutput = runGit($repoRoot, 'reset --hard ' . escapeshellarg($target), $resetCode);
                                $after = gitValue($repoRoot, 'rev-parse HEAD', 'unknown');
                                if ($resetCode === 0) {
                                    refreshRuntime($repoRoot, $runtimeDir);
                                    $message = 'Rollback completed.';
                                    $messageType = 'success';
                                    $commandOutput = $resetOutput;
                                    appendHistory($historyFile, array(
                                        'time' => date('Y-m-d H:i:s T'),
                                        'action' => 'rollback',
                                        'from' => $before,
                                        'to' => $after,
                                        'user' => $authUser,
                                        'ip' => $clientIp,
                                        'result' => 'success',
                                    ));
                                } else {
                                    $message = 'Rollback failed.';
                                    $messageType = 'error';
                                    $commandOutput = $resetOutput;
                                }
                            }
                        }
                    }
                }
            } else {
                $message = 'Unknown action.';
                $messageType = 'error';
            }
            flock($lockHandle, LOCK_UN);
            fclose($lockHandle);
        }
    }
    $allowedIps = readAllowedIps($allowedIpFile);
    $ipRestricted = count($allowedIps) > 0;
    $ipAllowed = !$ipRestricted || in_array($clientIp, $allowedIps, true);
}

$branch = $bootError === '' ? gitValue($repoRoot, 'rev-parse --abbrev-ref HEAD', 'unknown') : 'unknown';
$commit = $bootError === '' ? gitValue($repoRoot, 'rev-parse --short HEAD', 'unknown') : 'unknown';
$commitTime = $bootError === '' ? gitValue($repoRoot, 'log -1 --pretty=format:%ci', 'unknown') : 'unknown';
$statusCode = 0;
$trackedStatus = $bootError === '' ? runGit($repoRoot, 'status --short --untracked-files=no', $statusCode) : '';
$treeClean = $bootError === '' && $statusCode === 0 && trim($trackedStatus) === '';
$history = readHistory($historyFile, 30);
?>
<!DOCTYPE html>
<html lang="<?php echo h($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h(t('title')); ?></title>
    <style>
        :root{--primary:#176b53;--primary2:#0f513f;--bg:#f3f7f5;--card:#fff;--text:#17231f;--muted:#68756f;--line:#dbe5e0;--ok:#18794e;--danger:#c93535;--warn:#a96600}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font-family:"Segoe UI","Noto Sans JP",sans-serif;line-height:1.5}.wrap{max-width:1180px;margin:0 auto;padding:28px 20px 50px}
        header{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-bottom:22px}.brand h1{margin:0;color:var(--primary);font-size:28px}.brand p{margin:4px 0 0;color:var(--muted)}
        .language{display:flex;gap:6px;flex:0 0 auto}.language a{padding:7px 11px;border:1px solid var(--line);border-radius:8px;background:#fff;color:var(--text);text-decoration:none;font-size:13px}.language a.active{background:var(--primary);border-color:var(--primary);color:#fff}
        .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.card{background:var(--card);border:1px solid var(--line);border-radius:14px;box-shadow:0 4px 18px rgba(22,55,43,.06);padding:20px}.card.full{grid-column:1/-1}.card h2{margin:0 0 15px;font-size:18px}
        .facts{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.fact{padding:12px;background:#f8faf9;border:1px solid #e7eeea;border-radius:9px}.fact small{display:block;color:var(--muted);margin-bottom:3px}.fact strong{display:block;overflow-wrap:anywhere}
        .status-ok{color:var(--ok)}.status-bad{color:var(--danger)}.notice{margin-bottom:18px;padding:13px 15px;border-radius:10px;border:1px solid}.notice.info{background:#eef5ff;border-color:#cddfff}.notice.success{background:#edf9f1;border-color:#bfe5cb;color:#12633d}.notice.error{background:#fff0f0;border-color:#f0c2c2;color:#9d2323}
        .actions{display:flex;flex-wrap:wrap;gap:10px}.button,button{appearance:none;border:0;border-radius:9px;padding:11px 15px;font-weight:700;cursor:pointer;text-decoration:none;font-size:14px}.primary{background:var(--primary);color:#fff}.secondary{background:#e8f0ec;color:var(--primary2)}.danger{background:#ffe9e9;color:#a82323}.outline{background:#fff;color:var(--text);border:1px solid var(--line)}button:disabled{opacity:.45;cursor:not-allowed}.hint{margin:12px 0 0;color:var(--muted);font-size:13px}
        pre{margin:0;padding:15px;background:#13201b;color:#e7f6ef;border-radius:10px;overflow:auto;max-height:460px;white-space:pre-wrap;word-break:break-word;font:13px/1.55 Consolas,monospace}
        .ip-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:9px 0;border-bottom:1px solid var(--line)}.ip-row:last-child{border-bottom:0}.inline{display:inline}
        table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:10px 8px;text-align:left;border-bottom:1px solid var(--line);vertical-align:top}th{color:var(--muted);font-weight:600}.sha{font-family:Consolas,monospace;white-space:nowrap}.meta{color:var(--muted);font-size:12px}.top-links{margin-top:10px}.top-links a{color:var(--primary)}
        @media(max-width:760px){.wrap{padding:18px 12px 35px}header{align-items:flex-start}.brand h1{font-size:22px}.grid{grid-template-columns:1fr}.card.full{grid-column:auto}.facts{grid-template-columns:1fr}.table-wrap{overflow-x:auto}.actions{flex-direction:column}.actions form,.actions .button,.actions button{width:100%}}
    </style>
</head>
<body>
<div class="wrap">
    <header>
        <div class="brand">
            <h1><?php echo h(t('title')); ?></h1>
            <p><?php echo h(t('subtitle')); ?></p>
            <div class="top-links"><a href="https://github.com/Taiju-h/school" target="_blank" rel="noopener noreferrer"><?php echo h(t('github')); ?></a></div>
        </div>
        <nav class="language" aria-label="Language">
            <a href="?lang=ja" class="<?php echo $lang === 'ja' ? 'active' : ''; ?>">日本語</a>
            <a href="?lang=en" class="<?php echo $lang === 'en' ? 'active' : ''; ?>">English</a>
        </nav>
    </header>

    <?php if ($bootError !== ''): ?><div class="notice error"><?php echo h($bootError); ?></div><?php endif; ?>
    <?php if ($message !== ''): ?><div class="notice <?php echo h($messageType); ?>"><?php echo h($message); ?></div><?php endif; ?>

    <div class="grid">
        <section class="card full">
            <h2>Status</h2>
            <div class="facts">
                <div class="fact"><small><?php echo h(t('environment')); ?></small><strong><?php echo h(t('production')); ?></strong></div>
                <div class="fact"><small><?php echo h(t('target')); ?></small><strong>school.heartf.com</strong></div>
                <div class="fact"><small><?php echo h(t('path')); ?></small><strong><?php echo h($repoRoot); ?></strong></div>
                <div class="fact"><small><?php echo h(t('branch')); ?></small><strong><?php echo h($branch); ?></strong></div>
                <div class="fact"><small><?php echo h(t('commit')); ?></small><strong class="sha"><?php echo h($commit); ?></strong></div>
                <div class="fact"><small><?php echo h(t('commit_time')); ?></small><strong><?php echo h($commitTime); ?></strong></div>
                <div class="fact"><small><?php echo h(t('client_ip')); ?></small><strong><?php echo h($clientIp); ?></strong></div>
                <div class="fact"><small><?php echo h(t('user')); ?></small><strong><?php echo h($authUser); ?></strong></div>
                <div class="fact"><small><?php echo h(t('working_tree')); ?></small><strong class="<?php echo $treeClean ? 'status-ok' : 'status-bad'; ?>"><?php echo h($treeClean ? t('clean') : t('dirty')); ?></strong></div>
                <div class="fact"><small>IP</small><strong class="<?php echo $ipAllowed ? 'status-ok' : 'status-bad'; ?>"><?php echo h($ipAllowed ? t('allowed') : t('blocked')); ?></strong></div>
            </div>
        </section>

        <section class="card full">
            <h2><?php echo h(t('actions')); ?></h2>
            <div class="actions">
                <form method="post">
                    <input type="hidden" name="csrf" value="<?php echo h($csrfToken); ?>">
                    <input type="hidden" name="action" value="diff">
                    <button type="submit" class="secondary" <?php echo $bootError !== '' ? 'disabled' : ''; ?>><?php echo h(t('diff')); ?></button>
                </form>
                <form method="post" onsubmit="return confirm(<?php echo h(json_encode(t('confirm_deploy'))); ?>);">
                    <input type="hidden" name="csrf" value="<?php echo h($csrfToken); ?>">
                    <input type="hidden" name="action" value="deploy">
                    <button type="submit" class="primary" <?php echo ($bootError !== '' || !$treeClean || !$ipAllowed) ? 'disabled' : ''; ?>><?php echo h(t('deploy')); ?></button>
                </form>
                <form method="post" onsubmit="return confirm(<?php echo h(json_encode(t('confirm_rollback'))); ?>);">
                    <input type="hidden" name="csrf" value="<?php echo h($csrfToken); ?>">
                    <input type="hidden" name="action" value="rollback">
                    <button type="submit" class="danger" <?php echo ($bootError !== '' || !$treeClean || !$ipAllowed) ? 'disabled' : ''; ?>><?php echo h(t('rollback')); ?></button>
                </form>
                <a class="button outline" href="?lang=<?php echo h($lang); ?>"><?php echo h(t('reload')); ?></a>
            </div>
            <p class="hint"><?php echo h(t('note')); ?></p>
        </section>

        <?php if ($commandOutput !== ''): ?>
        <section class="card full">
            <h2><?php echo h(t('output')); ?></h2>
            <pre><?php echo h(clipOutput($commandOutput, 30000)); ?></pre>
        </section>
        <?php endif; ?>

        <section class="card">
            <h2><?php echo h(t('ip_control')); ?></h2>
            <?php if (!count($allowedIps)): ?><p class="hint"><?php echo h(t('ip_empty')); ?></p><?php endif; ?>
            <?php foreach ($allowedIps as $ip): ?>
                <div class="ip-row">
                    <span class="sha"><?php echo h($ip); ?></span>
                    <form method="post" class="inline" onsubmit="return confirm(<?php echo h(json_encode(t('confirm_remove'))); ?>);">
                        <input type="hidden" name="csrf" value="<?php echo h($csrfToken); ?>">
                        <input type="hidden" name="action" value="remove_ip">
                        <input type="hidden" name="ip" value="<?php echo h($ip); ?>">
                        <button type="submit" class="danger"><?php echo h(t('remove')); ?></button>
                    </form>
                </div>
            <?php endforeach; ?>
            <?php if (filter_var($clientIp, FILTER_VALIDATE_IP) && !in_array($clientIp, $allowedIps, true)): ?>
                <form method="post" style="margin-top:14px">
                    <input type="hidden" name="csrf" value="<?php echo h($csrfToken); ?>">
                    <input type="hidden" name="action" value="add_ip">
                    <button type="submit" class="secondary"><?php echo h(t('add_current_ip')); ?> (<?php echo h($clientIp); ?>)</button>
                </form>
            <?php endif; ?>
        </section>

        <section class="card">
            <h2>Tracked changes</h2>
            <?php if ($treeClean): ?>
                <p class="status-ok"><?php echo h(t('clean')); ?></p>
            <?php else: ?>
                <pre><?php echo h(clipOutput($trackedStatus, 10000)); ?></pre>
            <?php endif; ?>
        </section>

        <section class="card full">
            <h2><?php echo h(t('history')); ?></h2>
            <?php if (!count($history)): ?>
                <p class="hint"><?php echo h(t('no_history')); ?></p>
            <?php else: ?>
                <div class="table-wrap"><table>
                    <thead><tr><th><?php echo h(t('time')); ?></th><th><?php echo h(t('operation')); ?></th><th><?php echo h(t('from')); ?></th><th><?php echo h(t('to')); ?></th><th><?php echo h(t('user')); ?> / IP</th><th><?php echo h(t('result')); ?></th></tr></thead>
                    <tbody>
                    <?php foreach ($history as $row): ?>
                        <tr>
                            <td><?php echo h(isset($row['time']) ? $row['time'] : ''); ?></td>
                            <td><?php echo h(isset($row['action']) ? $row['action'] : ''); ?></td>
                            <td class="sha"><?php echo h(isset($row['from']) ? substr($row['from'], 0, 10) : ''); ?></td>
                            <td class="sha"><?php echo h(isset($row['to']) ? substr($row['to'], 0, 10) : ''); ?></td>
                            <td><?php echo h(isset($row['user']) ? $row['user'] : ''); ?><div class="meta"><?php echo h(isset($row['ip']) ? $row['ip'] : ''); ?></div></td>
                            <td><?php echo h(isset($row['result']) ? $row['result'] : ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table></div>
            <?php endif; ?>
        </section>
    </div>
</div>
</body>
</html>
