<#
move-images-to-img.ps1
Crea carpeta "img" dentro de cada subcarpeta de "museos" y mueve las imágenes que estén en la raíz de cada subcarpeta.
Uso:
  .\move-images-to-img.ps1           # ejecuta real
  .\move-images-to-img.ps1 -WhatIf   # simula (no mueve archivos)
#>

[CmdletBinding(SupportsShouldProcess=$true)]
param(
    [string]$Base = "E:\U\Web\museos-web\museos",
    [string[]]$Exts = @('.jpg','.jpeg','.png','.gif','.webp','.svg','.bmp','.tif','.tiff')
)

# Comprueba que la ruta existe
if (-not (Test-Path $Base)) {
    Write-Error "No se encontró la ruta: $Base"
    return
}

Get-ChildItem -Path $Base -Directory | Where-Object { $_.Name -ne 'img' } | ForEach-Object {
    $dir = $_.FullName
    $imgDir = Join-Path $dir 'img'

    # Crear carpeta img si no existe
    if (-not (Test-Path $imgDir)) {
        if ($PSCmdlet.ShouldProcess($imgDir, "Crear carpeta")) {
            New-Item -Path $imgDir -ItemType Directory | Out-Null
            Write-Host "Creada: $imgDir"
        } else {
            Write-Host "Simulación: crear $imgDir"
        }
    }

    # Buscar archivos de imagen solo en la raíz de la carpeta (no recursivo)
    $files = Get-ChildItem -Path $dir -File -ErrorAction SilentlyContinue | Where-Object { $Exts -contains $_.Extension.ToLower() }

    if ($files.Count -eq 0) {
        Write-Host "No se encontraron imágenes en: $dir"
        return
    }

    foreach ($f in $files) {
        # preparar destino y manejar conflictos de nombre
        $dest = Join-Path $imgDir $f.Name
        if (Test-Path $dest) {
            $baseName = [System.IO.Path]::GetFileNameWithoutExtension($f.Name)
            $ext = $f.Extension
            $i = 1
            do {
                $newName = "{0}_{1}{2}" -f $baseName, $i, $ext
                $dest = Join-Path $imgDir $newName
                $i++
            } while (Test-Path $dest)
        }

        if ($PSCmdlet.ShouldProcess($f.FullName, "Mover a $dest")) {
            Move-Item -Path $f.FullName -Destination $dest
            Write-Host "Movido: $($f.Name) -> $dest"
        } else {
            Write-Host "Simulación: mover $($f.Name) -> $dest"
        }
    }
}
