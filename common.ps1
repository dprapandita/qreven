# Helper bersama, di-dot-source oleh skrip lain. Jangan dijalankan langsung.

# Pilih 'podman' atau 'docker' untuk ketiga skrip.
$ContainerEngine = 'podman'

$AppUrl       = if ($env:APP_URL)       { $env:APP_URL }       else { 'http://127.0.0.1:8080' }
$script:Fail  = 0

function Ok($m)    { Write-Host '  [OK]   ' -ForegroundColor Green  -NoNewline; Write-Host $m }
function Bad($m)   { Write-Host '  [FAIL] ' -ForegroundColor Red    -NoNewline; Write-Host $m; $script:Fail = 1 }
function Warn($m)  { Write-Host '  [WARN] ' -ForegroundColor Yellow -NoNewline; Write-Host $m }
function Title($m) { Write-Host "== $m ==" -ForegroundColor Cyan }

# Status container ("running", "exited", ...) atau $null kalau tidak ada
function Get-State($name) {
    $s = & $ContainerCli container inspect -f '{{.State.Status}}' $name 2>$null
    if ($LASTEXITCODE -ne 0) { return $null }
    return "$s".Trim()
}

function Test-Running($name) { (Get-State $name) -eq 'running' }

# Jalankan SQL lewat client di container DB. SQL dikirim via stdin supaya aman dari masalah quoting PowerShell.
function Invoke-DbQuery($sql) {
    $out = $sql | & $ContainerCli exec -i $DbContainer sh -c 'mariadb -N -B -u$MARIADB_USER -p$MARIADB_PASSWORD $MARIADB_DATABASE' 2>&1
    if ($LASTEXITCODE -ne 0) {
        throw "Query MariaDB gagal: $($out -join ' ')"
    }
    return (($out | ForEach-Object { "$_" }) -join "`n").Trim()
}

if ($ContainerEngine -notin @('podman', 'docker')) {
    throw "Pilihan ContainerEngine '$ContainerEngine' tidak valid. Pilih podman atau docker."
}

$cli = Get-Command $ContainerEngine -CommandType Application -ErrorAction SilentlyContinue | Select-Object -First 1
if (-not $cli) {
    throw "$ContainerEngine tidak ditemukan di PATH."
}
$ContainerCli = $cli.Path
& $ContainerCli info > $null
if ($LASTEXITCODE -ne 0) {
    throw "Gagal terhubung ke $ContainerEngine. Periksa dengan '$ContainerEngine info'."
}

function Get-ServiceContainer($service) {
    $ids = @(& $ContainerCli compose ps -a -q $service)
    if ($LASTEXITCODE -ne 0) { throw "Compose gagal mencari service '$service'." }
    $ids = @($ids | Where-Object { $_.Trim() })
    if ($ids.Count -eq 0) { throw "Container untuk service '$service' belum ditemukan." }
    if ($ids.Count -gt 1) { throw "Service '$service' punya beberapa container. Tentukan ID container secara eksplisit." }
    return $ids[0].Trim()
}

$AppService = if ($env:APP_SERVICE) { $env:APP_SERVICE } else { 'app' }
$DbService = if ($env:DB_SERVICE) { $env:DB_SERVICE } else { 'db' }
$AppContainer = if ($env:APP_CONTAINER) { $env:APP_CONTAINER } else { Get-ServiceContainer $AppService }
$DbContainer = if ($env:DB_CONTAINER) { $env:DB_CONTAINER } else { Get-ServiceContainer $DbService }
