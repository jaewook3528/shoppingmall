<?php

// 결과값 Return
echo "
    <script>
    
    var win = window.opener;
    var is_opener = true;
    
    if( ! win ) { // 새창이 아닐 경우
        win = parent;
        is_opener = false;
    }
    
    win.result_submit('{$_POST["result_code"]}','{$_POST["error_msg"]}','{$_POST["enc_data"]}');
    
    if( is_opener ) {
        window.close();
    }
    
    </script>
";
