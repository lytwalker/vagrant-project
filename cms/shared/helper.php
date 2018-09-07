<?php 	

class ZCM_helper {

    var $failCount = 0;
    var $successCount = 0;

    function createFile($_file_name, $_file_folder, $_file_type, $_input_content) {
        $file_name = $_file_name;
        $file_folder = $_file_folder;
        $file_type = $_file_type;
        $input_content = $_input_content;
        $phpFileName = $file_name.$file_type;
        $fopenMode = "w";
        $phpFileHandle = fopen($file_folder.$phpFileName, $fopenMode);
        
        if($phpFileHandle){
            echo "/** phpFileHandle: ".$phpFileHandle." */\n\n";
            
            $input_content = str_replace("<br/>","\n",$input_content);
            //$input_content = str_replace("}    {","},\n{",$input_content);
            //$input_content = "[".$input_content."]";
            
            $fwriteResult = fwrite($phpFileHandle, $input_content);
            if($fwriteResult){
                $this->successCount++;
                echo "/** Success: ".$fwriteResult." */\n\n";
            } else {
                $this->failCount++;
                echo "/** Error writing to: ".$phpFileName." */\n\n";
            }
            fclose($phpFileHandle);
        } else {
            echo "/** Error locating/opening: ".$phpFileName." */\n\n";
        }
	}
}
?>