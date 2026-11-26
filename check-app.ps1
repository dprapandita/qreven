# Cek Laravel bisa konek DB dan tidak ada migrasi pending.
try {
    $helper = Get-Item -LiteralPath (Join-Path $PSScriptRoot 'common.ps1') -ErrorAction Stop
    . $helper.FullName
} catch {
    Write-Host "[GAGAL] Inisialisasi gagal: $($_.Exception.Message)"
    exit 2
}

Title 'Laravel -> database'
if (-not (Test-Running $AppContainer)) { Bad "$AppContainer tidak running"; exit 1 }

$out = & $ContainerCli exec $AppContainer php artisan migrate:status --no-ansi 2>&1 | ForEach-Object { "$_" }
if ($LASTEXITCODE -eq 0) {
    Ok 'app konek DB lewat artisan'
    $pending = @($out | Select-String '\bPending\s*$').Count
    if ($pending -gt 0) { Bad "$pending migrasi pending" } else { Ok 'semua migrasi sudah jalan' }
} else {
    Bad 'artisan gagal:'
    $out | Select-Object -Last 5 | ForEach-Object { Write-Host "        $_" }
}
exit $script:Fail
