<?php
// webscript.php
// Simple maintenance-mode toggler that renames index.php <-> custom.php
// WARNING: This script contains a hardcoded password for testing. Change/remove
// the hardcoded secret before deploying to production.

// ------------------- CONFIG -------------------
$SECRET = 'test123!';    // <--- HARD-CODED password (for testing only)
$INDEX  = 'dashboard.blade.php';
$CUSTOM = 'custom.blade.php';
$MAINT_HTML = <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Site Under Maintenance</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:#f7f7f7;color:#333}
    .card{background:#fff;padding:28px;border-radius:8px;box-shadow:0 8px 20px rgba(0,0,0,.08);max-width:560px;text-align:center}
    h1{margin:0 0 8px;font-size:22px}
    p{margin:0 0 12px;color:#666}
  </style>
</head>
<body>
  <div class="card">
    <h1>Website is under maintenance</h1>
    <p>We are performing scheduled maintenance. Please check back soon.</p>
    <small>Contact the site administrator if this continues.</small>
  </div>
</body>
</html>
HTML;
// ------------------- END CONFIG -------------------

// Helper: show messages
function flash($msg, $type='info'){
    $color = $type === 'error' ? '#b23' : ($type === 'success' ? '#197b30' : '#0b5');
    echo "<div style='border:1px solid rgba(0,0,0,.06);padding:10px;border-radius:6px;margin:10px 0;color:$color'>{$msg}</div>";
}

// Very small helper to sanitize action input
$action = isset($_POST['action']) ? trim($_POST['action']) : null;
$provided_key = isset($_POST['key']) ? $_POST['key'] : '';

// Basic UI
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Maintenance Mode Controller</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f3f4f6;margin:0;padding:30px}
    .wrap{max-width:760px;margin:0 auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,.06)}
    h2{margin-top:0}
    form{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
    input[type="text"], input[type="password"]{padding:8px;border-radius:6px;border:1px solid #ddd}
    button{padding:10px 14px;border-radius:6px;border:0;cursor:pointer}
    .btn-activate{background:#e87b2e;color:#fff}
    .btn-deactivate{background:#2d9cdb;color:#fff}
    .info{margin-top:12px;color:#555}
    pre{background:#f7f7f7;padding:10px;border-radius:6px;overflow:auto}
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Maintenance Mode Controller</h2>

    <?php
    // Show current status
    $index_exists  = file_exists($INDEX);
    $custom_exists = file_exists($CUSTOM);

    echo "<strong>Current files:</strong> ";
    echo $index_exists ? "<span style='color:green'>{$INDEX} (exists)</span>" : "<span style='color:#b23'>{$INDEX} (missing)</span>";
    echo " &nbsp; | &nbsp; ";
    echo $custom_exists ? "<span style='color:green'>{$CUSTOM} (exists)</span>" : "<span style='color:#b23'>{$CUSTOM} (missing)</span>";
    echo "<hr />";
    
    // Process POST if action provided
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action) {
        // Simple password check
        if ($provided_key !== $SECRET) {
            flash('Invalid password provided.', 'error');
        } else {
            // Activate maintenance
            if ($action === 'activate') {
                // Safety: ensure index exists to rename
                if (!file_exists($INDEX)) {
                    flash("Cannot activate: {$INDEX} does not exist.", 'error');
                } else {
                    // If custom already exists, we avoid overwriting it.
                    if (file_exists($CUSTOM)) {
                        // If both index and custom exist, probably already toggled or something else.
                        flash("{$CUSTOM} already exists. Aborting to avoid overwrite.", 'error');
                    } else {
                        // Attempt rename
                        if (!is_writable(dirname(__FILE__))) {
                            flash("Server does not allow writing/renaming in this directory. Check permissions.", 'error');
                        } else {
                            $renamed = @rename($INDEX, $CUSTOM);
                            if ($renamed) {
                                // Create new maintenance index
                                $written = @file_put_contents($INDEX, $MAINT_HTML, LOCK_EX);
                                if ($written === false) {
                                    // attempt to revert rename if write failed
                                    @rename($CUSTOM, $INDEX);
                                    flash("Failed to create maintenance file after renaming. Reverted. Check file permissions.", 'error');
                                } else {
                                    flash("Maintenance mode ACTIVATED. {$INDEX} replaced with maintenance page and original moved to {$CUSTOM}.", 'success');
                                }
                            } else {
                                flash("Failed to rename {$INDEX} → {$CUSTOM}. Check file permissions and that files are not locked.", 'error');
                            }
                        }
                    }
                }
            }
            // Deactivate maintenance
            elseif ($action === 'deactivate') {
                // Safety checks
                if (!file_exists($CUSTOM)) {
                    flash("Cannot deactivate: {$CUSTOM} not found. Maybe site is already live or custom file moved.", 'error');
                } else {
                    if (!file_exists($INDEX)) {
                        // OK: index missing, proceed to rename custom -> index
                        if (!is_writable(dirname(__FILE__))) {
                            flash("Server does not allow writing/renaming in this directory. Check permissions.", 'error');
                        } else {
                            $deleted = true;
                            // If there is an existing index (shouldn't be), delete it first
                            if (file_exists($INDEX)) {
                                $deleted = @unlink($INDEX);
                                if (!$deleted) {
                                    flash("Failed to delete existing {$INDEX}. Aborting.", 'error');
                                }
                            }
                            if ($deleted) {
                                $renamed_back = @rename($CUSTOM, $INDEX);
                                if ($renamed_back) {
                                    flash("Maintenance mode DEACTIVATED. {$CUSTOM} renamed back to {$INDEX}.", 'success');
                                } else {
                                    flash("Failed to rename {$CUSTOM} → {$INDEX}. Check permissions.", 'error');
                                }
                            }
                        }
                    } else {
                        // Index exists (likely maintenance file). We'll try to delete it and rename custom -> index
                        if (!is_writable(dirname(__FILE__))) {
                            flash("Server does not allow writing/renaming in this directory. Check permissions.", 'error');
                        } else {
                            $deleted = @unlink($INDEX);
                            if (!$deleted) {
                                flash("Failed to delete maintenance {$INDEX}. Cannot restore original. Check permissions.", 'error');
                            } else {
                                $renamed_back = @rename($CUSTOM, $INDEX);
                                if ($renamed_back) {
                                    flash("Maintenance mode DEACTIVATED. {$CUSTOM} renamed back to {$INDEX}.", 'success');
                                } else {
                                    flash("Deleted maintenance {$INDEX} but failed to rename {$CUSTOM} → {$INDEX}. You may need to manually rename.", 'error');
                                }
                            }
                        }
                    }
                }
            }
            else {
                flash('Unknown action requested.', 'error');
            }
        }
    }

    // After processing, show current status again (fresh)
    $index_exists  = file_exists($INDEX);
    $custom_exists = file_exists($CUSTOM);
    echo "<hr />";
    echo "<strong>Post-action files:</strong> ";
    echo $index_exists ? "<span style='color:green'>{$INDEX} (exists)</span>" : "<span style='color:#b23'>{$INDEX} (missing)</span>";
    echo " &nbsp; | &nbsp; ";
    echo $custom_exists ? "<span style='color:green'>{$CUSTOM} (exists)</span>" : "<span style='color:#b23'>{$CUSTOM} (missing)</span>";
    echo "<hr />";
    ?>

    <form method="post" onsubmit="return confirm('Are you sure? This will modify files in the webroot.')">
      <label for="key">Password:</label>
      <input id="key" name="key" type="password" placeholder="Enter test password" required />
      <input type="hidden" name="action" value="activate" />
      <button type="submit" class="btn-activate">Activate Maintenance</button>
    </form>

    <form method="post" style="margin-top:10px" onsubmit="return confirm('Deactivate maintenance and restore live site?')">
      <input type="hidden" name="key" value="" />
      <input type="hidden" name="action" value="deactivate" />
      <input type="password" name="key" placeholder="Enter test password" required />
      <button type="submit" class="btn-deactivate">Deactivate Maintenance</button>
    </form>
  </div>
</body>
</html>
