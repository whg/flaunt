<aside>	
	<a href="<?php echo(AROOT)?>"><img src="<?php echo(ROOT . 'admin/images/flauntlogo.png')?>" alt="logo" id="logo"/></a>
	<nav>
		<ul>
			<li id="edit"><span class="link">Edit</span></li>
			<li class="hide">
				<ul>
					<li><a href="<?php echo(AROOT . 'edit.php?name=homepage')?>">Homepage</a></li>
					<?php 
						//not sure why i have to do this...
						$dsn = "mysql:host=".DB_HOST.";dbname=".DB;
						try {
						    $pdo = new PDO($dsn, DB_USER, DB_PASS);
						}
						catch(PDOException $e) {
						    die("Could not connect to the database\n");
						}
						
						$stmt = $pdo->prepare("SELECT name,type FROM pages");
						$stmt->execute();
						$row = $stmt->fetchAll();
						
						foreach($row as $name) {
							if($name[1] !== 'marker') {
								echo('<li><a href="'.AROOT.'edit.php?type='.$name[1]."&name=".$name[0].'">'.$name[0]."</a></li>\n");
							}
						}
					?>
				</ul>
			</li>
			<li><a class="link" href="<?php echo(AROOT. 'add.php')?>">Add</a></li>
			<li><a class="link" href="<?php echo(AROOT. 'delete.php')?>">Delete</a></li>
			<li><a class="link" href="<?php echo(AROOT. 'menu.php')?>">Menu</a></li>
			<li><a class="link" href="<?php echo(AROOT. 'style.php')?>">Style</a></li>
<!-- 			<li><a class="link" href="<?php echo(AROOT. 'upload.php')?>">Upload</a></li> -->
		</ul>

	</nav> 	
</aside>
