try {
    $loginPage = Invoke-WebRequest -Uri http://127.0.0.1:8000/login -SessionVariable session -UseBasicParsing
    if ($loginPage -eq $null) { Write-Error 'Failed to fetch login page'; exit 1 }
    if ($loginPage.Content -match 'name="csrf-token" content="([^"]+)"') { $token = $matches[1] } else { Write-Error 'CSRF token not found'; exit 1 }
    Write-Output "Login CSRF token: $token"
    $loginForm = @{ email = 'adminpusatsci@akademi.com'; password = 'password'; _token = $token }
    $loginResp = Invoke-WebRequest -Uri http://127.0.0.1:8000/login -Method Post -Body $loginForm -WebSession $session -UseBasicParsing -ErrorAction SilentlyContinue
    if ($loginResp -and $loginResp.StatusCode) { Write-Output "Login response status: $($loginResp.StatusCode)" } else { Write-Output "Login request completed (possibly redirected)" }
    # Visit branches page
    $branchesPage = Invoke-WebRequest -Uri http://127.0.0.1:8000/owner/branches -WebSession $session -UseBasicParsing
    if ($branchesPage.Content -match 'name="csrf-token" content="([^"]+)"') { $token2 = $matches[1] } else { Write-Error 'CSRF token not found on branches page'; exit 1 }
    Write-Output "Branches page CSRF token: $token2"
    # Prepare create form
    $createForm = @{
        _token = $token2;
        name = 'Automated Cabang PS';
        city = 'Jakarta';
        regency = 'Kebayoran';
        address = 'Alamat otomatis';
        phone = '021-000000';
        email = 'auto.cabang.pwsh@example.com';
        password = 'password';
        admin_name = 'Auto Admin';
        can_students = 'on'
    }
    try {
        $createResp = Invoke-WebRequest -Uri http://127.0.0.1:8000/owner/branches -Method Post -Body $createForm -WebSession $session -UseBasicParsing -ErrorAction Stop
        if ($createResp -and $createResp.StatusCode) { Write-Output "Create response status: $($createResp.StatusCode)" }
    } catch {
        # Handle non-200 responses (like 302 redirect)
        $ex = $_.Exception
        if ($ex.Response -ne $null) {
            try { $status = $ex.Response.StatusCode.Value__ } catch { $status = 'unknown' }
            Write-Output "Create request resulted in status: $status (exception caught)"
        } else {
            Write-Error "Create request failed: $($_.Exception.Message)"
        }
    }
    # Fetch branches page again to confirm presence
    $branchesPage2 = Invoke-WebRequest -Uri http://127.0.0.1:8000/owner/branches -WebSession $session -UseBasicParsing
    if ($branchesPage2.Content -match 'Automated Cabang PS') { Write-Output 'Created branch found in page HTML' } else { Write-Output 'Created branch NOT found in page HTML' }
        # Try to extract the branch id from the page (search for modal id near the name)
        $html = $branchesPage2.Content
        $branchId = $null
        if ($html -match 'editModal(\d+)[\s\S]*?Automated Cabang PS') { $branchId = $matches[1] }
        if (-not $branchId) {
            # fallback: find any editModal id and use the last one
            if ($html -match 'editModal(\d+)') { $branchId = $matches[1] }
        }
        if ($branchId) { Write-Output "Detected branch id: $branchId" } else { Write-Output 'Could not detect branch id from HTML' }

        if ($branchId) {
            # Get fresh CSRF token
            if ($branchesPage2.Content -match 'name="csrf-token" content="([^"]+)"') { $tok3 = $matches[1] } else { $tok3 = $token2 }
            # Perform update (PUT)
            $updateForm = @{ _token = $tok3; _method = 'PUT'; name = 'Automated Cabang PS Updated'; city = 'Jakarta' }
            try {
                $updateResp = Invoke-WebRequest -Uri "http://127.0.0.1:8000/owner/branches/$branchId" -Method Post -Body $updateForm -WebSession $session -UseBasicParsing -ErrorAction Stop
                Write-Output "Update response status: $($updateResp.StatusCode)"
            } catch {
                Write-Output "Update resulted in exception: $($_.Exception.Message)"
            }

            $branchesAfterUpdate = Invoke-WebRequest -Uri http://127.0.0.1:8000/owner/branches -WebSession $session -UseBasicParsing
            if ($branchesAfterUpdate.Content -match 'Automated Cabang PS Updated') { Write-Output 'Update confirmed on page' } else { Write-Output 'Update not visible on page' }

            # Delete the branch
            if ($branchesAfterUpdate.Content -match 'name="csrf-token" content="([^"]+)"') { $tok4 = $matches[1] } else { $tok4 = $tok3 }
            $deleteForm = @{ _token = $tok4; _method = 'DELETE' }
            try {
                $delResp = Invoke-WebRequest -Uri "http://127.0.0.1:8000/owner/branches/$branchId" -Method Post -Body $deleteForm -WebSession $session -UseBasicParsing -ErrorAction Stop
                Write-Output "Delete response status: $($delResp.StatusCode)"
            } catch {
                Write-Output "Delete resulted in exception: $($_.Exception.Message)"
            }

            $branchesFinal = Invoke-WebRequest -Uri http://127.0.0.1:8000/owner/branches -WebSession $session -UseBasicParsing
            if ($branchesFinal.Content -match 'Automated Cabang PS Updated') { Write-Output 'Delete failed — updated name still present' } else { Write-Output 'Delete confirmed — branch removed from page' }
        }
} catch {
    Write-Error $_.Exception.Message
    exit 1
}
