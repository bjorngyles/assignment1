<?php
declare(strict_types=1);
include("./classes/students.php");
use PHPUnit\Framework\TestCase;

final class StudentsTest extends TestCase {
    public function testCanCreateStudent():void {
        // Act
        $newStudents = [[190199,synne,leirfall,20-01-1994]];

        $res = Students::CreateStudents($newStudents);
        
        $expected = new Student(190199,synne,leirfall,20-01-1994);
        
        // Assert
        $this->assertEquals($expected, $res[0]);
    }

    public function testCanCreateCorrectStudents(): void {
        // Arrange
        $studentArr = [
            [490199,bjørn,"bordewich gyles",04-11-91],
            [190199,synne,leirfall,20-01-1994],
            [123456,Ulrik,weum,10-12-92],
            [181922,zain,Foss,01-11-1996]
            ];

        $expectedResult =  [
            new Student(490199,bjørn,"bordewich gyles",04-11-91),
            new Student(190199,synne,leirfall,20-01-1994),
            new Student(123456,Ulrik,weum,10-12-92),
            new Student(181922,zain,Foss,01-11-1996)
        ];
        
        // Act
        $result = Students::CreateStudents($studentArr);

        $this->assertEquals($expectedResult[0], $result[0]);
    }

    public function testValidateStudentShouldFailOnDuplicateName(): void {
        // Arrange
        $ss =  [new Student(490199,bjørn,"bordewich gyles",04-11-91),
            new Student(190199,synne,leirfall,20-01-1994),
            new Student(123456,Ulrik,weum,10-12-92),
            new Student(181922,zain,Foss,01-11-1996)];

        $n = new Student(490199,trond,bordewich,12-12-79);
        
        $result = Students::CheckStudentUniqueness($ss, $n);
        
        $this->assertEquals("Duplicate student id: 490199", $result);
    }

    public function testStudentNrEquality(): void{
        $studentA = new Student(123456,Ulrik,weum,10-12-92);
        $StudentB = new Student(490199,trond,bordewich,12-12-79);

        $result = $studentA->isEqualTo($studentB);

        $this->assertFalse($result);
    }

    public function testStudentNameEquality(): void{
        $studentA = new Student(490199,Ulrik,weum,10-12-92);
        $studentB = new Student(490199,trond,bordewich,12-12-79);

        $result = $studentA->isEqualTo($studentB);
        $res = $studentA->getStudentnr() === $studentB->getStudentnr();
        $this->assertTrue($res);
    }

    public function testDoesNotCrashOnNull(): void {
        $studentA = new Student(490199,Ulrik,weum,10-12-92);

        $result = $studentA->isEqualTo(null);
        $this->assertFalse($result);
    }

    public function testGetNewStudents(): void {
        $existing = [
            [490199,bjørn,"bordewich gyles","04-11-91"],
            [190199,synne,leirfall,"20-01-1994"],
            [123456,Ulrik,weum,"10-12-92"],
            [181922,zain,Foss,"01-11-1996"],
            [490198,trond,bordewich,"12-12-79"]
            ];

        $newStudents = [
            [490198,trond,bordewich,"12-12-79"], 
            [789654,Petra,Police,"12-04-1983"],
            [181922,zain,Foss,"01-11-1996"]
        ];

        $expected = [
            [new Student(490198,trond,bordewich,"12-12-79")],
            [new Student(789654,Petra,Police,"12-04-1983")],
            
        ];

        $result = Students::CreateSeveralNewStudentsCheckUniqueness($existing, $newStudents);

            $this->assertEquals($expected[0], $result);
    }


}
?>