<?php 
	class Student {
		//Properties
		private $studentnr;
		private $fname;
		private $lname;
		private $bdate;
		private $completedCourse;
		private $failedCourse;
		private $gpa;

		public function __construct($studentnr, $fname, $lname, $bdate) {
			$this->studentnr = $studentnr;
			$this->fname = $fname;
			$this->lname = $lname;
			$this->bdate = $bdate;
		}
		public function getStudentnr(){
			return $this->studentnr;
		}
		public function getFname()
		{
			return $this->fname;
		}
		public function getLname()
		{
			return $this->lname;
		}
		public function getBdate()
		{
			return $this->bdate;
		}
		public function setCompletedCourses()
		{
			$this->completedCourse = $completedCourse;
		}
		public function isEqualTo($other)
		{	
			if ($other == null) {
				return false;
			}
			return $this->studentnr === $other->getStudentnr();
		}
	} 



	 
	 class Students {
		public function CreateStudents($newStudents): array {
			return array_map( function($val) {
				if ($val[0] != null && $val[1] != null && $val[2] != null && $val[3] != null) {
					return new Student($val[0], $val[1], $val[2], $val[3]); 
				}
			}, $newStudents);
		 }


		 public function CheckStudentUniqueness($students, $newStudent): string {
			$result = array_filter($students, function($student) use ($newStudent){
				return $student->isEqualTo($newStudent);
			});

			$counter = count($result);
			
			if ($counter > 0) {
				return "Duplicate student id: ".$newStudent->getStudentnr();
			}
			return "OK";
		 }

		 public function CreateSeveralNewStudentsCheckUniqueness($existing, $newStudents): array {
			//  $result = array_filter($newStudents, function($existed) use ($existing){
			// 	return new Student($existed[0], $existed[1], $existed[2], $existed[3]);
			// }); 
			$result = array_diff($newStudents, $existing);

			return [new Student($result[$studentnr], $result[1], $result[2], $result[3])];
		 }

		 public function FetchExistingStudentData () {
			$knownStudents = array_map('str_getcsv', file('data/student.csv'));
			return Students::CreateStudents($knownStudents);
		 }
		 
		 public function CompareNewAndExistingStudents($existingStudents, $newStudents){
			$diff = Array();

			foreach ($newStudents as $key => $val1) {
				if (array_search($val1, $existingStudents) === false) {
					$diff[$key] = $val1;
				}
			} return $diff;
		 }

		 // Skulle denne vært i en annen klasse kanskje? Ser for meg en klass som kan
		 // fikse all lagring til csv, det være studenter, courses eller andre ting. Fix at will.
		 public function addNewStudentsToExistingCsv ($newStudents) {
			if(empty($newStudents)){
				echo "<script type='text/javascript'>alert('No new students added');</script>";
			} else {
				$fp = fopen('./data/student.csv', 'a');
				foreach ($newStudents as $fields) {
				  if( is_object($fields) )
				  $fields = (array) $fields;
			  fputcsv($fp, $fields);
				  }
				  fclose($fp);
				  $counter = count($newStudents);
				  echo "<script type='text/javascript'>alert('$counter new students added');</script>";
			}

		 public function calculateGpa(){

		 }
		 public function countFailedAndSucceededCourses(){
			 
		 }
		}
	 }






	 

// 	 function studentArr_mapper($studentArr){
// 		$csvStud = array_map('str_getcsv', file('data/student.csv'));
// 	   // $csvCourse = array_map('str_getcsv', file('data/courses.csv'));
// 	   // $csvStudCourse = array_map('str_getcsv', file('data/studcourse.csv'));
// 	   // var_dump($csvStud);
// 		   echo "<pre>";
// 		   var_dump($csvStud);
// 		   echo "</pre>";
   
// 	   $j = 0;
// 	   foreach ($csvStud as $key => $value) {
// 		 $existing[$j] = new Student($value[0], $value[1], $value[2], $value[3]);
// 		 $j++;
// 		}
   
// 	   $i = 0;
// 	   foreach ($studentArr as $key => $value) {
// 		$student[$i] = new Student($value[0], $value[1], $value[2], $value[3]);
// 		$i++;
// 	   }
	   
// 	   studentToArray($student, $existing);
// 	 }

// 	function studentToArray($student, $existing){
// 		$resultStud = array();
// 		$resultExist = array();
// 		foreach ($student as $object)
// 		{
// 			$resultStud[] = (array) $object;
// 		}

// 		foreach ($existing as $object)
// 		{
// 			$resultExist[] = (array) $object;
// 		}
// 		studentCompare($resultStud, $resultExist);
// 	}

// 	function studentCompare($student, $existing){
// 		$studDiff = array_map('unserialize',
// 		array_diff(array_map('serialize', $student), 
// 		array_map('serialize', $existing)));
		
// 		echo "<pre>";
// 		var_dump($studDiff);
// 		echo "</pre>";

//   	 	if (empty($studDiff)){
// 			   echo "<script type='text/javascript'>alert('No new students added');</script>";
// 			} else {
// 				echo "<script type='text/javascript'>alert('Added a new student');</script>";
// 				csvWriter($studDiff, 'data/student.csv');
// 			}
// 		}
// ?>
