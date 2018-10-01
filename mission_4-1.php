<?php
//データベースへの接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

//データベース内にテーブルを作成する
$sql="CREATE TABLE mission4"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name CHAR(32),"
."comment TEXT,"
."date DATETIME,"
."pass CHAR(32)"
.");";
$stmt=$pdo->query($sql);


//投稿を編集する
if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])){
	if(!empty($_POST['edit_num'])){
		$id=$_POST['edit_num'];
		$name=$_POST['name'];
		$comment=$_POST['comment'];
		$date=date("Y/m/d H:i:s");
		$pass=$_POST['pass'];
		//入力データの編集
		$sql="update mission4 set name='$name',comment='$comment',date='$date',pass='$pass' where id=$id";
		$result=$pdo->query($sql);
	}
	//編集番号が空の時
	else{
		//データの入力
		$sql=$pdo->prepare("INSERT INTO mission4(name,comment,date,pass) VALUES(:name,:comment,:date,:pass)");
		$sql->bindParam(':name', $name, PDO::PARAM_STR);
		$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql->bindParam(':date', $date, PDO::PARAM_STR);
		$sql->bindParam(':pass', $pass, PDO::PARAM_STR);
	
		$name=$_POST['name'];
		$comment=$_POST['comment'];
		$date=date("Y/m/d H:i:s");
		$pass=$_POST['pass'];
		$sql->execute();
	}
}

//投稿削除
if(!empty($_POST['delete'])){
	$id=$_POST['delete'];
	$sql="SELECT*FROM mission4 where id = $id";
	$stmt=$pdo -> query($sql);
	$result=$stmt -> fetch();
	
	if($_POST['password1']==$result['pass']){
		$sql="delete from mission4 where id = $id";
		$result=$pdo -> query($sql);
	}
	else{
		echo "パスワードが間違っています";
	}
}

//投稿を編集
if(!empty($_POST['edit'])){
	$id=$_POST['edit'];
	$sql="SELECT*FROM mission4 where id = $id";
	$stmt=$pdo -> query($sql);
	$result=$stmt -> fetch();

	if($_POST['password2']==$result['pass']){
	$data0=$result['id'];
	$data1=$result['name'];
	$data2=$result['comment'];
	$data3=$result['pass'];
	}
	else{
	echo "パスワードが間違っています";
		}
}
?>

<html>
<head>
<meta charset="UTF-8">
<title>WEB掲示板</title>
</haed>
<body>
<form action="mission_4-1.php" method="post">
<input type="text" name="name" value="<?php echo $data1; ?>" placeholder="名前"><br>
<input type="text" name="comment" value="<?php echo $data2; ?>" placeholder="コメント"><br>
<input type="text" name="pass" value="<?php echo $data3; ?>" placeholder="パスワード">
<input type="hidden" name="edit_num" value="<?php echo $data0; ?>">
<input type="submit" value="送信"><br>
<br>
<input type="text" name="delete"  placeholder="削除対象番号"><br>
<input type="text" name="password1"  placeholder="パスワード">
<input type="submit" value="削除"><br>
<br>
<input type="text" name="edit"  placeholder="編集対象番号"><br>
<input type="text" name="password2"  placeholder="パスワード">
<input type="submit" value="編集">

</form>
</body>
</html>	

<?php
//投稿を表示する
$sql="SELECT*FROM mission4";
$results=$pdo->query($sql);
if(!empty($results)){
	foreach($results as $row){

		echo $row['id']." ";
		echo $row['name']." ";
		echo $row['comment']." ";
		echo $row['date']."<br>";
	}

}
?>