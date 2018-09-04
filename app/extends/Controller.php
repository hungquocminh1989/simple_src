<?php

class Controller {
	
	public static function action_backupsite(){
		//Create folder backup
		$time = date("YmdHis");
		$folderBackup = SYSTEM_TMP_DIR."/site_backup_$time";
		$folderImagesUpload = "$folderBackup/public/upload";
		if(Support_File::CreateFolder($folderImagesUpload) == TRUE){
			//Copy data file uploaded
			Support_File::CopyFolder(SYSTEM_PUBLIC_UPLOAD, $folderImagesUpload);
			
			//Backup database
			$file_backup = Flight::postgresSqlBackup();
			if(Support_File::FileExists($file_backup) == TRUE){
				Support_File::CopyFile($file_backup, $folderBackup . "/db_$time.backup");
			}
		}
		Flight::json(array('status' => 'OK'));
		return FALSE;#Stop Route
	}
	
	public static function action_obfuscator()
	{
		$arr_return = array();
		$arr_return = Flight::request()->data->getData();
		Flight::renderSmarty('obfuscator.html',$arr_return);
		return FALSE;//Stop Route
	}
}
