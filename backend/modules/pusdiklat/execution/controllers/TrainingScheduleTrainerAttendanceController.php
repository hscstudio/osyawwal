<?php

namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

use backend\models\TrainingClass;
use backend\models\TrainingSchedule;
use backend\models\TrainingScheduleTrainer;
use backend\models\TrainingScheduleTrainerAttendance;
use backend\models\TrainingScheduleTrainerAttendanceSearch;
use backend\models\ProgramSubject;
use backend\models\ObjectReference;
use backend\models\Reference;

use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;


class TrainingScheduleTrainerAttendanceController extends Controller
{
	public $layout = '@hscstudio/heart/views/layouts/column2';
	 
 	
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }





    public function actionUpdate($idSubjects, $training_schedule_id)
    {

		// Mbelah id schedule, kalau jamak
		$idSchedule = explode('_', $training_schedule_id);
		// dah

		// Ngambil schedule
		$modelTrainingSchedule = [];
		for ($i = 0; $i < count($idSchedule); $i++) {
    		$modelTrainingSchedule[$i] = TrainingSchedule::find()
    			->where(['id' => $idSchedule[$i]])
    			->one();
		}
		//dah

		// Cek dulu apakah class_id pada schedule itu sama, klo beda ada yg salah sm request, lempar
		// Jadi, class_id yg sama pada setiap schedule artinya kita sedang mengedit absensi untuk session yang sama
		$different = false;
		$referenceClass = '';
		
		for ($i = 0; $i < count($modelTrainingSchedule); $i++) {
			
			if ($referenceClass == '') {
				$referenceClass = $modelTrainingSchedule[$i]['training_class_id'];
			}

			if ($modelTrainingSchedule[$i]['training_class_id'] != $referenceClass) {
				$different = true;
			}

		}

		if ($different) {
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Filling attendance should for one class only!');
			return $this->redirect(['training/index']);
		}
		// dah

		// Bikin data provider schedule_trainer 
		$dataProvider = new ActiveDataProvider([
			'query' => TrainingScheduleTrainer::find()
			->where([
				'training_schedule_id' => $idSchedule
			])
			->groupBy('trainer_id')
		]);
		// dah


		// Ngambil data trainingclass
		$trainingClass = TrainingClass::findOne($referenceClass);
		// dah
		
        return $this->render('update', [
            'dataProvider' => $dataProvider,
            'training_class_id' => $referenceClass,
            'idSchedule' => $idSchedule,
            'trainingClass' => $trainingClass,
            'training_schedule_id' => $training_schedule_id
        ]);

    }






	
	public function actionEditable() {

		// Cuma ajax yg boleh manggil fungsi ni
		if (Yii::$app->request->isAjax == false) {
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Forbidden!');
			return $this->redirect(['training/index']);
		}
		// dah


		$modelTrainingScheduleTrainerAttendance = TrainingScheduleTrainer::find()
			->where([
				'id' => Yii::$app->request->post('id'),
			])
			->one();
		
		// Cek jumlah jamlat yg dinput dengan max jamlat dari schedule

		$error = '';

		if ( Yii::$app->request->post('hours') > $modelTrainingScheduleTrainerAttendance->trainingSchedule->hours) {

			// Melebihi limit. Simpan ke nilai maxnya
			$error = 'max';
			
			$modelTrainingScheduleTrainerAttendance->hours = $modelTrainingScheduleTrainerAttendance->trainingSchedule->hours;

		}
		else {

			$modelTrainingScheduleTrainerAttendance->hours = Yii::$app->request->post('hours');

		}

		if ($modelTrainingScheduleTrainerAttendance->save()) {

			echo Json::encode(['hours' => $modelTrainingScheduleTrainerAttendance->hours, 'error' => $error]);

		}
		else {

			echo Json::encode(['hours' => $modelTrainingScheduleTrainerAttendance->errors['hours'], 'error' => $error]);

		}

		//dah

	}

	public function actionOpenTbs($filetype='docx'){
		$dataProvider = new ActiveDataProvider([
            'query' => TrainingScheduleTrainerAttendance::find(),
        ]);
		
		try {
			$templates=[
				'docx'=>'ms-word.docx',
				'odt'=>'open-document.odt',
				'xlsx'=>'ms-excel.xlsx'
			];
			// Initalize the TBS instance
			$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
			// Change with Your template kaka
			$template = Yii::getAlias('@hscstudio/heart').'/extensions/opentbs-template/'.$templates[$filetype];
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "TrainingScheduleTrainerAttendance";
			$data1[]['col0'] = 'id';			
			$data1[]['col1'] = 'training_schedule_trainer_id';			
			$data1[]['col2'] = 'hours';			
			$data1[]['col3'] = 'reason';			
	
			$OpenTBS->MergeBlock('a', $data1);			
			$data2 = [];
			foreach($dataProvider->getModels() as $trainingscheduletrainerattendance){
				$data2[] = [
					'col0'=>$trainingscheduletrainerattendance->id,
					'col1'=>$trainingscheduletrainerattendance->training_schedule_trainer_id,
					'col2'=>$trainingscheduletrainerattendance->hours,
					'col3'=>$trainingscheduletrainerattendance->reason,
				];
			}
			$OpenTBS->MergeBlock('b', $data2);
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'result.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);		
	}	
	
	public function actionPhpExcel($filetype='xlsx',$template='yes',$engine='')
    {
		$dataProvider = new ActiveDataProvider([
            'query' => TrainingScheduleTrainerAttendance::find(),
        ]);
		
		try {
			if($template=='yes'){
				// only for filetypr : xls & xlsx
				if(in_array($filetype,['xlsx','xls'])){
					$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
					$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
					$template = Yii::getAlias('@hscstudio/heart').'/extensions/phpexcel-template/ms-excel.'.$filetype;
					$objPHPExcel = $objReader->load($template);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
					$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Tabel TrainingScheduleTrainerAttendance');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $trainingscheduletrainerattendance){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingscheduletrainerattendance->id)
													  ->setCellValue('B'.$idx, $trainingscheduletrainerattendance->training_schedule_trainer_id)
													  ->setCellValue('C'.$idx, $trainingscheduletrainerattendance->hours)
													  ->setCellValue('D'.$idx, $trainingscheduletrainerattendance->reason);
						$idx++;
					}		
					
					// Redirect output to a client’s web browser
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="result.'.$filetype.'"');
					header('Cache-Control: max-age=0');
					$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
					$objWriter->save('php://output');
					exit;
				}
				else{
					Yii::$app->session->setFlash('error', 'Unfortunately pdf not support, only for excel');
				}
			}
			else{
				if(in_array($filetype,['xlsx','xls'])){
					$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
					// Create new PHPExcel object
					$objPHPExcel = new \PHPExcel();
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
					$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Tabel TrainingScheduleTrainerAttendance');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $trainingscheduletrainerattendance){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingscheduletrainerattendance->id)
													  ->setCellValue('B'.$idx, $trainingscheduletrainerattendance->training_schedule_trainer_id)
													  ->setCellValue('C'.$idx, $trainingscheduletrainerattendance->hours)
													  ->setCellValue('D'.$idx, $trainingscheduletrainerattendance->reason);
						$idx++;
					}		
									
					// Redirect output to a client’s web browser (Excel2007)
					if($filetype=='xlsx')
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					// Redirect output to a client’s web browser (Excel5)
					if($filetype=='xls')
					header('Content-Type: application/vnd.ms-excel');

					header('Content-Disposition: attachment;filename="result.'.$filetype.'"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
					$objWriter->save('php://output');
					exit;				
				}
				else if(in_array($filetype,['pdf'])){
					if(in_array($engine,['tcpdf','mpdf',''])){
						$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
						if($engine=='tcpdf' or $engine==''){
							$rendererName = \PHPExcel_Settings::PDF_RENDERER_TCPDF;
							$rendererLibraryPath = Yii::getAlias('@hscstudio/heart').'/libraries/tcpdf';
						}
						else if($engine=='mpdf'){
							$rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF;
							$rendererLibraryPath = Yii::getAlias('@hscstudio/heart').'/libraries/mpdf';
						}
						// Create new PHPExcel object
						$objPHPExcel = new \PHPExcel();
						
						$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A1', 'Tabel TrainingScheduleTrainerAttendance');
						$idx=2; // line 2
						foreach($dataProvider->getModels() as $trainingscheduletrainerattendance){
							$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingscheduletrainerattendance->id)
														  ->setCellValue('B'.$idx, $trainingscheduletrainerattendance->training_schedule_trainer_id)
														  ->setCellValue('C'.$idx, $trainingscheduletrainerattendance->hours)
														  ->setCellValue('D'.$idx, $trainingscheduletrainerattendance->reason);
							$idx++;
						}		
						
						if (!\PHPExcel_Settings::setPdfRenderer(
							$rendererName,
							$rendererLibraryPath
						)){
							Yii::$app->session->setFlash('error', 
								'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
								'<br />' .
								'at the top of this script as appropriate for your directory structure'
							);
						}
						else{
							// Redirect output to a client’s web browser (PDF)
							header('Content-Type: application/pdf');
							header('Content-Disposition: attachment;filename="result.pdf"');
							header('Cache-Control: max-age=0');

							$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
							$objWriter->save('php://output');
							exit;
						}
					}
					else{
						Yii::$app->session->setFlash('error', 'Unfortunately this engine not support');
					}
				}
				else{
					Yii::$app->session->setFlash('error', 'Unfortunately filetype not support, only for excel & pdf');
				}
			}
        } catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);	
    }
	
	public function actionImport(){
		$dataProvider = new ActiveDataProvider([
            'query' => TrainingScheduleTrainerAttendance::find(),
        ]);
		
		/* 
		Please read guide of upload https://github.com/yiisoft/yii2/blob/master/docs/guide/input-file-upload.md
		maybe I do mistake :)
		*/		
		if (!empty($_FILES)) {
			$importFile = \yii\web\UploadedFile::getInstanceByName('importFile');
			if(!empty($importFile)){
				$fileTypes = ['xls','xlsx']; // File extensions allowed
				//$ext = end((explode(".", $importFile->name)));
				$ext=$importFile->extension;
				if(in_array($ext,$fileTypes)){
					$inputFileType = \PHPExcel_IOFactory::identify($importFile->tempName );
					$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($importFile->tempName );
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$baseRow = 2;
					$inserted=0;
					$read_status = false;
					$err=[];
					while(!empty($sheetData[$baseRow]['A'])){
						$read_status = true;
						$abjadX=array();
						//$id=  $sheetData[$baseRow]['A'];
						$training_schedule_trainer_id=  $sheetData[$baseRow]['B'];
						$hours=  $sheetData[$baseRow]['C'];
						$reason=  $sheetData[$baseRow]['D'];
						$status=  $sheetData[$baseRow]['E'];
						//$created=  $sheetData[$baseRow]['F'];
						//$createdBy=  $sheetData[$baseRow]['G'];
						//$modified=  $sheetData[$baseRow]['H'];
						//$modifiedBy=  $sheetData[$baseRow]['I'];
						//$deleted=  $sheetData[$baseRow]['J'];
						//$deletedBy=  $sheetData[$baseRow]['K'];

						$model2=new TrainingScheduleTrainerAttendance;
						//$model2->id=  $id;
						$model2->training_schedule_trainer_id=  $training_schedule_trainer_id;
						$model2->hours=  $hours;
						$model2->reason=  $reason;
						$model2->status=  $status;
						//$model2->created=  $created;
						//$model2->createdBy=  $createdBy;
						//$model2->modified=  $modified;
						//$model2->modifiedBy=  $modifiedBy;
						//$model2->deleted=  $deleted;
						//$model2->deletedBy=  $deletedBy;

						try{
							if($model2->save()){
								$inserted++;
							}
							else{
								foreach ($model2->errors as $error){
									$err[]=($baseRow-1).'. '.implode('|',$error);
								}
							}
						}
						catch (\yii\base\ErrorException $e){
							Yii::$app->session->setFlash('error', "{$e->getMessage()}");
							//$this->refresh();
						} 
						$baseRow++;
					}	
					Yii::$app->session->setFlash('success', ($inserted).' row inserted');
					if(!empty($err)){
						Yii::$app->session->setFlash('warning', 'There are error: <br>'.implode('<br>',$err));
					}
				}
				else{
					Yii::$app->session->setFlash('error', 'Filetype allowed only xls and xlsx');
				}				
			}
			else{
				Yii::$app->session->setFlash('error', 'File import empty!');
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'File import empty!');
		}
		
		return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);					
	}




	public function actionPrint($training_schedule_id) {

		// Casting argumen ke array
		$training_schedule_id = explode('_', $training_schedule_id);
		// dah

    	// Ngambil training schedule
    	$modelSchedule = TrainingSchedule::find()
    		->where([
    			'id' => $training_schedule_id
    		])
    		->all();
    	// dah

		// Ngecek kelas pada schedule
		// Jadi schedule2 yang diambil harus sama semua kelasnya
		// Klo beda berarti ada orang yang mainin requestnya, lempar
		$different = false;
		$referenceClass = '';
		$namaDiklat = '';
		$namaKelas = '';
		$namaUnit = '';
		$tanggalMentah = ''; // Ragu ... mungkin gak sih schedule2 yang diquery tanggal major nya beda??
		
		foreach ($modelSchedule as $row) {
			
			if ($referenceClass == '') {
				$referenceClass = $row->training_class_id;
			}

			if ($row->training_class_id != $referenceClass) {
				$different = true;
			}

			$namaDiklat = $row->trainingClass->training->activity->name;

			$namaKelas = $row->trainingClass->class;

			$namaUnit = Reference::findOne($row->trainingClass->training->activity->satker_id)->name;

			$tanggalMentah = date('Y-m-d', strtotime($row->start));

		}

		if ($different) {
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Attendance form creation should for one class only!');
			return $this->redirect(['training/index']);
		}
		// dah

    	// Ambil template
    	$template = Yii::getAlias('@backend').'/../file/template/pusdiklat/execution/2.13_contoh_daftar_hadir_pengajar_diklat.xls';
		$objPHPExcel = PHPExcel_IOFactory::load($template);
		//dah

		// Ngisi konten
		$objPHPExcel->getActiveSheet()->setCellValue('A3', strtoupper($namaUnit));
		$objPHPExcel->getActiveSheet()->setCellValue('A8', strtoupper($namaDiklat));
		$objPHPExcel->getActiveSheet()->setCellValue('A9', 'TAHUN ANGGARAN '.date('Y', strtotime($tanggalMentah)));
		$objPHPExcel->getActiveSheet()->setCellValue('A11', 'Hari / Tanggal: '.date('D, d F Y', strtotime($tanggalMentah)));
		$objPHPExcel->getActiveSheet()->setCellValue('A13', 'Kelas '.$namaKelas);

		// Bikin worksheet sejumlah schedule id
		for($a = 1; $a < count($training_schedule_id); $a++) {
			$objClonedWorksheet = clone $objPHPExcel->getSheetByName('Sheet1');
			$objClonedWorksheet->setTitle('Sheet'.($a + 1));
			$objPHPExcel->addSheet($objClonedWorksheet);
		}
		// dah

		$jumlahBaris = 0;

		$namaGender = [
			0 => 'Belum Di set',
			1 => 'Pria',
			2 => 'Wanita'
		];

		for($i = 0; $i < count($training_schedule_id); $i++) {

			$objPHPExcel->setActiveSheetIndex($i);
			
			$jumlahBaris += 0;

			// Ngisi data yang cukup sekali ngeloopnya kyk nomer, nama, nip, unit kerja
			$modelTrainingScheduleTrainer = TrainingScheduleTrainer::find()
				->where([
					'training_schedule_id' => $training_schedule_id[$i]
				])
				->all();


			$pointerBaris = 19;

			$jumlahBaris += 0;

			// Insert row sejumlah peserta yang ada
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($pointerBaris+1, count($modelTrainingScheduleTrainer));
			// dah

			// Ngisi nomer urut
			foreach ($modelTrainingScheduleTrainer as $baris) {
				
				// Ngeset Mata Pelajaran
				$objPHPExcel->getActiveSheet()->setCellValue('A12', 'Mata Diklat: '.
					ProgramSubject::findOne($baris->trainingSchedule->trainingClassSubject->program_subject_id)
					->name
				);
				// dah
				
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBaris, $pointerBaris-18);
				
				$pointerBaris += 1;

				$jumlahBaris += 1;
			}
			// dah

			// Ngisi nama
			$pointerBaris = 19;
			foreach ($modelTrainingScheduleTrainer as $baris) {
				
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBaris, 
					$baris->trainer->person->name
				);
				
				$pointerBaris += 1;
			}
			// dah

			// Ngisi L/P
			$pointerBaris = 19;
			foreach ($modelTrainingScheduleTrainer as $baris) {
				
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$pointerBaris, 
					$namaGender[$baris->trainer->person->gender]
				);
				
				$pointerBaris += 1;
			}
			// dah

			// Ngisi nip
			$pointerBaris = 19;
			foreach ($modelTrainingScheduleTrainer as $baris) {
				$objPHPExcel->getActiveSheet()->getCell('D'.$pointerBaris)->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$pointerBaris, 
					$baris->trainer->person->nip
				);
				
				$pointerBaris += 1;
			}
			// dah

			// Ngisi satker
			$pointerBaris = 19;
			foreach ($modelTrainingScheduleTrainer as $baris) {
				$unit = "-";
				$object_reference = ObjectReference::find()
					->where([
						'object' => 'person',
						'object_id' => $baris->trainer->person->id,
						'type' => 'unit',
					])
					->one();
				if(null!=$object_reference){
					$unit = $object_reference->reference->name;
				}
				else {
					$unit = $baris->trainer->person->organisation;
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$pointerBaris, 
					$unit
				);
				
				$pointerBaris += 1;
			}
			// dah

			// Finishing
			for($z = 0; $z < $jumlahBaris; $z++) {
				$objPHPExcel->getActiveSheet()->getRowDimension(19 + $z)->setRowHeight(30);
			}

			$objPHPExcel->getActiveSheet()->getStyle('A19:F'.(19 + $jumlahBaris))
				->getAlignment()
				->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$objPHPExcel->getActiveSheet()->mergeCells('A1:F'.'1');
			$objPHPExcel->getActiveSheet()->mergeCells('A2:F'.'2');
			$objPHPExcel->getActiveSheet()->mergeCells('A3:F'.'3');
			$objPHPExcel->getActiveSheet()->mergeCells('A4:F'.'4');
			$objPHPExcel->getActiveSheet()->mergeCells('A5:F'.'5');
			$objPHPExcel->getActiveSheet()->mergeCells('A6:F'.'6');
			$objPHPExcel->getActiveSheet()->mergeCells('A7:F'.'7');
			$objPHPExcel->getActiveSheet()->mergeCells('A8:F'.'8');
			$objPHPExcel->getActiveSheet()->mergeCells('A9:F'.'9');
			$objPHPExcel->getActiveSheet()->mergeCells('A10:F'.'10');
			$objPHPExcel->getActiveSheet()->mergeCells('A11:F'.'11');
			$objPHPExcel->getActiveSheet()->mergeCells('A12:F'.'12');
			$objPHPExcel->getActiveSheet()->mergeCells('A13:F'.'13');
			$objPHPExcel->getActiveSheet()->mergeCells('A14:F'.'14');

			$objPHPExcel->getActiveSheet()->removeRow(19 + $jumlahBaris,1);

			$objPHPExcel->getActiveSheet()->getRowDimension(19 + $jumlahBaris)->setRowHeight(90);
			// dah
		}
		// dah

		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.ms-excel');

		header('Content-Disposition: attachment;filename="attendance_class_'.$namaKelas.'_'.$namaDiklat.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

    }
}
