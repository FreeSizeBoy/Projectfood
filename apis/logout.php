<?php
// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Include a JavaScript snippet to clear local storage
echo '<script>
    localStorage.clear();
    window.location.href = "' . ROOT_URL . '";
</script>';
?>
