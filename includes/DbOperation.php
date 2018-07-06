<?php

class DbOperation
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    //Method to create a new user
    function registerUser($fname,$lname, $email, $pass)
    {
        if (!$this->isUserExist($email)) {
            $password = md5($pass);
            $stmt = $this->con->prepare("INSERT INTO users_91375 (fname,lname, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fname,$lname, $email, $pass);
            if ($stmt->execute())
                return USER_CREATED;
            return USER_CREATION_FAILED;
        }
        return USER_EXIST;
    }

    //Method for user login
    function userLogin($email, $pass)
    {
        $password = md5($pass);
        $stmt = $this->con->prepare("SELECT id FROM users_91375 WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;

    }
    //Method to get user by email
    function getUserByEmail($email)
    {
        $stmt = $this->con->prepare("SELECT id, fname, lname, email FROM users_91375 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $fname, $lname, $email);
        $stmt->fetch();
        $user = array();
        $user['id'] = $id;
        $user['fname'] = $fname;
        $user['lname'] = $lname;
        $user['email'] = $email;


        return $user;
    }

    //Method to add session
    function addSession($session_id, $date, $gym_location, $exercise_type, $reps, $id)
    {
        $stmt = $this->con->prepare("INSERT INTO sessions_91375 (session_id,date, gym_location,exercise_type, reps, id) VALUES (?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("iiss", $session_id, $date, $gym_location, $exercise_type, $reps, $id);
        if ($stmt->execute())
            return true;
        return false;
    }

    //Method to update profile of user
    function updateProfile($id, $fname, $lname, $email, $pass)
    {
        $password = md5($pass);
        $stmt = $this->con->prepare("UPDATE users_91375 SET fname = ?,lname = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $fname, $lname,$email, $password, $id);
        if ($stmt->execute())
            return true;
        return false;
    }

    //I'VE REACHED HERE
    //Method to get sessions of a particular user; the functional($id) is the user_id
    function getMessages($id)
    {
//        $stmt = $this->con->prepare("SELECT sessions_91375.session_id, (SELECT * FROM users_91375 WHERE users_91375.id = sessions_91375.id) as `from`, (SELECT users.name FROM users WHERE users.id = messages.to_users_id) as `to`, messages.title, messages.message, messages.sentat FROM messages WHERE messages.to_users_id = ?;");
//        $stmt->bind_param("i", $id);
//        $stmt->execute();
//        $stmt->bind_result($session_id, $date, $gym_location, $exercise_type, $reps, $id);
//
//        $messages = array();
//
//        while ($stmt->fetch()) {
//            $temp = array();
//
//            $temp['id'] = $id;
//            $temp['from'] = $from;
//            $temp['to'] = $to;
//            $temp['title'] = $title;
//            $temp['message'] = $message;
//            $temp['sent'] = $sent;
//
//            array_push($messages, $temp);
//        }
//
//        return $messages;
    }


    //Method to get all instructors
    function getAllInstructors(){
        $stmt = $this->con->prepare("SELECT id, name, gender, email, contacts FROM instructors_91375");
        $stmt->execute();
        $stmt->bind_result($id, $name, $gender, $email, $contacts);
        $instructors = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['name'] = $name;
            $temp['gender'] = $gender;
            $temp['email'] = $email;
            $temp['contacts'] = $contacts;
            array_push($instructors, $temp);
        }
        return $instructors;
    }
//Method to get all sessions
    function getAllSessions($id){
        $stmt = $this->con->prepare("SELECT session_id, date, gym_location, exercise_type, reps, id FROM sessions_91375 WHERE sessions_91375.id=users_91375.id");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($session_id, $date, $gym_location, $exercise_type, $reps, $id);
        $sessions = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['session_id'] = $session_id;
            $temp['date'] =$date;
            $temp['gym_location'] = $gym_location;
            $temp['exercise_type'] = $exercise_type;
            $temp['reps'] = $reps;

            array_push($sessions, $temp);
        }
        return $sessions;
    }
    //Method to check if email already exist
    function isUserExist($email)
    {
        $stmt = $this->con->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}