# php-msqli-better-queries
Write shorter mysqli queries using this code. It automatically prepares queries and shortens queries significantly.

What originally would be:

```		
    $query = $conn->prepare("INSERT INTO articles (uid, username, userID, title, html, images, postTime) VALUES(?, ?, ?, ?, ?, ?, ?)");
		$query->bind_param("ssissss", $uid, $user, $userID, $articleTitle, $htmlContent, $arrToStr, $time);

		$uid = generateKey($conn);
		$user = $_SESSION['username'];
		$userID = $_SESSION['id'];
		$articleTitle = $title;
		$htmlContent = $html;
		$arrToStr = implode(" ", $imgs);
		$time = time();

		$query->execute();
		$query->close();
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
