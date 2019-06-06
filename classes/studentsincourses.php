<?php 
	class StudentInCourse {
		//Properties
		private $studentId;
		private $courseId;
		private $courseYear;
		private $courseSemester;
		private $courseGrade;

		public function __construct($studentId, $courseId, $courseYear, $courseSemester, $courseGrade) {
			$this -> studentId = $studentId;
			$this -> courseId = $courseId;
			$this -> courseYear = $courseYear;
			$this -> courseSemester = $courseSemester;
			$this -> courseGrade = $courseGrade;
		}
		public function getStudentId(){
			return $this->studentId;
		}
		public function getCourseId(){
			return $this->courseId;
		}
		public function getCourseYear()
		{
			return $this->courseYear;
		}
		public function getCourseSemester()
		{
			return $this->courseSemester;
		}
		public function getCourseGrade()
		{
			return $this->courseGrade;
		}
	} 

	class StudentInCourses {
		public function CreateStudentInCourses($newStudentCourses): array {
			return array_map( function($val) {
				if ($val[0] != null && $val[1] != null && $val[2] != null && $val[3] != null && $val[4] != null) {
					return new StudentinCourse($val[0], $val[1], $val[2], $val[3], $val[4]); 
				}
			}, $newStudentCourses);
		 }


		 public function CheckStudentInCourseUniqueness($StudentIncourses, $newStudentInCourse): string {
			$result = array_filter($StudentIncourses, function($course) use ($newStudentInCourse){
				return $course->isEqualTo($newStudentInCourse);
			});

			$counter = count($result);
			
			if ($counter > 0) {
				return "Duplicate course id: ".$newStudentInCourse->getCourseId();
			}
			return "OK";
		 }

		 public function CreateSeveralNewStudentInCoursesCheckUniqueness($existing, $newStudentInCourses): array {
			//  $result = array_filter($newStudents, function($existed) use ($existing){
			// 	return new Student($existed[0], $existed[1], $existed[2], $existed[3]);
			// }); 
			$result = array_diff($newStudentInCourses, $existing);

			return [new StudentinCourse($result[$cId], $result[1], $result[2], $result[3])];
		 }

		 public function FetchExistingStudentInCourseData () {
			$knownStudentInCourses = array_map('str_getcsv', file('data/studcourse.csv'));
			return StudentinCourses::CreateStudentInCourses($knownStudentInCourses);
		 }
		 
		 public function CompareNewAndExistingStudentInCourses($existingStudentInCourses, $newStudentInCourses){
			$diff = Array();
			foreach ($newStudentInCourses as $key => $val1) {
				if (array_search($val1, $existingStudentInCourses) === false) {
					$diff[$key] = $val1;
				}
			} return $diff;
		 }

		 public function addNewStudInCoursesToExistingCsv ($newStudentInCourses) {
			if(empty($newStudentInCourses)){
				echo "<script type='text/javascript'>alert('No new courses added');</script>";
			} else {
				$counter = count($newStudentInCourses);
				$fp = fopen('./data/studcourse.csv', 'a');
				foreach ($newStudentInCourses as $fields) {
				  if( is_object($fields) )
				  $fields = (array) $fields;
				  fputcsv($fp, $fields);
				  }
				  fclose($fp);
				  
				  echo "<script type='text/javascript'>alert('$newStudentInCourses new Student courses added');</script>";
			}
        }
        public function calculateGpa(){

        }
	 }
?>