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

?>