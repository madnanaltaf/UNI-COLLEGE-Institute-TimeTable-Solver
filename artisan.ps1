param(
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$Args
)
& "C:\wamp64\bin\php\php8.3.28\php.exe" "$PSScriptRoot\artisan" @Args
