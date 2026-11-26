# Cek MariaDB: healthcheck, koneksi, jumlah tabel, jumlah baris.
try {
    $helper = Get-Item -LiteralPath (Join-Path $PSScriptRoot 'common.ps1') -ErrorAction Stop
    . $helper.FullName
} catch {
    Write-Host "[GAGAL] Inisialisasi gagal: $($_.Exception.Message)"
    exit 2
}

Title 'Database'
if (-not (Test-Running $DbContainer)) { Bad "$DbContainer tidak running"; exit 1 }

$json = & $ContainerCli container inspect $DbContainer
if ($LASTEXITCODE -ne 0) { Bad "Gagal membaca status $DbContainer"; exit 1 }
$container = @(ConvertFrom-Json -InputObject ($json -join "`n"))[0]
$health = $container.State.Health.Status
if (-not $health) { $health = $container.State.Healthcheck.Status }
if (-not $health) { $health = 'none' }
switch ($health) {
    'healthy'  { Ok   'healthcheck: healthy' }
    'starting' { Warn 'healthcheck: masih starting' }
    'none'     { Warn 'container tidak punya healthcheck' }
    default    { Bad  "healthcheck: $health" }
}

$r = Invoke-DbQuery 'SELECT 1;'
if ($r -ne '1') { Bad "gagal konek: $r"; exit 1 }
Ok 'bisa login sebagai user aplikasi'

$tables = Invoke-DbQuery 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE();'
if ($tables -match '^\d+$' -and [int]$tables -gt 0) { Ok "jumlah tabel: $tables" }
else { Bad 'database kosong, migrasi belum jalan' }

Write-Host '  Jumlah baris:'
$list = if ($env:TABLES) { $env:TABLES -split '\s+' } else {
    'users','events','ticket_types','ticket_orders','tickets','booths','booth_bookings','rundowns','migrations'
}
foreach ($t in $list) {
    if (-not $t) { continue }
    if ($t -notmatch '^[a-zA-Z0-9_]+$') {
        Bad "Nama tabel tidak valid: $t"
        continue
    }
    $exists = Invoke-DbQuery "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = '$t';"
    if ($exists -eq '1') {
        '    {0,-16} {1}' -f $t, (Invoke-DbQuery "SELECT COUNT(*) FROM ``$t``;")
    }
}
exit $script:Fail
