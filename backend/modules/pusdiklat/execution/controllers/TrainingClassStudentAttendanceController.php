<?php

namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;

use backend\models\TrainingClassStudentAttendance;
use backend\models\TrainingClassStudentAttendanceSearch;
use backend\models\TrainingClassStudent;
use backend\models\TrainingSchedule;
use backend\models\TrainingClass;
use backend\models\ProgramSubject;
use backend\models\ObjectReference;
use backend\models\Reference;

use backend\modules\pusdiklat\execution\models\TrainingClassStudentSearch;

class TrainingClassStudentAttendanceController extends Controller
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
		// Jadi, class_id yg sama pada setiap schedule artinya kita sedang mengedit absensi untuk class yang sama
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

		// Input tabel attendance dg schedule_id dan student_id
		$readyInjectStudent2Attendance = TrainingClassStudent::find()->where(['training_class_id' => $referenceClass])->all();

		for ($i = 0; $i < count($idSchedule); $i++) {						// Ngeloop dulu, siapa tau schedule_id nya lebih dari 1
			foreach ($readyInjectStudent2Attendance as $row) {						// Dari sini, mulai nginject
				$injector = TrainingClassStudentAttendance::find()
					->where([
						'training_schedule_id' => $idSchedule[$i],
						'training_class_student_id' => $row->id
					])
					->one();

				// Cek uda ada record ga?
				if ($injector === null) {
					$injector = new TrainingClassStudentAttendance;
					$injector->training_schedule_id = $idSchedule[$i];
					$injector->training_class_student_id = $row->id;
					$injector->hours = $modelTrainingSchedule[$i]->hours;
					$injector->status = 1;
					$injector->save();
				}
			}
		}
		// dah

		// Bikin data provider student dari class schedule
		$searchModel = new TrainingClassStudentSearch(); 

		$queryParams['TrainingClassStudentSearch'] = [
			'training_class_id' => $referenceClass
		];

		$queryParams = ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);

		$dataProvider = $searchModel->search($queryParams);
		// dah


		// Ngambil data trainingclass
		$trainingClass = TrainingClass::findOne($referenceClass);
		
        return $this->render('update', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'training_schedule_id' => $training_schedule_id,
            'training_class_id' => $referenceClass,
            'idSchedule' => $idSchedule,
            'trainingClass' => $trainingClass
        ]);

    }





	
	public function actionEditable() {

		// Cuma ajax yg boleh manggil fungsi ni
		if (Yii::$app->request->isAjax == false) {
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Forbidden!');
			return $this->redirect(['activity2/index']);
		}
		// dah

		$modelTrainingClassStudentAttendance = TrainingClassStudentAttendance::find()
			->where([
				'training_class_student_id' => Yii::$app->request->post('training_class_student_id'),
				'training_schedule_id' => Yii::$app->request->post('training_schedule_id'),
			])
			->one();

		// Cek jumlah jamlat yg dinput dengan max jamlat dari schedule
		$error = '';

		$modelTrainingSchedule = TrainingSchedule::findOne($modelTrainingClassStudentAttendance->training_schedule_id);

		if ( Yii::$app->request->post('hours') > $modelTrainingSchedule->hours) {

			// Melebihi limit. Simpan ke nilai maxnya
			$error = 'max';
			
			$modelTrainingClassStudentAttendance->hours = $modelTrainingSchedule->hours;

		}
		else {

			$modelTrainingClassStudentAttendance->hours = Yii::$app->request->post('hours');

		}

		if ($modelTrainingClassStudentAttendance->save()) {

			echo Json::encode(['hours' => $modelTrainingClassStudentAttendance->hours, 'error' => $error]);

		}
		else {

			echo Json::encode(['hours' => $modelTrainingClassStudentAttendance->errors['hours'], 'error' => $error]);

		}

		//dah

	}

	public function actionOpenTbs($filetype='docx'){
		$dataProvider = new ActiveDataProvider([
            'query' => TrainingClassStudentAttendance::find(),
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
			$OpenTBS->VarRef['modelName']= "TrainingClassStudentAttendance";
			$data1[]['col0'] = 'id';			
			$data1[]['col1'] = 'training_schedule_id';			
			$data1[]['col2'] = 'training_class_student_id';			
			$data1[]['col3'] = 'hours';			
	
			$OpenTBS->MergeBlock('a', $data1);			
			$data2 = [];
			foreach($dataProvider->getModels() as $trainingclassstudentattendance){
				$data2[] = [
					'col0'=>$trainingclassstudentattendance->id,
					'col1'=>$trainingclassstudentattendance->training_schedule_id,
					'col2'=>$trainingclassstudentattendance->training_class_student_id,
					'col3'=>$trainingclassstudentattendance->hours,
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
            'query' => TrainingClassStudentAttendance::find(),
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
								->setCellValue('A1', 'Tabel TrainingClassStudentAttendance');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $trainingclassstudentattendance){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingclassstudentattendance->id)
													  ->setCellValue('B'.$idx, $trainingclassstudentattendance->training_schedule_id)
													  ->setCellValue('C'.$idx, $trainingclassstudentattendance->training_class_student_id)
													  ->setCellValue('D'.$idx, $trainingclassstudentattendance->hours);
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
								->setCellValue('A1', 'Tabel TrainingClassStudentAttendance');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $trainingclassstudentattendance){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingclassstudentattendance->id)
													  ->setCellValue('B'.$idx, $trainingclassstudentattendance->training_schedule_id)
													  ->setCellValue('C'.$idx, $trainingclassstudentattendance->training_class_student_id)
													  ->setCellValue('D'.$idx, $trainingclassstudentattendance->hours);
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
									->setCellValue('A1', 'Tabel TrainingClassStudentAttendance');
						$idx=2; // line 2
						foreach($dataProvider->getModels() as $trainingclassstudentattendance){
							$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingclassstudentattendance->id)
														  ->setCellValue('B'.$idx, $trainingclassstudentattendance->training_schedule_id)
														  ->setCellValue('C'.$idx, $trainingclassstudentattendance->training_class_student_id)
														  ->setCellValue('D'.$idx, $trainingclassstudentattendance->hours);
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
            'query' => TrainingClassStudentAttendance::find(),
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
						$training_schedule_id=  $sheetData[$baseRow]['B'];
						$training_class_student_id=  $sheetData[$baseRow]['C'];
						$hours=  $sheetData[$baseRow]['D'];
						$reason=  $sheetData[$baseRow]['E'];
						$status=  $sheetData[$baseRow]['F'];
						//$created=  $sheetData[$baseRow]['G'];
						//$createdBy=  $sheetData[$baseRow]['H'];
						//$modified=  $sheetData[$baseRow]['I'];
						//$modifiedBy=  $sheetData[$baseRow]['J'];
						//$deleted=  $sheetData[$baseRow]['K'];
						//$deletedBy=  $sheetData[$baseRow]['L'];

						$model2=new TrainingClassStudentAttendance;
						//$model2->id=  $id;
						$model2->training_schedule_id=  $training_schedule_id;
						$model2->training_class_student_id=  $training_class_student_id;
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
    	$template = Yii::getAlias('@backend').'/../file/template/pusdiklat/execution/2.12_contoh_daftar_hadir_peserta_diklat_27082014.xls';
		$objPHPExcel = PHPExcel_IOFactory::load($template);
		//dah

		// Ngisi konten
		$objPHPExcel->getActiveSheet()->setCellValue('A3', $namaUnit);
		$objPHPExcel->getActiveSheet()->setCellValue('A8', strtoupper($namaDiklat));
		$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Kelas '.$namaKelas);
		$objPHPExcel->getActiveSheet()->setCellValue('A10', 'TAHUN ANGGARAN '.date('Y', strtotime($tanggalMentah)));
		$objPHPExcel->getActiveSheet()->setCellValue('A12', 'Hari / Tanggal: '.date('D, d F Y', strtotime($tanggalMentah)));


		$pointerKolomMP = ord('E');

		$jumlahBaris = 0;

		for($i = 0; $i < count($training_schedule_id); $i++) {
			
			$jumlahBaris += 0;

			// Ngisi data yang cukup sekali ngeloopnya kyk nomer, nama, nip, unit kerja
			if ($i == 0) {
				$modelTrainingClassStudentAttendance = TrainingClassStudentAttendance::find()
					->where([
						'training_schedule_id' => $training_schedule_id[$i]
					])
					->all();

				$pointerBaris = 17;

				$jumlahBaris += 0;

				// Insert row sejumlah peserta yang ada
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($pointerBaris+1, count($modelTrainingClassStudentAttendance));
				// dah

				// Ngisi nomer urut
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBaris, $pointerBaris-16);
					
					$pointerBaris += 1;

					$jumlahBaris += 1;
				}
				// dah

				// Ngisi nama
				$pointerBaris = 17;
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBaris, 
						$baris->trainingClassStudent->trainingStudent->student->person->name
					);
					
					$pointerBaris += 1;
				}
				// dah

				// Ngisi nip
				$pointerBaris = 17;
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$pointerBaris, 
						$baris->trainingClassStudent->trainingStudent->student->person->nip, 
						PHPExcel_Cell_DataType::TYPE_STRING);
					
					$pointerBaris += 1;
				}
				// dah

				// Ngisi satker
				$pointerBaris = 17;
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					$unit = "-";
					$object_reference = ObjectReference::find()
						->where([
							'object' => 'person',
							'object_id' => $baris->trainingClassStudent->trainingStudent->student->person->id,
							'type' => 'unit',
						])
						->one();
					if(null!=$object_reference){
						$unit = $object_reference->reference->name;
					}
					if($baris->trainingClassStudent->trainingStudent->student->satker==2){
						if(!empty($baris->trainingClassStudent->trainingStudent->student->eselon2)){
							$unit = $baris->trainingClassStudent->trainingStudent->student->eselon2;
						}
					}
					else if($baris->trainingClassStudent->trainingStudent->student->satker==3){
						if(!empty($baris->trainingClassStudent->trainingStudent->student->eselon3)){
							$unit = $baris->trainingClassStudent->trainingStudent->student->eselon3;
						}
					}
					else if($baris->trainingClassStudent->trainingStudent->student->satker==4){
						if(!empty($baris->trainingClassStudent->trainingStudent->student->eselon4)){
							$unit = $baris->trainingClassStudent->trainingStudent->student->eselon4;
						}
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$pointerBaris, 
						$unit
					);
					
					$pointerBaris += 1;
				}
				// dah

			}
			//dah

			// Ngisi data yang butuh loop khusus yaitu mata pelajaran
			$modelTrainingSchedule = TrainingSchedule::find()
				->where(['id' => $training_schedule_id[$i]])
				->one();

			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(17-3), 
				$objPHPExcel->getActiveSheet()->getCell('E14')->getValue()
			);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(17-2), 
				$objPHPExcel->getActiveSheet()->getCell('E15')->getValue()
			);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(17-1), 
				$objPHPExcel->getActiveSheet()->getCell('E16')->getValue()
			);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(17 + $jumlahBaris + 1), 
				$objPHPExcel->getActiveSheet()->getCell('E'.(17 + $jumlahBaris + 1))->getValue()
			);

			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E14'), chr($pointerKolomMP).'14');
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E15'), chr($pointerKolomMP).'15');
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E16'), chr($pointerKolomMP).'16');
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E'.(17 + $jumlahBaris + 1)), 
				chr($pointerKolomMP).(17 + $jumlahBaris + 1)
			);

			for($j = 0; $j < $jumlahBaris; $j++) {
				$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(17 + $j), 
					$objPHPExcel->getActiveSheet()->getCell('E'.(17 + $j))->getValue()
				);

				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E'.(17 + $j)), 
					chr($pointerKolomMP).(17 + $j)
				);
			}

			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).'15', 
				'Mata Diklat: '.ProgramSubject::findOne($modelTrainingSchedule->trainingClassSubject->program_subject_id)->name
			);

			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($pointerKolomMP))->setWidth(20);

			$pointerKolomMP += 1;
			// dah
		}
		// dah

		// Finishing
		for($z = 1; $z <= $jumlahBaris; $z++) {
			$objPHPExcel->getActiveSheet()->getRowDimension($z + $jumlahBaris)->setRowHeight(30);
		}

		$objPHPExcel->getActiveSheet()->getStyle('A17:'.chr($pointerKolomMP).(17 + $jumlahBaris))
			->getAlignment()
			->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr($pointerKolomMP - 1).'1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:'.chr($pointerKolomMP - 1).'2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:'.chr($pointerKolomMP - 1).'3');
		$objPHPExcel->getActiveSheet()->mergeCells('A4:'.chr($pointerKolomMP - 1).'4');
		$objPHPExcel->getActiveSheet()->mergeCells('A5:'.chr($pointerKolomMP - 1).'5');
		$objPHPExcel->getActiveSheet()->mergeCells('A6:'.chr($pointerKolomMP - 1).'6');
		$objPHPExcel->getActiveSheet()->mergeCells('A7:'.chr($pointerKolomMP - 1).'7');
		$objPHPExcel->getActiveSheet()->mergeCells('A8:'.chr($pointerKolomMP - 1).'8');
		$objPHPExcel->getActiveSheet()->mergeCells('A9:'.chr($pointerKolomMP - 1).'9');
		$objPHPExcel->getActiveSheet()->mergeCells('A10:'.chr($pointerKolomMP - 1).'10');
		$objPHPExcel->getActiveSheet()->mergeCells('A11:'.chr($pointerKolomMP - 1).'11');
		$objPHPExcel->getActiveSheet()->mergeCells('A12:'.chr($pointerKolomMP - 1).'12');

		$objPHPExcel->getActiveSheet()->removeRow(17 + $jumlahBaris,1);

		$objPHPExcel->getActiveSheet()->getRowDimension(17 + $jumlahBaris)->setRowHeight(90);
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




    public function actionRecap($training_schedule_id) {

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
    	$template = Yii::getAlias('@backend').'/../file/template/pusdiklat/execution/2.15_contoh_rekapitulasi_kehadiran_peserta_dan_pengajar_24082014.xls';
		$objPHPExcel = PHPExcel_IOFactory::load($template);
		//dah

		// Ngisi konten
		$objPHPExcel->getActiveSheet()->setCellValue('A2', strtoupper($namaDiklat));
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Kelas '.$namaKelas);
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'TAHUN ANGGARAN '.date('Y', strtotime($tanggalMentah)));

		$pointerKolomMP = ord('E');

		$jumlahBaris = 0;

		$jp = [];

		for($i = 0; $i < count($training_schedule_id); $i++) {
			
			$jumlahBaris += 0;

			// Ngisi data yang cukup sekali ngeloopnya kyk nomer, nama, nip, unit kerja
			if ($i == 0) {
				$modelTrainingClassStudentAttendance = TrainingClassStudentAttendance::find()
					->where([
						'training_schedule_id' => $training_schedule_id[$i]
					])
					->all();

				$pointerBaris = 10;

				$jumlahBaris += 0;

				// Insert row sejumlah peserta yang ada
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($pointerBaris+1, count($modelTrainingClassStudentAttendance));
				// dah

				// Ngisi nomer urut
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBaris, $pointerBaris-9);
					
					$pointerBaris += 1;

					$jumlahBaris += 1;
				}
				// dah

				// Ngisi nama
				$pointerBaris = 10;
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBaris, 
						$baris->trainingClassStudent->trainingStudent->student->person->name
					);
					
					$pointerBaris += 1;
				}
				// dah

				// Ngisi nip
				$pointerBaris = 10;
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$pointerBaris, 
						$baris->trainingClassStudent->trainingStudent->student->person->nip, 
						PHPExcel_Cell_DataType::TYPE_STRING);
					
					$pointerBaris += 1;
				}
				// dah

				// Ngisi satker
				$pointerBaris = 10;
				foreach ($modelTrainingClassStudentAttendance as $baris) {
					$unit = "-";
					$object_reference = ObjectReference::find()
						->where([
							'object' => 'person',
							'object_id' => $baris->trainingClassStudent->trainingStudent->student->person->id,
							'type' => 'unit',
						])
						->one();
					if(null!=$object_reference){
						$unit = $object_reference->reference->name;
					}
					if($baris->trainingClassStudent->trainingStudent->student->satker==2){
						if(!empty($baris->trainingClassStudent->trainingStudent->student->eselon2)){
							$unit = $baris->trainingClassStudent->trainingStudent->student->eselon2;
						}
					}
					else if($baris->trainingClassStudent->trainingStudent->student->satker==3){
						if(!empty($baris->trainingClassStudent->trainingStudent->student->eselon3)){
							$unit = $baris->trainingClassStudent->trainingStudent->student->eselon3;
						}
					}
					else if($baris->trainingClassStudent->trainingStudent->student->satker==4){
						if(!empty($baris->trainingClassStudent->trainingStudent->student->eselon4)){
							$unit = $baris->trainingClassStudent->trainingStudent->student->eselon4;
						}
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$pointerBaris, 
						$unit
					);
					
					$pointerBaris += 1;
				}
				// dah

			}
			//dah

			// Ngisi data yang butuh loop khusus yaitu mata pelajaran
			$modelTrainingSchedule = TrainingSchedule::find()
				->where(['id' => $training_schedule_id[$i]])
				->one();

			if ($pointerKolomMP == ord('E')) {
				$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(10-1), 
					ProgramSubject::findOne($modelTrainingSchedule->trainingClassSubject->program_subject_id)->name
				);
			}
			else {
				$objPHPExcel->getActiveSheet()->insertNewColumnBefore(chr($pointerKolomMP), 1);
				
				$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(10-1), 
					ProgramSubject::findOne($modelTrainingSchedule->trainingClassSubject->program_subject_id)->name
				);

				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E'.(10 - 1)), 
					chr($pointerKolomMP).(10 - 1)
				);
			}

			$modelTrainingClassStudentAttendance = TrainingClassStudentAttendance::find()
				->where([
					'training_schedule_id' => $training_schedule_id[$i]
				])
				->all();

			$pointerBaris = 0;

			foreach ($modelTrainingClassStudentAttendance as $baris) {
				$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(10 + $pointerBaris), 
					$baris->hours
				);

				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E'.(10 + $pointerBaris)), 
					chr($pointerKolomMP).(10 + $pointerBaris)
				);

				// Nyimpen jamlat ideal dari schedule
				if (empty($jp[$pointerBaris])) {
					$jp[$pointerBaris] = [];
				}


				if (array_key_exists('hadir', $jp[$pointerBaris])) {
					$jp[$pointerBaris]['hadir'] += $baris->hours;
				}
				else {
					$jp[$pointerBaris]['hadir'] = $baris->hours;
				}

				if (array_key_exists('totalIdeal', $jp[$pointerBaris])) {
					$jp[$pointerBaris]['totalIdeal'] += TrainingSchedule::findOne($baris->training_schedule_id)->hours;
				}
				else {
					$jp[$pointerBaris]['totalIdeal'] = TrainingSchedule::findOne($baris->training_schedule_id)->hours;
				}
				// dah

				$pointerBaris += 1;
			}

			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($pointerKolomMP))->setWidth(10);

			$pointerKolomMP += 1;
			// dah
		}
		// dah

		// Ngitung hadir-bolos-total
		for($b = 0; $b < count($jp); $b++) {
			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP).(10 + $b), 
				$jp[$b]['hadir']
			);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP + 1).(10 + $b), 
				$jp[$b]['totalIdeal'] - $jp[$b]['hadir']
			);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($pointerKolomMP + 2).(10 + $b), 
				$jp[$b]['hadir'] / $jp[$b]['totalIdeal']
			);
		}

		// Finishing
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr($pointerKolomMP + 3).'1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:'.chr($pointerKolomMP + 3).'2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:'.chr($pointerKolomMP + 3).'3');
		$objPHPExcel->getActiveSheet()->mergeCells('A4:'.chr($pointerKolomMP + 3).'4');
		$objPHPExcel->getActiveSheet()->mergeCells('E6:'.chr($pointerKolomMP - 1).'8');

		$objPHPExcel->getActiveSheet()->removeRow(10 + $jumlahBaris,1);

		// dah
		
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.ms-excel');

		header('Content-Disposition: attachment;filename="recapitulation_attendance_class_'.$namaKelas.'_'.$namaDiklat.'.xls"');
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
