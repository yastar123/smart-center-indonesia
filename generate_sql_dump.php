<?php
/**
 * generate_sql_dump.php
 * =====================
 * Script migrasi + seeder dengan output SQL (MySQL only).
 *
 * CARA PAKAI:
 *   php generate_sql_dump.php [opsi]
 *
 * OPSI:
 *   --env=path/to/.env        Path file .env (default: cari otomatis ke atas)
 *   --host=127.0.0.1          Host MySQL
 *   --port=3306               Port MySQL
 *   --user=root               Username MySQL
 *   --pass=secret             Password MySQL
 *   --db=nama_database        Nama database
 *   --output=dump.sql         File output (default: dump_<db>_<timestamp>.sql)
 *   --batch=200               Jumlah baris per INSERT (default: 200)
 *   --schema-only             Hanya ekspor schema (DDL / migration)
 *   --data-only               Hanya ekspor data (INSERT / seeder)
 *   --tables=tbl1,tbl2        Hanya ekspor tabel tertentu (pisah koma)
 *   --exclude=tbl1,tbl2       Kecualikan tabel tertentu
 *   --no-drop                 Jangan tambahkan DROP TABLE IF EXISTS
 *   --help                    Tampilkan bantuan
 *
 * CONTOH:
 *   php generate_sql_dump.php --env=".env" --output="dump.sql"
 *   php generate_sql_dump.php --host=localhost --user=root --pass=secret --db=mydb
 *   php generate_sql_dump.php --env=".env" --schema-only --output="migration.sql"
 *   php generate_sql_dump.php --env=".env" --data-only  --output="seeder.sql"
 *   php generate_sql_dump.php --env=".env" --tables=users,products --output="partial.sql"
 */

ini_set('memory_limit', '-1');
set_time_limit(0);

// ─── PARSE OPSI CLI ───────────────────────────────────────────────────────────

$opts = getopt('', [
    'env::', 'host::', 'port::', 'user::', 'pass::', 'db::',
    'output::', 'batch::', 'tables::', 'exclude::',
    'schema-only', 'data-only', 'no-drop', 'help',
]);

if (isset($opts['help'])) {
    echo file_get_contents(__FILE__, false, null, 3, 1200);
    exit(0);
}

// ─── FUNGSI HELPER ───────────────────────────────────────────────────────────

function parseEnv(string $path): array
{
    if (!file_exists($path)) return [];
    $result = [];
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if (preg_match('/^(["\'])(.*)\\1$/', $v, $m)) $v = $m[2];
        $result[$k] = $v;
    }
    return $result;
}

function findEnv(): array
{
    $dir = getcwd();
    for ($i = 0; $i < 8; $i++) {
        $f = $dir . DIRECTORY_SEPARATOR . '.env';
        if (file_exists($f)) return parseEnv($f);
        $parent = dirname($dir);
        if ($parent === $dir) break;
        $dir = $parent;
    }
    return [];
}

function w(string $text): void
{
    global $fh;
    fwrite($fh, $text);
}

function banner(string $title): void
{
    $line = str_repeat('-', 70);
    w("\n-- {$line}\n-- {$title}\n-- {$line}\n\n");
}

function escapeValue($v, PDO $pdo): string
{
    if ($v === null) return 'NULL';
    return $pdo->quote($v);
}

// ─── KONEKSI DATABASE ─────────────────────────────────────────────────────────

$env = isset($opts['env']) && file_exists($opts['env'])
    ? parseEnv($opts['env'])
    : findEnv();

$host   = $opts['host'] ?? $env['DB_HOST']     ?? '127.0.0.1';
$port   = $opts['port'] ?? $env['DB_PORT']     ?? '3306';
$user   = $opts['user'] ?? $env['DB_USERNAME'] ?? $env['DB_USER'] ?? 'root';
$pass   = $opts['pass'] ?? $env['DB_PASSWORD'] ?? $env['DB_PASS'] ?? '';
$dbName = $opts['db']   ?? $env['DB_DATABASE'] ?? null;

if (empty($dbName)) {
    fwrite(STDERR, "ERROR: Nama database wajib diisi (--db=nama atau DB_DATABASE di .env)\n");
    exit(1);
}

$dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ]);
} catch (Exception $e) {
    fwrite(STDERR, "Koneksi gagal: " . $e->getMessage() . "\n");
    exit(1);
}

// ─── OUTPUT FILE ──────────────────────────────────────────────────────────────

$defaultOut = 'dump_' . preg_replace('/[^A-Za-z0-9_]/', '_', $dbName) . '_' . date('Ymd_His') . '.sql';
$outputFile = $opts['output'] ?? $defaultOut;

$fh = fopen($outputFile, 'w');
if (!$fh) {
    fwrite(STDERR, "Tidak bisa membuka file output: {$outputFile}\n");
    exit(1);
}

// ─── OPSI ─────────────────────────────────────────────────────────────────────

$schemaOnly = isset($opts['schema-only']);
$dataOnly   = isset($opts['data-only']);
$noDrop     = isset($opts['no-drop']);
$batchSize  = max(1, (int)($opts['batch'] ?? 200));

$onlyTables   = !empty($opts['tables'])  ? array_map('trim', explode(',', $opts['tables']))  : [];
$excludeTables = !empty($opts['exclude']) ? array_map('trim', explode(',', $opts['exclude'])) : [];

