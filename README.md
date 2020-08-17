# Better SQLI queries using PHP 
Write shorter mysqli queries using this code. It automatically uses prepared statements for all queries and shortens queries significantly.

What originally would be:

```		
$query = "INSERT INTO table (uid, username, userID, title, content, images, postTime) VALUES(?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssissss", $uid, $user, $userID, $articleTitle, $htmlContent, $arrToStr, $time);

$uid = generateKey($conn);
$user = $_SESSION['username'];
$userID = $_SESSION['id'];
$articleTitle = $title;
$htmlContent = $html;
$arrToStr = implode(" ", $imgs);
$time = time();

$stmt->execute();
$stmt->close();
```

Becomes:

```
$insertValues = array(
    "uid" => generateKey($conn),
    "username" => $_SESSION['username'],
    "userID" => $_SESSION['id'],
    "title" => $title,
    "html" => $html,
    "images" => implode(" ", $imgs),
    "postTime" => time()
);

(new query)->insert("articles", $insertValues);
```

<h1>How to Use the File:</h1>
Include the file by doing:

```
require "database.php"
```
<br>
<b>Note</b>: Change Database connection according to your settings in database.php 
<br>
<h1>SELECT Statement</h1>
Select statement returns to parameters:<br><br>
1)<b>data</b> - mysqli_fetch_assoc data in an array. <br><br>
2)<b>data_num_rows</b> - Number of rows (mysqli_num_rows)<br>><br>

Usage: <br>
       1) select() takes 3 params -> tablename, fields to retrieve and conditions. To specify no conditions use an empty array.<br><br>
       2) Mention where conditions in a multidimensional array. All arrays must have four elements: field name, logical operator, value, Clause (And, OR etc).
       Leave Clause empty for last array or if only one array. <br><br>
       <b>Note:</b> use foreach loop to iterate over data not while loop.<br>
<br>
```
$selectWhere = [
	["id", "<", 31, "AND"],
	["age", ">", 21, "AND"],
	["status", "=", Online", '']
] ;

$data = (new query)->select("users", "id, age", $selectWhere);
$ageFirstPerson = $data['data_array'][0]['age']; 

if($data['data_num_rows'] > 0) {
	foreach($data['data'] as $row) {
		echo $row['username'] . "<br> . $row['id'];
	}
}
```

<h1>Insert Statement</h1>
Insert values using associative array with key as fieldname and value as field value.<br>
Insert takes 2 parameters - tablename and values to insert
<br><br>

```
$insertValues = array(
    "uid" => "@njhd832",
    "username" => "John",
    "userID" => 32,
    "title" => "Hello World",
    "content" => "Some content",
    "post_time" => time()
);

(new query)->insert("articles", $insertValues);
```

<h1>UPDATE Statement</h1>
Update takes 3 parameters table name, set data and where conditions.
<Br>
Here update data is an associative array with field names as key and new values.<br>
<b>Note: </b> WHERE condition is set to default as required.<br>
Update conditions are similar to that of select statement<br><Br>

```
$updateValues = array(
	"uid" => "ws328123";
	"name" => "Salil"
)

$updateWhere [
	["id", ">", 31, "AND"],
	["name", "=", "John", "OR"],
	["LENGTH(uid)", ">", "10", '']
];

(new query)->update("users", $updateValues, $udpateWhere);

```

<br><br>
<h1>DELETE statement</h1>
DELETE takes 2 parameters: tablename and where conditions similar as above. <br>
<b>Note: </b> WHERE condition is set to default as required.

```
$deleteWhere = [
	["uid", "=", 1234, "AND"],
	["name", "=", "Salil", "OR"],
	["id", ">", 44, '']
];

(new query)->delete("users", $deleteWhere);
```
<br><br>
<h1>Contributions </h1> <br>
Salil Mittal
