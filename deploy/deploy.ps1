[CmdletBinding()]
param(
    [Parameter(Mandatory=$true)]
    [ValidateSet("local","staging","production")]
    [string]$Environment
)

# 設定基本變數
$scriptPath = $PSScriptRoot
if (!$scriptPath) {
    $scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
}
$projectRoot = Split-Path -Parent $scriptPath
$configPath = Join-Path $projectRoot "config\deploy\environments.json"
$deployLogPath = Join-Path $projectRoot "deploy\logs"
$pluginPath = "C:\xampp\htdocs\wordpress\wp-content\plugins\vel-enterprise-system"

# 建立日誌目錄
if (-not (Test-Path $deployLogPath)) {
    New-Item -ItemType Directory -Path $deployLogPath -Force | Out-Null
}

# 設定日誌檔案
$logFile = Join-Path $deployLogPath "deploy_$(Get-Date -Format 'yyyyMMdd_HHmmss').log"
Start-Transcript -Path $logFile

try {
    Write-Host "開始部署到 $Environment 環境..." -ForegroundColor Green

    # 檢查配置文件
    if (-not (Test-Path $configPath)) {
        throw "找不到配置文件: $configPath"
    }

    # 讀取配置
    $config = Get-Content $configPath -Raw | ConvertFrom-Json
    $envConfig = $config.$Environment

    # 檢查必要目錄
    $requiredPaths = @($envConfig.path, $envConfig.backup_path)
    foreach ($path in $requiredPaths) {
        if (-not (Test-Path $path)) {
            New-Item -ItemType Directory -Path $path -Force
            Write-Host "已建立目錄: $path" -ForegroundColor Yellow
        }
    }

    # 複製檔案
    Write-Host "開始複製檔案..." -ForegroundColor Green
    $excludeDirs = @('.git', '.github', 'node_modules', 'vendor', 'deploy', 'tests')
    Get-ChildItem -Path $projectRoot -Exclude $excludeDirs | 
        Copy-Item -Destination $envConfig.path -Recurse -Force

    Write-Host "部署完成！" -ForegroundColor Green

} catch {
    Write-Host "部署失敗: $_" -ForegroundColor Red
    exit 1
} finally {
    Stop-Transcript
}
