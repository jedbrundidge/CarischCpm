<?php
        $this->session->unset_userdata('uid');
        $this->session->destroy();
        echo "Click <a href=\"/cpm/index.php/personnel/login\">here</a> to login.";
?>