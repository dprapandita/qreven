# Cek container app dan db ada dan running.
try {
    $helper = Get-Item -LiteralPath (Join-Path $PSScriptRoot 'common.ps1') -ErrorAction Stop
    . $helper.FullName
} catch {
    Write-Host "[GAGAL] Inisialisasi gagal: $($_.Exception.Message)"
    exit 2
}

Title 'Status container'
foreach ($c in @($DbContainer, $AppContainer)) {
    $state = Get-State $c
    if (-not $state)               { Bad "$c tidak ada (belum dibuat / nama salah)" }
    elseif ($state -ne 'running')  { Bad "$c status: $state" }
    else                           { Ok  "$c running" }
}
exit $script:Fail
