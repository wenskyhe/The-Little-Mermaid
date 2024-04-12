# Aquademia
### An e-Learning Platform

Aquademia is an online leanring management system boasting the following features:

- USERS: can login/signup, update user details
- ADMIN: can create courses 
- ADMIN: can accept students into a course
- TEACHER: can create different assignments (quizzes and essays)
- STUDENT: can enroll in the course
- STUDENT: can submit assignments
- TEACHER: can grade assignments 
- STUDENT: can see grades

## Installation instructions
### Running the website:
1. Download xampp and install it in the C drive
2. Save the project in the C:\xampp\htdocs
3. To access the project, open your browser and type ‘localhost’ into the address bar.

### Setting up the database
1. With xampp now installed, in your browser’s address bar type “localhost/phpmyadmin”
2. On the left where it shows the list of tables, click on “New” to create a new table and name it “aquademia”
3. Import the ‘aquademia.sql’ file into phpmyadmin. This will now create all the tables necessary for the project to function


### Running the tests
Composer must be installed to run the tests. It can be downloaded from here.
