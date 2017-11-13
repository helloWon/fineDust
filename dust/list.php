<?php
$dbh = new PDO('mysql:host=localhost;dbname=o2', 'root', '');
$stmt = $dbh->prepare('SELECT * FROM dust');
$stmt->execute();
$list = $stmt->fetchAll();
if(!empty($_GET['id'])) {
    $stmt = $dbh->prepare('SELECT * FROM dust WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $id = $_GET['id'];
    $stmt->execute();
    $dust = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <style type="text/css">
            body {
                font-size: 0.8em;
                font-family: dotum;
                line-height: 1.6em;
            }
            header {
                border-bottom: 1px solid #ccc;
                padding: 20px 0;
            }
            nav {
                float: left;
                margin-right: 20px;
                min-height: 1000px;
                min-width:150px;
                border-right: 1px solid #ccc;
            }
            nav ul {
                list-style: none;
                padding-left: 0;
                padding-right: 20px;
            }
            article {
                float: left;
            }
            .value{
                width:500px;
            }
        </style>
    </head>
   
    <body id="body">
        <div>
            <nav>
                <ul>
                    <?php
                    foreach($list as $row) {
                        echo "<li><a href=\"?id={$row['id']}\">".htmlspecialchars($row['time'])."</a></li>";                        }
                    ?>
                </ul>
                <ul>
                    <li><a href="input.php">추가</a></li>
                </ul>
            </nav>
            <article>
                <?php
                if(!empty($dust)){
                ?>
                <h2><?=htmlspecialchars($dust['time'])?></h2>
		<div class="value">
		<?php
		echo "측정값";
		?>               
		    <?=htmlspecialchars($dust['value'])?>
                </div>
		<div class="locate">
		<?php
		echo "위치";
		?>
                    <?=htmlspecialchars($dust['locate'])?>
		</div>
                <div>
                    <a href="modify.php?id=<?=$topic['id']?>">수정</a>
                    <form method="POST" action="process.php?mode=delete">
                        <input type="hidden" name="id" value="<?=$topic['id']?>" />
                        <input type="submit" value="삭제" />
                    </form>
                </div>
                <?php
                }
                ?>
            </article>
        </div>
    </body>
</html>