// ─── AMBIL DAFTAR TABEL ───────────────────────────────────────────────────────

$allTables = [];
$stmt = $pdo->query("SHOW FULL TABLES FROM `{$dbName}` WHERE Table_type = 'BASE TABLE'");
foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $r) {
    $allTables[] = $r[0];
}

// Filter tabel
$tables = array_filter($allTables, function (string $t) use ($onlyTables, $excludeTables): bool {
    if (!empty($onlyTables) && !in_array($t, $onlyTables, true)) return false;
    if (in_array($t, $excludeTables, true)) return false;
    return true;
});
$tables = array_values($tables);

// ─── HEADER FILE SQL ──────────────────────────────────────────────────────────

$mode = $schemaOnly ? 'Schema (Migrasi)' : ($dataOnly ? 'Data (Seeder)' : 'Migrasi + Seeder');

w("-- ===========================================================================\n");
w("-- SQL DUMP — {$mode}\n");
w("-- Database  : {$dbName}\n");
w("-- Host      : {$host}:{$port}\n");
w("-- Dibuat    : " . date('Y-m-d H:i:s') . "\n");
w("-- Tabel     : " . count($tables) . " tabel\n");
w("-- Generator : generate_sql_dump.php\n");
w("-- ===========================================================================\n\n");

w("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;\n");
w("SET TIME_ZONE = '+00:00';\n");
w("SET FOREIGN_KEY_CHECKS = 0;\n");
w("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

// ─── BAGIAN 1 : SCHEMA / MIGRASI ─────────────────────────────────────────────

if (!$dataOnly) {
    banner('BAGIAN 1 — SCHEMA / MIGRASI (DDL)');
    w("-- Jalankan bagian ini untuk membuat ulang struktur tabel.\n\n");

    foreach ($tables as $table) {
        w("-- Tabel: `{$table}`\n");

        if (!$noDrop) {
            w("DROP TABLE IF EXISTS `{$table}`;\n");
        }

        $row    = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_NUM);
        $create = rtrim($row[1] ?? $row[0] ?? '', ';') . ";\n\n";

        // Pastikan ENGINE dan CHARSET selalu ada
        if (!preg_match('/ENGINE=/i', $create)) {
            $create = rtrim($create, ";\n") . " ENGINE=InnoDB;\n\n";
        }
        if (!preg_match('/CHARSET=/i', $create)) {
            $create = preg_replace('/ENGINE=\w+/', '$0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci', $create);
        }

        w($create);
    }

    w("-- ===========================================================================\n");
    w("-- Akhir bagian SCHEMA\n");
    w("-- ===========================================================================\n\n");
}

// ─── BAGIAN 2 : DATA / SEEDER ─────────────────────────────────────────────────

if (!$schemaOnly) {
    banner('BAGIAN 2 — DATA / SEEDER (DML)');
    w("-- Jalankan bagian ini untuk mengisi data (setelah schema sudah ada).\n\n");

    foreach ($tables as $table) {
        // Hitung baris
        $count = (int)$pdo->query("SELECT COUNT(*) FROM `{$table}`")->fetchColumn();

        w("-- ---- Tabel: `{$table}` ({$count} baris) ----\n");

        if ($count === 0) {
            w("-- (kosong)\n\n");
            continue;
        }

        // Ambil nama kolom
        $cols    = $pdo->query("SHOW COLUMNS FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        $colNames = array_column($cols, 'Field');
        $colList  = implode(', ', array_map(fn($c) => "`{$c}`", $colNames));

        // Nonaktifkan auto-increment sementara untuk kolom integer PK
        $hasPkAutoInc = false;
        foreach ($cols as $c) {
            if ($c['Key'] === 'PRI' && str_contains(strtolower($c['Extra']), 'auto_increment')) {
                $hasPkAutoInc = true;
                break;
            }
        }
        if ($hasPkAutoInc) {
            w("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n");
        }

        // Streaming data dalam batch
        $stmt   = $pdo->query("SELECT * FROM `{$table}`");
        $batch  = [];
        $total  = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vals = [];
            foreach ($colNames as $col) {
                $vals[] = escapeValue($row[$col], $pdo);
            }
            $batch[] = '  (' . implode(', ', $vals) . ')';
            $total++;

            if (count($batch) >= $batchSize) {
                w("INSERT INTO `{$table}` ({$colList}) VALUES\n");
                w(implode(",\n", $batch) . ";\n");
                $batch = [];
            }
        }

        if (!empty($batch)) {
            w("INSERT INTO `{$table}` ({$colList}) VALUES\n");
            w(implode(",\n", $batch) . ";\n");
        }

        w("\n");
    }

    w("-- ===========================================================================\n");
    w("-- Akhir bagian DATA\n");
    w("-- ===========================================================================\n\n");
}

// ─── FOOTER ───────────────────────────────────────────────────────────────────

w("SET FOREIGN_KEY_CHECKS = 1;\n");
w("-- Selesai — " . date('Y-m-d H:i:s') . "\n");

fclose($fh);

// ─── RINGKASAN OUTPUT ─────────────────────────────────────────────────────────

$size = number_format(filesize($outputFile) / 1024, 2);
echo "✅ Berhasil!\n";
echo "   File  : {$outputFile}\n";
echo "   Ukuran: {$size} KB\n";
echo "   Tabel : " . count($tables) . " tabel\n";
echo "   Mode  : {$mode}\n";
