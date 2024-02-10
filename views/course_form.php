<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Course</title>
</head>
<body>
    <form action="add_course" method="post">
        <label for="course_code">Course Code:</label>
        <input type="text" id="course_code" name="course_code"><br><br>
        
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name"><br><br>
        
        <label for="exam_count">Exam Count:</label>
        <input type="number" id="exam_count" name="exam_count"><br><br>
        
        <label for="program_code">Program Code:</label>
        <input type="text" id="program_code" name="program_code"><br><br>
        
        <label for="term">Term:</label>
        <input type="text" id="term" name="term"><br><br>
        
        <label for="crn">CRN:</label>
        <input type="text" id="crn" name="crn"><br><br>
        
        <label for="section_code">Section Code:</label>
        <input type="text" id="section_code" name="section_code"><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
