<?php
include 'login.php';
header("Content-Type: text/html; charset=utf-8");

/* This error handler will turn all notices, warnings, and errors into fatal
 * errors, unless they have been suppressed with the @-operator. */
function error_handler($errno, $errstr, $errfile, $errline, $errcontext)
{
    /* The @-operator (used with chdir() below) temporarely makes
     * error_reporting() return zero, and we don't want to die in that case.
     * We do note the error in the output, though. */
    if (error_reporting() == 0) {
        $_SESSION['output'] .= $errstr . "\n";
    } else {
        die('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
           "http://www.w3.org/TR/html4/strict.dtd">
           <html>
           <head>
              <title>aCC Server Shell</title>
              <meta http-equiv="Content-Script-Type" content="text/javascript">
              <meta http-equiv="Content-Style-Type" content="text/css">
              <link href="css/custom.css" rel="stylesheet">
              <link rel="shortcut icon" href="favicon.ico">
          </head>
          <body>
              <h1>Fatal Error!</h1>
              <p><b>' . $errstr . '</b></p>
              <p>in <b>' . $errfile . '</b>, line <b>' . $errline . '</b>.</p>

              <hr>

              <p>Please consult the <a href="README">README</a>, <a
                  href="INSTALL">INSTALL</a>, and <a href="SECURITY">SECURITY</a> files for
                  instruction on how to use PHP Shell.</p>

                  <hr>

                  <address>
                      Copyright &copy; 2000&ndash;2012, the Phpshell-team. Get the latest
                      version at <a
                      href="http://phpshell.sourceforge.net/">http://phpshell.sourceforge.net/</a>.
                  </address>

              </body>
              </html>');
}
}

/* Installing our error handler makes PHP die on even the slightest problem.
 * This is what we want in a security critical application like this. */
set_error_handler('error_handler');


function logout()
{
    /* Empty the session data, except for the 'authenticated' entry which the
     * rest of the code needs to be able to check. */
    $_SESSION = array('authenticated' => false);

    /* Unset the client's cookie, if it has one. */
    //    if (isset($_COOKIE[session_name()]))
    //        setcookie(session_name(), '', time()-42000, '/');

    /* Destroy the session data on the server.  This prevents the simple
     * replay attack where one uses the back button to re-authenticate using
     * the old POST data since the server wont know the session then. */
    //    session_destroy();
}

/* Clear screen */
function clearscreen() 
{
    $_SESSION['output'] = '';
}

function stripslashes_deep($value)
{
    if (is_array($value)) {
        return array_map('stripslashes_deep', $value);
    } else {
        return stripslashes($value);
    }
}

if (get_magic_quotes_gpc()) {
    $_POST = stripslashes_deep($_POST);
}

/* Initialize some variables we need again and again. */
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$nounce   = isset($_POST['nounce'])   ? $_POST['nounce']   : '';

$command  = isset($_POST['command'])  ? $_POST['command']  : '';
$rows     = isset($_POST['rows'])     ? $_POST['rows']     : 24;
$columns  = isset($_POST['columns'])  ? $_POST['columns']  : 80;

if (!preg_match('/^[[:digit:]]+$/', $rows)) { 
    $rows=24 ; 
} 
if (!preg_match('/^[[:digit:]]+$/', $columns)) {
    $columns=80 ;
}

if (empty($ini['settings'])) {
    $ini['settings'] = array();
}

/* Default settings --- these settings should always be set to something. */
$default_settings = array('home-directory' => '.',
  'PS1'            => '$ ');

/* Merge settings. */
$ini['settings'] = array_merge($default_settings, $ini['settings']);

/* Delete the session data if the user requested a logout. This leaves
 * the session cookie at the user, but this is not important since we
 * authenticates on $_SESSION['authenticated']. */
if (isset($_POST['logout'])) {
    logout();
}

/* Clear screen if submitted */
if (isset($_POST['clear'])) {
    clearscreen();
}

if (!isset($_SESSION['authenticated'])) {
    $_SESSION['authenticated'] = false;
}

if ($_SESSION['authenticated']) {  
    /* Initialize the session variables. */
    if (empty($_SESSION['cwd'])) {
        $_SESSION['cwd'] = realpath($ini['settings']['home-directory']);
        $_SESSION['history'] = array();
        $_SESSION['output'] = '';
    }
    /* Clicked on one of the subdirectory links - ignore the command */
    if (isset($_POST['levelup'])) {
        $levelup = $_POST['levelup'] ;
        while ($levelup > 0) {
            $command = '' ; /* ignore the command */
            $_SESSION['cwd'] = dirname($_SESSION['cwd']);
            $levelup -- ;
        }
    }
    /* Selected a new subdirectory as working directory - ignore the command */
    if (isset($_POST['changedirectory'])) {
        $changedir= $_POST['changedirectory'];
        if (strlen($changedir) > 0) {
            if (@chdir($_SESSION['cwd'] . '/' . $changedir)) {
                $command = '' ; /* ignore the command */
                $_SESSION['cwd'] = realpath($_SESSION['cwd'] . '/' . $changedir);
            }
        }
    }
    if (isset($_FILES['uploadfile']['tmp_name'])) {
        if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
            if (!move_uploaded_file($_FILES['uploadfile']['tmp_name'], $_SESSION['cwd'] . '/' . $_FILES['uploadfile']['name'])) { 
                echo "CANNOT MOVE {$_FILES['uploadfile']['name']}" ;
            }
        }
    }

    /* Save content from 'editor' */
    if (isset($_POST["filetoedit"]) && ($_POST["filetoedit"] != "")) {
        $filetoedit_handle = fopen($_POST["filetoedit"], "w");
        fputs($filetoedit_handle, str_replace("%0D%0D%0A", "%0D%0A", $_POST["filecontent"]));
        fclose($filetoedit_handle);
    }

    if (!empty($command)) {
        /* Save the command for late use in the JavaScript. If the command is
         * already in the history, then the old entry is removed before the
         * new entry is put into the list at the front. */
        if (($i = array_search($command, $_SESSION['history'])) !== false) {
            unset($_SESSION['history'][$i]);
        }

        array_unshift($_SESSION['history'], $command);

        /* Now append the command to the output. */
        $_SESSION['output'] .= htmlspecialchars($ini['settings']['PS1'] . $command, ENT_COMPAT, 'UTF-8') . "\n";

        /* Initialize the current working directory. */
        if (trim($command) == 'cd') {
            $_SESSION['cwd'] = realpath($ini['settings']['home-directory']);
        } elseif (preg_match('/^[[:blank:]]*cd[[:blank:]]+([^;]+)$/', $command, $regs)) {
            /* The current command is a 'cd' command which we have to handle
             * as an internal shell command. */

            /* if the directory starts and ends with quotes ("), remove them -
            allows command like 'cd "abc def"' */
            if ((substr($regs[1], 0, 1) == '"') && (substr($regs[1], -1) =='"') ) {
                $regs[1] = substr($regs[1], 1);
                $regs[1] = substr($regs[1], 0, -1);
            }

            if ($regs[1]{0} == '/') {
                /* Absolute path, we use it unchanged. */
                $new_dir = $regs[1];
            } else {
                /* Relative path, we append it to the current working directory. */
                $new_dir = $_SESSION['cwd'] . '/' . $regs[1];
            }

            /* Transform '/./' into '/' */
            while (strpos($new_dir, '/./') !== false) {
                $new_dir = str_replace('/./', '/', $new_dir);
            }

            /* Transform '//' into '/' */
            while (strpos($new_dir, '//') !== false) {
                $new_dir = str_replace('//', '/', $new_dir);
            }

            /* Transform 'x/..' into '' */
            while (preg_match('|/\.\.(?!\.)|', $new_dir)) {
                $new_dir = preg_replace('|/?[^/]+/\.\.(?!\.)|', '', $new_dir);
            }

            if ($new_dir == '') {
                $new_dir = '/';
            }

            /* Try to change directory. */
            if (@chdir($new_dir)) {
                $_SESSION['cwd'] = $new_dir;
            } else {
                $_SESSION['output'] .= "cd: could not change to: $new_dir\n";
            }

            /* history command (without parameter) - output the command history */
        } elseif (trim($command) == 'history') {
            $i = 1 ; 
            foreach ($_SESSION['history'] as $histline) {
                $_SESSION['output'] .= htmlspecialchars(sprintf("%5d  %s\n", $i, $histline), ENT_COMPAT, 'UTF-8');
                $i++;
            }
            /* history command (with parameter "-c") - clear the command history */
        } elseif (preg_match('/^[[:blank:]]*history[[:blank:]]*-c[[:blank:]]*$/', $command)) {
            $_SESSION['history'] = array() ;
            /* "clear" command - clear the screen */
        } elseif (trim($command) == 'clear') {
            clearscreen();
        } elseif (trim($command) == 'editor') {
            /* You called 'editor' without a filename so you get an short help
             * on how to use the internal 'editor' command */
            $_SESSION['output'] .= " Syntax: editor filename\n (you forgot the filename)\n";

        } elseif (preg_match('/^[[:blank:]]*editor[[:blank:]]+([^;]+)$/', $command, $regs)) {
            /* This is a tiny editor which you can start with 'editor filename'. */
            $filetoedit = $regs[1];
            if ($regs[1]{0} != '/') {
                /* relative path, add it to the current working directory. */
                $filetoedit = $_SESSION['cwd'].'/'.$regs[1];
            } ;
            if (is_file(realpath($filetoedit)) || ! file_exists($filetoedit)) {
                if (file_exists(realpath($filetoedit))) {
                    $filetoedit = realpath($filetoedit);
                }
            } else {
                $_SESSION['output'] .= " Syntax: editor filename\n (just regular or not existing files)\n";
            }

        } elseif ((trim($command) == 'exit') || (trim($command) == 'logout')) {
            logout();
        } else {

            /* The command is not an internal command, so we execute it after
             * changing the directory and save the output. */
            if (@chdir($_SESSION['cwd'])) {

                /* Alias expansion. */
                $length = strcspn($command, " \t");
                $token = substr($command, 0, $length);
                if (isset($ini['aliases'][$token])) {
                    $command = $ini['aliases'][$token] . substr($command, $length);
                }
                $io = array();
                $p = proc_open(
                    $command,
                    array(1 => array('pipe', 'w'),
                      2 => array('pipe', 'w')),
                    $io
                    );

                /* Read output sent to stdout. */
                while (!feof($io[1])) {
                    $line=fgets($io[1]);
                    if (function_exists('mb_convert_encoding')) {
                        /* (hopefully) fixes a strange "htmlspecialchars(): Invalid multibyte sequence in argument" error */
                        $line = mb_convert_encoding($line, 'UTF-8', 'UTF-8');
                    }
                    $_SESSION['output'] .= htmlspecialchars($line, ENT_COMPAT, 'UTF-8');
                }
                /* Read output sent to stderr. */
                while (!feof($io[2])) {
                    $line=fgets($io[2]);
                    if (function_exists('mb_convert_encoding')) {
                        /* (hopefully) fixes a strange "htmlspecialchars(): Invalid multibyte sequence in argument" error */
                        $line = mb_convert_encoding($line, 'UTF-8', 'UTF-8');
                    }
                    $_SESSION['output'] .= htmlspecialchars($line, ENT_COMPAT, 'UTF-8');
                }

                fclose($io[1]);
                fclose($io[2]);
                proc_close($p);
            } else { /* It was not possible to change to working directory. Do not execute the command */
                $_SESSION['output'] .= "PHP Shell could not change to working directory. Your command was not executed.\n";
            }
        }
    }

    /* Build the command history for use in the JavaScript */
    if (empty($_SESSION['history'])) {
        $js_command_hist = '""';
    } else {
        $escaped = array_map('addslashes', $_SESSION['history']);
        $js_command_hist = '"", "' . implode('", "', $escaped) . '"';
    }
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title>aCC Server Shell</title>
  <meta http-equiv="Content-Script-Type" content="text/javascript">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta name="generator" content="phpshell">
  <link href="css/custom.css" rel="stylesheet">
  <link rel="shortcut icon" href="favicon.ico">

  <script type="text/javascript">
      <?php if ($_SESSION['authenticated']) { ?>

        var current_line = 0;
        var command_hist = new Array(<?php echo $js_command_hist ?>);
        var last = 0;

        function key(e) {
            if (!e) var e = window.event;

            if (e.keyCode == 38 && current_line < command_hist.length-1) {
                command_hist[current_line] = document.shell.command.value;
                current_line++;
                document.shell.command.value = command_hist[current_line];
            }

            if (e.keyCode == 40 && current_line > 0) {
                command_hist[current_line] = document.shell.command.value;
                current_line--;
                document.shell.command.value = command_hist[current_line];
            }

        }

        function init() {
            document.shell.setAttribute("autocomplete", "off");
            document.shell.output.scrollTop = document.shell.output.scrollHeight;
            document.shell.command.focus()
        }

        <?php } elseif ($_SESSION['authenticated']) { ?>

            function init() {
              document.shell.filecontent.focus();
          }

          <?php } else { ?>

            function init() {
                document.shell.username.focus();
            }

            <?php } ?>
        </script>
    </head>

    <body onload="init()">

        <h1>aCC Server Shell</h1>

        <form name="shell" enctype="multipart/form-data" action="<?php print($_SERVER['PHP_SELF']) ?>" method="post">
            <?php
            if (!$_SESSION['authenticated']) {
    /* Generate a new nounce every time we present the login page.  This binds
     * each login to a unique hit on the server and prevents the simple replay
     * attack where one uses the back button in the browser to replay the POST
     * data from a login. */
    $_SESSION['nounce'] = mt_rand();

    ?>

    <fieldset>
        <legend>Authentication</legend>
        <?php
        if (!empty($username)) {
            echo "  <p class=\"error\">Login failed, please try again:</p>\n";
        } else {
            echo "  <p>Please login:</p>\n";
        }
        ?>

        <label for="username">Username:</label>
        <input name="username" id="username" type="text" value="<?php echo $username ?>"><br>
        <label for="password">Password:</label>
        <input name="password" id="password" type="password">
        <p><input type="submit" value="Login"></p>
        <input name="nounce" type="hidden" value="<?php echo $_SESSION['nounce']; ?>">

    </fieldset>

    <?php } else { /* Authenticated. */ ?>
    <fieldset>
      <legend><?php echo "Shell running on: " . $_SERVER['SERVER_NAME']; ?></legend>
      <p>Current Working Directory:
        <span class="pwd"><?php
            $parts = explode('/', $_SESSION['cwd']);
            for ($i=1; $i<count($parts); $i=$i+1) {
                echo '/' . htmlspecialchars($parts[$i], ENT_COMPAT, 'UTF-8');

                echo '</span>';
            }
            ?>
        </span>
    </p>
    <div id="terminal">
        <textarea name="output" readonly="readonly" cols="<?php echo $columns ?>" rows="<?php echo $rows ?>">
            <?php
            $lines = substr_count($_SESSION['output'], "\n");
            $padding = str_repeat("\n", max(0, $rows+1 - $lines));
            echo rtrim($padding . $_SESSION['output']);
            ?>
        </textarea>
        <p id="prompt">
            <span id="ps1"><?php echo htmlspecialchars($ini['settings']['PS1'], ENT_COMPAT, 'UTF-8'); ?></span>
            <input name="command" type="text" onkeyup="key(event)"
            size="<?php echo $columns-strlen($ini['settings']['PS1']); ?>" tabindex="1">
        </p>
    </div>
    <p>
      <span style="float: right">Size: <input type="text" name="rows" size="2"
          maxlength="3" value="<?php echo $rows ?>"> &times; <input type="text"
          name="columns" size="2" maxlength="3" value="<?php echo $columns
          ?>"></span><br>
          <input type="submit" value="Execute command">
          <input type="submit" name="clear" value="Clear screen">
          <input type="submit" name="logout" value="Logout">
      </p>
  </fieldset>
  <fieldset>
      <legend>File upload</legend>
      Select file for upload:
      <input type="file" name="uploadfile" size="40"><br>
      <input type="submit" value="Upload file">
  </fieldset>

  <?php } ?>

</form>
</body>
</html>
