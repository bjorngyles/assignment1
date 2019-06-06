<?php 
	class Course {
		//Properties
		private $cId;
		private $cName;
		private $cYear;
		private $cSemester;
		private $cInstructor;
		private $cCredit;

		public function __construct($cId, $cName, $cYear, $cSemester, $cInstructor, $cCredit) {
			$this -> cId = $cId;
			$this -> cName = $cName;
			$this -> cYear = $cYear;
			$this -> cSemester = $cSemester;
			$this -> cInstructor = $cInstructor;
			$this -> cCredit = $cCredit;
		}
		public function getCourseId(){
			return $this->cId;
		}
		public function getCourseName(){
			return $this->cName;
		}

		public function getCourseYear()
		{
			return $this->cYear;
		}
		public function getCourseSemester()
		{
			return $this->cSemester;
		}
		public function getCourseIntstructor()
		{
			return $this->cInstructor;
		}
		public function getCourseCredit()
		{
			return $this->cCredit;
		}
	} 

	class Courses {
		public function CreateCourses($newCourses): array {
			return array_map( function($val) {
				if ($val[0] != null && $val[1] != null && $val[2] != null && $val[3] != null && $val[4] != null && $val[5] != null) {
					return new Course($val[0], $val[1], $val[2], $val[3], $val[4], $val[5]); 
				}
			}, $newCourses);
		 }


		 public function CheckCourseUniqueness($courses, $newCourse): string {
			$result = array_filter($courses, function($course) use ($newCourse){
				return $course->isEqualTo($newCourse);
			});

			$counter = count($result);
			
			if ($counter > 0) {
				return "Duplicate course id: ".$newCourse->getCourseId();
			}
			return "OK";
		 }

		 public function CreateSeveralNewCoursesCheckUniqueness($existing, $newCourses): array {
			//  $result = array_filter($newStudents, function($existed) use ($existing){
			// 	return new Student($existed[0], $existed[1], $existed[2], $existed[3]);
			// }); 
			$result = array_diff($newCourses, $existing);

			return [new Student($result[$cId], $result[1], $result[2], $result[3])];
		 }

		 public function FetchExistingCourseData () {
			$knownCourses = array_map('str_getcsv', file('data/courses.csv'));
			return Courses::CreateCourses($knownCourses);
		 }
		 
		 public function CompareNewAndExistingCourses($existingCourses, $newCourses){
			$diff = Array();
			foreach ($newCourses as $key => $val1) {
				if (array_search($val1, $existingCourses) === false) {
					$diff[$key] = $val1;
				}
			} return $diff;
		 }

		// Samme som jeg kommenterte i Students-klassen, fix at will.
		 public function addNewCoursesToExistingCsv ($newCourses) {
			if(empty($newCourses)){
				echo "<script type='text/javascript'>alert('No new courses added');</script>";
			} else {
				$counter = count($newCourses);
				$fp = fopen('./data/courses.csv', 'a');
				foreach ($newCourses as $fields) {
				  if( is_object($fields) )
				  $fields = (array) $fields;
				  fputcsv($fp, $fields);
				  }
				  fclose($fp);
				  
				  echo "<script type='text/javascript'>alert('$counter new courses added');</script>";
			}
		}
	 }
?>
