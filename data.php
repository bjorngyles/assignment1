<html>
  <table width="600">
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">

    <tr>
      <td width="20%">Select file</td>
      <td width="80%"><input type="file" name="file" id="file" /></td>
    </tr>

    <tr>
      <td>Submit</td>
      <td><input type="submit" name="submit" /></td>
    </tr>

    <body>
      <?php
        
      ?>
    </body>

    </form>
  </table>
</html>

<?php
include("classes/students.php");
include("classes/courses.php");
include("classes/studentsincourses.php");

if (isset($_POST["submit"])){
  $tmpName = $_FILES['file']['tmp_name'];
  // Map from CSV to Array
  $csv = array_map('str_getcsv', file($tmpName));
  // Make CSV array lowercase
  array_walk_recursive($csv, function (&$item) {
    $item = strtolower($item);
  });

  csvSlicer($csv);
}

  function csvSlicer ($csv){
    parseNewStudents($csv);
    parseNewCourses($csv);
    parseStudentsInCourses($csv);
  }

  function dumpStudents() {
    // create csv from validated students array
  }
  function validateStudents() {

  }

  function validateCourses() {
    
  }

  // Functions for fetching and processing Student data
  function parseNewStudents($csv) {
    // list of new students
    $parsedStudents = array();
    foreach ($csv as $fields) {
      $parsedStudents[] = array_slice($fields, 0, 4);
    }
    $removeDuplicateStudents = array_unique($parsedStudents, SORT_REGULAR);
    
    $existingStudents = Students::FetchExistingStudentData();
   
    $newStudents = Students::CreateStudents($removeDuplicateStudents);
   
    CompareNewAndExistingStudents($existingStudents, $newStudents);
  }
 
  function CompareNewAndExistingStudents($existingStudents, $newStudents){
    $NewStudentsWithoutDuplicates = Students::CompareNewAndExistingStudents($existingStudents, $newStudents);

    addNewStudent($NewStudentsWithoutDuplicates);
  }
  
  function addNewStudent($newStudents){
    Students::addNewStudentsToExistingCsv($newStudents);
  }

  function getKnownCourses() {
    // 
  }

  // Functions for fetching and processing course data
  function parseNewCourses($csv) {
    // List of new courses
    foreach ($csv as $fields) {
      $parsedCourses[] = array_slice($fields, 4, 6);   
    }
    //Filter out duplicate Courses
    $removeDuplicateCourses = array_unique($parsedCourses, SORT_REGULAR);
   
    $existingCourses = Courses::FetchExistingCourseData();
    
    $newCourses = Courses::CreateCourses($removeDuplicateCourses);
    
    CompareNewAndExistingCourses($existingCourses, $newCourses);
  }

  function CompareNewAndExistingCourses($existingCourses, $newCourses){
    // Compare New and existing courses
    $NewCoursesWithoutDuplicates = Courses::CompareNewAndExistingCourses($existingCourses, $newCourses);
    
    addNewCourses($NewCoursesWithoutDuplicates);
  }

  function addNewCourses($newCourses){
    Courses::addNewCoursesToExistingCsv($newCourses);
  }


  //Functions for fetching Data for students and courses they are in
  function parseStudentsInCourses($csv) {
    // List of new StudentsInCourses
    foreach ($csv as $fields) {
      $parsedStudentsInCourses[] = array($fields[0], $fields[4], $fields[6], $fields[7], $fields[10]);   
    }
    //Filter out duplicate Courses
    $removeDuplicateStudentsInCourses = array_unique($parsedStudentsInCourses, SORT_REGULAR);

    $existingStudInCourses = StudentInCourses::FetchExistingStudentInCourseData();
    
    $newStudentInCourses = StudentInCourses::CreateStudentInCourses($removeDuplicateStudentsInCourses);

    CompareNewAndExistingStudentInCourses($existingStudInCourses, $newStudentInCourses);
  }

  function CompareNewAndExistingStudentInCourses($existingStudentInCourses, $newStudentInCourses){
    // Compare New and existing Student in courses
    $NewStudentInCoursesWithoutDuplicates = StudentInCourses::CompareNewAndExistingStudentInCourses($existingStudentInCourses, $newStudentInCourses);
    
    addNewStudentInCourses($NewStudentInCoursesWithoutDuplicates);
  }

  function addNewStudentInCourses($newStudentInCourses){
    StudentInCourses::addNewStudInCoursesToExistingCsv($newStudentInCourses);
  }
  createStudentsTable();

  Function createStudentsTable(){
    $existingStudents = Students::FetchExistingStudentData();
    $studentInCourses = StudentInCourses::FetchExistingStudentInCourseData();

    echo "<table>";
    echo "<tr><th>Student Number</th><th>Name</th><th>Surname</th><th>Birthdate</th><th>Completed Courses</th><th>Failed Courses</th><th>GPA</th><th>Status</th></tr>";
    foreach ($existingStudents as $key) {
      echo "<tr><td>" . $key->getStudentnr() . 
           "</td><td>" .$key->getFname() . 
           "</td><td>" . $key->getLname() . 
           "</td><td>" . $key->getBdate() .

           "</td></tr>";
    }
    echo "</table>";
  }




















  // function fileCompare($studentArr, $coursesArr, $studentinCourse, $csvStud, $csvCourse, $csvStudCourse){
    
  //   /**
  //    * array_map() runs each sub-array of the main arrays through serialize()
  //    *serialize() converts each sub-array into a string representation of that sub-array
  //    *the main arrays now have values that are not arrays but string representations of the sub-arrays
  //    *array_diff() now has a one-dimensional array for each of the arrays to compare
  //    *after the difference is returned array_map() runs the array result (differences) through unserialize() to turn the string representations back into sub-arrays
  //    */

  //   $studDiff = array_map('unserialize',
  //   array_diff(array_map('serialize', $studentArr), 
  //   array_map('serialize', $csvStud)));

  //   $courseDiff = array_map('unserialize',
  //   array_diff(array_map('serialize', $coursesArr), 
  //   array_map('serialize', $csvCourse)));

  //   $studCourseDiff= array_map('unserialize',
  //   array_diff(array_map('serialize', $studentinCourse), 
  //   array_map('serialize', $csvStudCourse)));

  //   if (empty($studDiff or $courseDiff or $studCourseDiff)){
  //     echo "<script type='text/javascript'>alert('Nothing to add');</script>";
  //   } else {
  //     echo "<script type='text/javascript'>alert('Added new info');</script>";
  //     csvWriter($studDiff, $courseDiff, $studCourseDiff);
  //   }

  // }
  
  // function csvWriter($csvDiff, $filePath){
  //   $fp = fopen($filePath, 'a');
  //   foreach ($csvDiff as $fields) {
  //       fputcsv($fp, $fields);
  //   }
  //   fclose($fp);

    // $fp = fopen('data/courses.csv', 'a');
    // foreach ($courseDiff as $fields) {
    //     fputcsv($fp, $fields);
    // }
    // fclose($fp);
  
    // $fp = fopen('data/studcourse.csv', 'a');
    // foreach ($studCourseDiff as $fields) {
    //     fputcsv($fp, $fields);
    // }
    // fclose($fp);
    
  // }
 
 
// echo "<pre>";
// var_dump($csv);
// echo "</pre>";
// echo "student";
// echo "<pre>";
// var_dump($studentArr);
// echo "</pre>";

// echo "Courses";
// echo "<pre>";
// var_dump($coursesArr);
// echo "</pre>";





?>