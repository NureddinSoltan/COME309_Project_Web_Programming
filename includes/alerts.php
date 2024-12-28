<?php
function display_alert($message, $type = 'success') {
    echo "<div style='padding: 10px; margin: 10px 0; border-radius: 5px; background: " . 
         ($type === 'success' ? '#d4edda' : '#f8d7da') . "; color: " . 
         ($type === 'success' ? '#155724' : '#721c24') . ";'>
            $message
          </div>";
}
?>