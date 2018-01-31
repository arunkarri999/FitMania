 <?php
//make sure you have a user name fit and password fit in your phpmyadmin

$server = "localhost";
$userName = "root";
$password = "";

// connect to db
$connection = mysqli_connect($server, $userName, $password);

// die if connection is invalid
if (!$connection){
  die("Connection to DB failed :" . mysqli_connect_error() . "</br>");
}

echo  "Successfully connected to DB!" . "</br>";

//Dropping old database
$sql = "DROP DATABASE IF EXISTS fit_mania";
if ($connection->query($sql) === TRUE){
    echo "Database dropped successfully!" . "</br>";
} else {
    echo "Error droppping database!" . $connection->error . "</br>" ;
}

//Create new database
$sql  = "CREATE DATABASE fit_mania CHARACTER SET utf8 COLLATE utf8_general_ci";
if ($connection->query($sql) === TRUE){
    echo "Database created successfully!" . "</br>";
} else {
    echo "Error creating database!" . $connection->error . "</br>" ;
}

//Grant access to the db user
//$sql  = "GRANT ALL ON fit_mania.* TO 'fit'@'localhost';";
$sql = "GRANT ALL ON fit_mania.* TO 'fit'@'localhost' IDENTIFIED BY 'fit'";
if ($connection->query($sql) === TRUE){
    echo "Access granted successfully!" . "</br>";
} else {
    echo "Error granting access database!" . $connection->error . "</br>";
}

//select fit_mania database
mysqli_select_db($connection,"fit_mania");

//Tables-Begin
// Add your tables here

//users
$sql = "CREATE TABLE users (
id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
full_name varchar(100) DEFAULT NULL,
first_name varchar(50) DEFAULT NULL,
last_name varchar(50) DEFAULT NULL,
image_url varchar(2000) DEFAULT NULL,
email varchar(50) NOT NULL,
gender bit(1) DEFAULT NULL,
weight float DEFAULT NULL,
height float DEFAULT NULL,
birthday date DEFAULT NULL,
dietary varchar (50)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "users TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating users TABLE!" . $connection->error . "</br>";
}

//recipes
$sql = "CREATE TABLE recipes (
id int NOT NULL AUTO_INCREMENT,
name varchar(50) NOT NULL,
serving int NOT NULL,
description text NOT NULL,
image_url varchar(100) NOT NULL,
calories float DEFAULT NULL,
tag varchar(100) NOT NULL,
meal varchar(50) NOT NULL,
user_id int NULL,
is_approved bit NOT NULL DEFAULT 1,
PRIMARY KEY (id),
KEY user_id (user_id),
CONSTRAINT recipes_users_fk FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "recipes TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating recipes TABLE!" . $connection->error . "</br>";
}

//ingredients
$sql = "CREATE TABLE ingredients (
id int NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
unit varchar(20) NOT NULL,
amount float NOT NULL,
recipe_id int NOT NULL,
amount_gram int,
ndbno int,
PRIMARY KEY (id),
KEY recipe_id (recipe_id),
CONSTRAINT ingredients_recipes_fk FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "ingredients TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating ingredients TABLE!" . $connection->error . "</br>";
}

// $sql = "ALTER TABLE nutrients ADD FULLTEXT INDEX index_full_text_description (description);";
//  if ($connection->query($sql) === true) {
//      echo "nutrients TABLE Description-Column-Indexed successfully!" . "</br>";
//  } else {
//      echo "Error Indexing nutrients TABLE!" . $connection->error . "</br>";
//  }


//user-ratings
$sql = "CREATE TABLE user_ratings (
id int NOT NULL AUTO_INCREMENT,
rating int NOT NULL,
user_id int NOT NULL,
recipe_id int NOT NULL,
PRIMARY KEY (id),
KEY user_id (user_id),
KEY recipe_id (recipe_id),
CONSTRAINT user_ratings_users_fk FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
CONSTRAINT user_ratings_recipes_fk FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "user-ratings TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating user-ratings TABLE!" . $connection->error . "</br>";
}

//recipe_hits
$sql = "CREATE TABLE recipe_hits (
id int PRIMARY KEY AUTO_INCREMENT,
recipe_id int NOT NULL,
created_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "recipe_hits TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating recipe_hits TABLE!" . $connection->error . "</br>";
}

//units of measure
$sql = "CREATE TABLE units (
id int PRIMARY KEY AUTO_INCREMENT,
name varchar(50) NOT NULL,
shortname varchar(10),
coefficient decimal(10,4)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "units TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating units TABLE!" . $connection->error . "</br>";
}

//favorites table
$sql = "CREATE TABLE favorites (
 id int(11) NOT NULL AUTO_INCREMENT,
 user_id int(11) NOT NULL,
 recipe_id int(11) NOT NULL,
 PRIMARY KEY (id),
 KEY user_id (user_id),
 KEY recipe_name (`recipe_id`),
 CONSTRAINT favorites_users_fk FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
 CONSTRAINT favorites_recipes_fk FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "favorites TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating favorites TABLE!" . $connection->error . "</br>";
}


//recipes full text index addition
$sql = "ALTER TABLE recipes
ADD FULLTEXT INDEX index_full_text (name, tag)";
if ($connection->query($sql) === true) {
    echo "recipes TABLE Indexed successfully!" . "</br>";
} else {
    echo "Error Indexing ingredients TABLE!" . $connection->error . "</br>";
}

$sql = "ALTER TABLE recipes
ADD FULLTEXT INDEX index_single_full_text_name (name);";
 if ($connection->query($sql) === true) {
     echo "recipes TABLE Name-Single-Column-Indexed successfully!" . "</br>";
 } else {
     echo "Error Indexing ingredients TABLE!" . $connection->error . "</br>";
 }

$sql = "ALTER TABLE recipes
ADD FULLTEXT INDEX index_single_full_text_tag (tag);";
 if ($connection->query($sql) === true) {
     echo "recipes TABLE Tag-Single-Column-Indexed successfully!" . "</br>";
 } else {
     echo "Error Indexing ingredients TABLE!" . $connection->error . "</br>";
 }
//Tables-End

// Add test data here

//goal table
$sql = "CREATE TABLE goal (
 id int(11) NOT NULL AUTO_INCREMENT,
 user_id int(11) NOT NULL,
 active int(1),
 goal_weight float,
 begin date,
 end date,
 dietary varchar(50),
 status int(1),
 calories float,
 PRIMARY KEY (id),
 KEY user_id (user_id),
 CONSTRAINT goal_users_fk FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "goal TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating goal TABLE!" . $connection->error . "</br>";
}


//Plan Meals TABLE

$sql = " CREATE TABLE plan_meals (
id int(6) PRIMARY KEY AUTO_INCREMENT,
goal_id int(11),
day int(6),
meal smallint,
recipe_id int
) ENGINE = MyISAM;
";
if ($connection->query($sql) === true) {
    echo "plan meals TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating plan meals TABLE!" . $connection->error . "</br>";
}
//history table
$sql = "CREATE TABLE history (
id int(11) NOT NULL AUTO_INCREMENT,
recipe_id int NOT NULL,
user_id int NOT NULL,
time DATETIME NOT NULL,
PRIMARY KEY (id),
KEY user_id (user_id),
KEY recipe_id (recipe_id),
CONSTRAINT history_users_fk FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
CONSTRAINT history_recipes_fk FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
 if ($connection->query($sql) === true) {
     echo "history TABLE CREATED successfully!" . "</br>";
 } else {
     echo "Error creating history TABLE!" . $connection->error . "</br>";
 }

//comments table
$sql = "CREATE TABLE comments (
 id int(11) NOT NULL AUTO_INCREMENT,
 comment_text text NOT NULL,
 comment_date date NOT NULL,
 user_id int(11) NOT NULL,
 recipe_id int(11) NOT NULL,
 PRIMARY KEY (id),
 KEY user_id (user_id),
 KEY recipe_id (recipe_id),
 CONSTRAINT comments_recipes_fk FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON UPDATE CASCADE,
 CONSTRAINT comments_users_fk FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($connection->query($sql) === true) {
    echo "comments TABLE CREATED successfully!" . "</br>";
} else {
    echo "Error creating comments TABLE!" . $connection->error . "</br>";
}

// ------------------------------------------------------------------------------------------------------------ //
//All Test Data Start Here

//Admin User
$sql = "INSERT INTO users (full_name, email) VALUES ('Admin', 'info@fitmania.com')";
if ($connection->query($sql) === true) {
    echo "user DATA INSERTED CREATED successfully!" . "</br>";
    $user_id = $connection->insert_id;
} else {
    echo "Error INSERTING user DATA!" . $connection->error . "</br>";
}

//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Caesar Salad Supreme',
serving = 2,
description = '
    - Mince 3 cloves of garlic, and combine in a small bowl with mayonnaise, anchovies, 2 tablespoons of the Parmesan cheese, Worcestershire sauce, mustard, and lemon juice. Season to taste with salt and black pepper. Refrigerate until ready to use.
    - Heat oil in a large skillet over medium heat. Cut the remaining 3 cloves of garlic into quarters, and add to hot oil. Cook and stir until brown, and then remove garlic from pan. Add bread cubes to the hot oil. Cook, turning frequently, until lightly browned. Remove bread cubes from oil, and season with salt and pepper.
    - Place lettuce in a large bowl. Toss with dressing, remaining Parmesan cheese, and seasoned bread cubes.
',
image_url = '/cheza/code/Img/recipes/ceaser_salad.jpg',
calories = 250,
tag = 'vegan, pizza',
meal  = 'lunch,dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('garlic, peeled, divided',17,6,1),
      ('mayonnaise', 5, 0.75,1),
      ('anchovy fillets, minced', 17,5 ,1),
      ('grated Parmesan cheese, divided', 2,6 ,1),
      ('Worcestershire sauce ', 1, 1,1),
      ('Dijon mustard', 1, 1,1),
      ('lemon juice', 2, 1 ,1),
      ('salt', 15,0 ,1),
      ('ground black pepper', 15,0 ,1),
      ('olive oil', 5, 0.25,1),
      ('day-old bread, cubed', 5, 4,1),
      ('romaine lettuce, torn into bite-size pieces', 17, 1, 1)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//UNITS TEST DATA
$sql = "INSERT INTO units (name,shortname, coefficient)
     VALUES
     ('teaspoon','tsp.', 5),
     ('tablespoon',' tbsp.' ,15),
     ('fluid ounce(US)','fl oz)',29),
     ('gill(US)','1/2 cup', 118),
     ('cup(US), diced','c',220),
     ('pint(US)','fl pt',473),
     ('quart(US)','fl qt',946),
     ('gallon(US)','gal',3785),
     ('ml','cc',1),
     ('l','liter',1000),
     ('dl','deciliter',100),
     ('pound','lb',453),
     ('ounce international','oz',28.35),
     ('mg','milligram',0.001),
     ('g','gram',1),
     ('kg','kilogram',1000),
     ('pcs','pieces',0),
     ('others','others',0),
     ('cup(US), melted','c',250),
     ('cup(US), shredded','c',200)
";
if($connection->query($sql) === TRUE){
  echo "Units DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING units DATA!" . $connection->error . "</br>" ;
}



// EXAMPLES
// Tables
// $sql = "  CREATE TABLE food (
//       id INT AUTO_INCREMENT PRIMARY KEY,
//       name VARCHAR (50),
//       color VARCHAR (10),
//       cal VARCHAR (10)
// ) ENGINE = MyISAM" ;
//
// if($connection->query($sql) === TRUE){
//   echo "food TABLE CREATED successfully!" . "</br>";
// }else {
//   echo "Error creating food TABLE!" . $connection->error . "</br>";
// }

// DATA
// EXAMPLE 1
// $sql = "INSERT INTO food(name, color, cal) VALUES ('apple','red','50') ";
// if($connection->query($sql) === TRUE){
//   echo "food DATA INSERTED successfully!";
// }else {
//   echo "Error inserting food DATA!" . $connection->error ;
// }

// EXAMPLE 2
// $sql = "INSERT INTO food SET
// name = 'apple',
// color = 'red',
// cal = '50';";
// if($connection->query($sql) === TRUE){
//   echo "food DATA INSERTED successfully!";
// }else {
//   echo "Error inserting food DATA!" . $connection->error ;
// }

//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Home-style pork curry with cauliflower rice',
serving = 4,
description = '
    - Tip the pork into a bowl and stir in the curry powder and vinegar. Set aside. Heat the oil in a heavy-based pan and fry the onion and ginger for 10 mins, stirring frequently, until golden. Tip in the pork mixture and fry for a few mins more. Remove the pork and set aside. Stir in the toasted spices, then tip in the tomatoes, lentils and aubergine, and crumble in the stock cube. Cover and leave to simmer for 40 mins, stirring frequently, until the aubergine is almost cooked. If it starts to look dry, add a splash of water. Return the pork to the pan and cook for a further 10-20 mins until the pork is cooked and tender.
    - Just before serving, cut the hard core and stalks from the cauliflower and pulse the rest in a food processor to make grains the size of rice. Tip into a heatproof bowl, cover with cling film, then pierce and microwave for 7 mins on High – there is no need to add any water. Stir in the coriander and serve with the curry. For spicier rice, add some toasted cumin seeds.
',
image_url = '/cheza/code/Img/recipes/1.jpg',
calories = 309,
tag = 'pork',
meal  = 'lunch,dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('lean pork fillet (tenderloin), cubed',15,425,2),
      ('Madras curry powder', 2, 2,2),
      ('red wine vinegar', 2,2 ,2),
      ('rapeseed oil', 2,1 ,2),
      ('large onion,finely chopped', 17, 1,2),
      ('finely shredded ginger', 2, 2,2),
      ('fennel,toasted in a pan then crushed', 1, 1 ,2),
      ('cumin,toasted in a pan then crushed', 'tsp ',1 ,2),
      ('can chopped tomatoes', 15,400 ,2),
      ('red lentils', 2, 2,2),
      ('pack baby aubergine,quartered', 15, 350,2),
      ('reduced-salt vegetable stock cube', 17, 1,2),
	  ('medium cauliflower',17,1,2),
	  ('good handful coriander,chopped',' ',0,2),
	  ('cumin seeds,toasted','optional',0,2)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Carrot & ginger immune-boosting soup',
serving = 1,
description = '
    - Peel and chop the carrots and put in a blender with the ginger, turmeric, cayenne pepper, wholemeal bread, soured cream and vegetable stock. Blitz until smooth. Heat until piping hot. Swirl through some extra soured cream, or a sprinkling of cayenne, if you like.

',
image_url = '/cheza/code/Img/recipes/2.jpg',
calories = 223,
tag = 'carrot,ginger',
meal  = 'soup',
user_id = $user_id,
is_approved = 0
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('large carrots',17,3,3),
      ('grated ginger', 2, 1,3),
      ('turmeric', 2,1 ,3),
      ('wholemeal bread', 15,20 ,3),
      ('soured cream', 2, 1,3),
      ('vegetable stock', 9, 200,3),
      ('cayenne peppera', 'pinch', 1 ,3)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Beef & stout stew with carrots',
serving = 4,
description = '
    - Heat oven to 160C/140C fan/gas 3. Heat the oil in large lidded casserole dish, brown the meat really well in batches, then set aside. Add the onion and carrots to the dish, give them a good browning, then scatter over the flour and stir. Tip the meat and any juices back into the dish and give it all a good stir. Pour over the Guinness and crumble in the stock cube. Season the stew with salt, pepper and a pinch of sugar. Tuck in the herbs and bring everything to a simmer.
    - Cover with a lid and place in the oven for about 2½ hrs until the meat is really tender. The stew can now be chilled and frozen for up to 3 months – defrost completely before reheating until piping hot. Leave the stew to settle a little, then serve with Creamy parsnip mash for a true celebration of winter vegetables.


',
image_url = '/cheza/code/Img/recipes/3.jpg',
calories = 562,
tag = 'beef',
meal  = 'dinner,lunch',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('vegetable oil',2,2,4),
      ('stewing beef', 16, 1,4),
      ('onion', 17,1 ,4),
      ('carrot', 17,10 ,4),
      ('plain flour', 2, 2,4),
      ('can Guinness', 9, 500,4),
      ('beef stock cube', 17, 1 ,4),
	  ('sugar','pinch',1,4),
	  ('bay leaves',17,3,4),
	  ('big thyme sprig',17,1,4),
	  ('creamy parsnip mash','optional',0,4)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Cardamom & peach quinoa porridge',
serving = 2,
description = '
    - Put the quinoa, oats and cardamom pods in a small saucepan with 250ml water and 100ml of the almond milk. Bring to the boil, then simmer gently for 15 mins, stirring occasionally.
    - Pour in the remaining almond milk and cook for 5 mins more until creamy.
    - Remove the cardamom pods, spoon into bowls or jars, and top with the peaches and maple syrup.


',
image_url = '/cheza/code/Img/recipes/4.jpg',
calories = 231,
tag = 'porridge',
meal  = 'breakfast',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('quinoa',15,75,5),
      ('porridge oats', 15, 25,5),
      ('cardamom pods', 17,4 ,5),
      ('unsweetend almond milk', 9,250,5),
      ('ripe peach', 17, 2,5),
      ('maple syrup', 2, 1,5)
";
if($connection->query($sql) === TRUE) {
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
} else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Roasted cauli-broc bowl with tahini houmous',
serving = 2,
description = '
    - The night before, heat oven to 200C/180C fan/gas 6. Put the cauliflower and broccoli in a large roasting tin with the oil and a sprinkle of flaky sea salt. Roast for 25-30 mins until browned and cooked. Leave to cool completely.
    - Build each bowl by putting half the quinoa in each. Lay the slices of beetroot on top, followed by the spinach, cauliflower, broccoli and walnuts. Combine the tahini, houmous, lemon juice and 1 tbsp water in a small pot. Before eating, coat in the dressing. Serve with the lemon wedges.

',
image_url = '/cheza/code/Img/recipes/5.jpg',
calories = 533,
tag = 'salad',
meal  = 'lunch',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('pack cauliflower & broccoli florets',15,400,6),
      ('olive oil', 2, 2,6),
      ('quinoa', 15,250,6),
      ('beetroot', 17,2,6),
      ('baby spinach', 'large handful', 1,6),
      ('walnuts', 17, 10,6),
	  ('tahini',2,2,6),
      ('houmous',1,3,6),
	  ('lemon',17,1,6)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Smashed cucumber salad',
serving = 1,
description = '
    - Smash cucumber with a cleaver, cut into thick slices, and transfer to a plate. Crush and mince garlic and sprinkle on top of cucumber. Season with salt and sugar. Drizzle sesame oil on top. Sprinkle diced bell pepper on top. Lightly toss salad and enjoy as a light snack or side dish with Asian fare!

',
image_url = '/cheza/code/Img/recipes/Smashed cucumber salad.jpg',
calories = 160,
tag = 'chinese, salad, vegetarian',
meal  = 'breakfast, lunch, dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('cucumber',17,0.5,7),
      ('garlic (minced)', 'cloves', 1,7),
      ('salt', 1,0.5,7),
      ('sugar', 1,0.75,7),
      ('sesame oil', 1,0.5,7),
      ('red bell pepper(dices)', 2, 1,7)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Sweet rice balls with black sesame',
serving = 1,
description = '
    - the meal is for the chinese new year
    - Place the rice flour into a large bowl. First, whisk in the hot water. Then, add the cold water and mix well to combine. Using your hands, form dough into a ball. Cover and set aside.
	- In a grease-free pan, toast peanuts and black sesame until lightly roasted. Transfer to a food processor and finely grind. Then, place into a small bowl and add confectioner’s sugar and maple syrup. Mix until a sticky paste forms.
	- Place filling onto a cutting board and divide into four equal portions. Roll each portion into a small log and cut into hazelnut-sized pieces.
	- Lightly cover hands with rice flour. Take a small portion of the dough (approx. 1 tablespoon) and form a small dough round. Place filling in the center and wrap dough around to form a ball. Cover with rice flour.
    - Add water to a large saucepan and bring to a boil. Place balls in boiling water and reduce heat. Immediately stir to avoid sticking. Cover and allow to steep for approx. 3 – 5 min. until dumplings rise to the surface. Serve with cooking water.

',
image_url = '/cheza/code/Img/recipes/Sweet rice balls with black sesame.jpg',
calories = 480,
tag = 'chinese, dessert, vegetarian, sweet, nuts',
meal  = 'breakfast, lunch, dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('glutinous rice flour',15,100,8),
      ('water (hot)', 9, 75,8),
      ('water (cold)', 9,12,8),
      ('peanuts', 15,15,8),
      ('black sesame', 15,7,8),
      ('confectioner sugar', 9, 12,8),
	  ('maple syrup',9,12,8)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Slow-cooker beef Bourguignon',
serving = 3,
description = '
    - slow cooking and for weekend night dinner
    - Cut beef into large cubes. Toss beef in flour, salt, and pepper; set aside. Slice bacon into strips, chop onion and carrot, mince garlic, and cube potatoes; if shallots are large, halve them.
	- Fry bacon until crisp. Set aside to drain on paper towel-lined plate. Add beef to skillet and brown all sides well. Transfer beef and bacon to slow cooker along with potatoes and shallots. Brown carrot, onion, and garlic in same skillet and add to slow cooker, as well.
	- Add stock, tomato paste, thyme, bay leaves, and wine to slow cooker. Cover and cook on low for 6 – 8 hours.
    - Meanwhile, clean and slice mushrooms, then sauté them in butter until soft and golden brown. Add to slow cooker about 1 hour before serving.
	- When almost ready to serve, boil pasta, according to package instructions, in salted water. If sauce is too thin, remove lid and turn slow cooker to high; sift in a little bit of flour, a tablespoon at a time, and stir to combine until thickened. Serve stew over tagliatelle and enjoy!

',
image_url = '/cheza/code/Img/recipes/Slow-cooker beef Bourguignon.jpg',
calories = 962,
tag = 'French, slow cooking, main',
meal  = 'dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('beef rump or chuck',15,1500,9),
      ('bacon', 'strips', 6,9),
      ('large carrot', 15,100,9),
      ('Onion', 17,1,9),
      ('garlic', 'cloves',3,9),
      ('shallots or pearl onions', 15, 200,9),
	  ('potatoes',15,250,9),
	  ('beef stock',9,240,9),
	  ('tomato paste',2,1,9),
	  ('thyme','strips',3,9),
	  ('bay leaves',17,2,9),
	  ('full-bodied red wine, like Burgundy or Bordeaux',9,475,9),
	  ('cremini mushrooms',15,300,9),
	  ('butter',15,30,9),
	  ('tagliatelle',15,500,9),
	  ('flour, plus more for thickening',2,3,9),
	  ('salt',1,1.5,9),
	  ('pepper',1,0.5,9)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Rustic burger',
serving = 1,
description = '
    - Easy Hamburger
    - Wash and slice tomato and cucumber.
	- Add ground beef, Dijon mustard, and ketchup to a mixing bowl. Season with salt and pepper and mix together until combined. Divide in half and form two burger patties with your hands.
	- Heat oil in a pan for approx. 2 min. and fry burger patties on both sides over medium-high heat for approx. 4 min., or until cooked to desired doneness.
	- With the help of a cake ring, cut out two circles approx. the size of the burger patties from each slice of bread. They will be used as buns later. Heat oil in a frying pan over medium-high heat and toast bread on both sides for approx. 2 min., or until crisp.
	- Top rye bread slices with mayonnaise, burger patties, and slices of tomato and cucumber. Enjoy!

',
image_url = '/cheza/code/Img/recipes/Rustic burger.jpg',
calories = 501,
tag = 'Beef, roasting, burger',
meal  = 'dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('ground beef',15,250,10),
	  ('tomato',17,1,10),
	  ('cucumber',17,0.33,10),
	  ('Dijon mustard',1,2,10),
	  ('ketchup',1,2,10),
	  ('salt',1,0.5,10),
	  ('pepper','pinch',1,10),
	  ('vegetable oil(divided)',2,2,10),
	  ('rye bread','slices',2,10)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//RECIPE's TEST DATA
$sql = "INSERT INTO recipes SET
name = 'Roasted chicken in honey-ginger-soy-sauce',
serving = 1,
description = '
    - Mix soy sauce, vinegar, honey. The overall taste should be led by sourness, then saltiness and sweetness to mold everything together.
	- Lay chicken breast in the baking dish. Important! The size of the dish should perfectly fit the breast’s size so it will be covered with the sauce when we pour it in.
	- Put ginger (chopped) on top of the chicken breast. Pour in the sauce. Bake at 180°C/350°F for approx. 40 min.
	- Boil the udon noodles. Follow the package instructions (no more than 10 min). When the udon noodles are cooked, rinse them with cold water.
	- Put noodles on a dish first, then chicken on top, and pour the juice we get from roasting the chicken over the dish. Sprinkle with parsley.

',
image_url = '/cheza/code/Img/recipes/Roasted chicken in honey-ginger-soy-sauce.jpg',
calories = 347,
tag = 'Roasting, chicken, Japanese, sweet',
meal  = 'dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('chicken breast',17,1,11),
	  ('soy sauce',2,2,11),
	  ('rice vinegar',2,1,11),
	  ('honey',1,1,11),
	  ('fresh ginger',2,1,11),
	  ('udon noodles',15,100,11),
	  ('parsley',15,20,11)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}



//recipe12
$sql = "INSERT INTO recipes SET
name = 'Smoked paprika paella with cod & peas',
serving = 2,
description = '
    - Heat the oil in a non-stick frying pan over a medium-high heat and fry the onion and garlic for a couple of mins to soften. Add the rice and spices, stir briefly, then pour in the bouillon and add the pepper. Cover the pan, reduce the heat and leave to simmer for 20 mins. Stir in the courgette, cover and cook for 10 mins more.
	- Add the peas and cod, cover the pan and cook for 10 mins more until the rice is cooked and the liquid has been absorbed. Toss with the parsley and serve with lemon wedges.

',
image_url = '/cheza/code/Img/recipes/smoked-paprika-paella-with-cod-peas.jpg',
calories = 481,
tag = 'seafood, rice',
meal  = 'dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('rapeseed oil',1,1,12),
	  ('onion',15,50,12),
	  ('garlic cloves',15,5,12),
	  ('brown basmati rice',15,100,12),
	  ('turmeric',1,1,12),
	  ('smoked paprika',1,1,12),
	  ('reduced-salt vegetable bouillon',9,500,12),
	  ('red pepper',15,5,12),
	  ('courgette',15,20,12),
	  ('frozen peas',15,125,12),
	  ('Atlantic cod',15,300,12),
	  ('parsley',15,5,12),
	  ('lemon',15,10,12)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe13
$sql = "INSERT INTO recipes SET
name = 'Herby lamb fillet with caponata',
serving = 2,
description = '
    - Slice 2 of the garlic cloves for the caponata, finely grate the other for the lamb and set aside. Heat the oil for the caponata in a wide pan, add the onion and fry for 5 mins to soften. Tip in the aubergine and cook, stirring, for 5 mins more. Add the passata and pepper with the olives, capers, rosemary and balsamic vinegar, then cover and cook for 15 mins, stirring frequently.
	- Meanwhile, heat oven to 190C/170C fan/ gas 5. Boil the potatoes for 10 mins, then drain. Mix the grated garlic with the rosemary and some black pepper, then rub all over the lamb. Toss the potatoes in the oil with some more black pepper, place in a small roasting tin with the lamb and roast for 15-20 mins. Meanwhile, wilt the spinach in the microwave or in a pan, and squeeze to drain any excess liquid.
	- Stir the garlic into the caponata and serve with the lamb, either whole or sliced, rolled in parsley if you like, with the potatoes and spinach.
',
image_url = '/cheza/code/Img/recipes/herby-lamb-caponata.jpg',
calories = 483,
tag = 'lamb, potatoes, vegetarian',
meal  = 'lunch',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('garlic cloves',15,10,13),
	  ('rapeseed oil',1,2,13),
	  ('red onion',15,15,13),
	  ('aubergine',15,50,13),
	  ('carton passata',15,500,13),
	  ('green pepper',17,5,13),
	  ('Kalamata olives',15,10,13),
	  ('capers',1,2,13),
	  ('chopped rosemary',1,1,13),
	  ('balsamic vinegar',1,1,13),
	  ('new potatoes',15,100,13),
	  ('chopped rosemary',1,1,13),
	  ('lean lamb loin fillet',15,250,13),
	  ('baby spinach',15,240,13),
	  ('rapeseed oil',1,1,13)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}



//recipe14
$sql = "INSERT INTO recipes SET
name = 'Turkey & coriander burgers with guacamole',
serving = 4,
description = '
    - Mix the mince, Worcestershire sauce, breadcrumbs, half each of the coriander and onion, and some seasoning until combined. Form into 4 burgers, then chill until ready to cook.
	- To make the guacamole, mash the avocado with the remaining coriander and onion, the chilli and lime juice, and season.
	- Heat a griddle pan or barbecue until hot. Griddle the rolls, cut-side down, for 1 min, then keep warm. Brush the burgers with the oil to keep them from sticking. Cook for 7-8 mins on each side until charred and cooked through. Fill the rolls with the burgers, guacamole and peppadews.

',
image_url = '/cheza/code/Img/recipes/Turkey & coriander burgers with guacamole.jpg',
calories = 497,
tag = 'low-fat turkey, avocado, vitaminC',
meal  = 'lunch',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('turkey mince',15,400,14),
	  ('Worcestershire sauce',1,1,14),
	  ('fresh breadcrumb',15,85,14),
	  ('chopped coriander',1,1,14),
	  ('red onion',15,10,14),
	  ('avocado',15,45,14),
	  ('chilli',15,5,14),
	  ('ciabatta rolls',15,30,14),
	  ('sunflower oil',1,1,14),
	  ('hot peppadew peppers',15,10,14)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//recipe15
$sql = "INSERT INTO recipes SET
name = 'Thai red duck with sticky pineapple rice',
serving = 2,
description = '
    - Sit a duck breast between 2 sheets of cling film on a chopping board. Use a rolling pin to bash the duck until it is 0.5cm thick. Repeat with the other breast, then put them both in a dish. Mix the curry paste with the lime zest and juice, and rub all over the duck. Leave to marinate at room temperature for 20 mins.
	- Meanwhile, tip the rice into a small saucepan with some salt. Pour over the coconut milk with 150ml water. Bring to a simmer, then cover the pan, turn the heat down low and cook for 5 more mins. Stir in the peas, then cover, turn the heat off and leave for another 10 mins. Check the rice - all the liquid should be absorbed and the rice cooked through. Boil the kettle, put the beansprouts and red onion in a colander and pour over a kettleful of boiling water. Stir the beansprouts and onion into the rice with the pineapple, chilli and coriander stalks, and some more salt if it needs it, and put the lid back on to keep warm.
	- Heat a griddle pan and cook the duck for 1-2 mins each side or until cooked to your liking. Slice the duck, stir most of the coriander leaves through the rice with a fork to fluff up, and serve alongside the duck, scattered with the remaining coriander.
',
image_url = '/cheza/code/Img/recipes/thai-red-duck-with-sticky-pineapple-rice.jpg',
calories = 500,
tag = 'Tailand, rice, duck',
meal  = 'dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('duck breasts',15,80,15),
	  ('Thai red curry paste',1,1,15),
	  ('zest and juice',10,1,15),
	  ('jasmine rice',15,140,15),
	  ('light coconut milk',9,125,15),
	  ('frozen peas',15,140,15),
	  ('beansprouts',15,50,15),
	  ('red onion',15,20,15),
	  ('fresh pineapple',15,100,15),
	  ('red chilli',15,10,15),
	  ('coriander',15,20,15)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//recipe16
$sql = "INSERT INTO recipes SET
name = 'Bengali mustard fish',
serving = 2,
description = '
    - In a bowl, marinate the fish in 1/4 tsp turmeric and a good pinch of salt, tossing to coat, then set aside. Using a spice grinder or a pestle and mortar, grind the mustard seeds to a fine powder. Put the tomato, garlic, 2 or 3 green chillies, the powdered mustard seeds, 1/2 tsp turmeric, a pinch of salt and 150ml water in the small bowl of a food processor and blitz to a smooth paste.
	- Heat 3 tbsp of the mustard oil in a medium-sized non-stick pan until smoking, then take off the heat and wait for 30 secs. Add the nigella seeds to the pan and allow to sizzle for 10 secs, then add the paste. Cook over a medium heat, stirring occasionally, until all the excess liquid has evaporated and the paste releases its oil. Lower the heat and continue cooking for another 4 mins or so, until it darkens a little. Add 400ml water and the remaining chillies, bring to a boil and simmer for 7-8 mins until it has a medium consistency, not too watery. Check the seasoning and keep on a low heat while you fry the fish.
	- Heat the remaining oil in a frying pan until smoking. Add the fish and fry on all sides for around 6 mins until golden brown. Add the fish to the mustard sauce, bring back to the boil and cook for 2 mins. Sprinkle on the coriander leaves and serve with rice, if you like.
',
image_url = '/cheza/code/Img/recipes/Bengali mustard fish.jpg',
calories = 492,
tag = 'fish, Bengal',
meal  = 'lunch',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('halibut steaks',15,450,16),
	  ('ground turmeric',1,1,16),
	  ('yellow mustard seeds',1,2,16),
	  ('tomatoes',15,140,16),
	  ('garlic cloves',15,10,16),
	  ('green chillies',15,10,16),
	  ('mustard oil',1,4,16),
	  ('nigella seeds',1,1,16),
	  ('coriander leaves',15,5,16),
	  ('cooked rice',15,100,16)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe 17
$sql ="INSERT INTO `recipes` (`name`,`serving`,`description`,`image_url`,`calories`,`tag`,`meal`,`user_id`) VALUES ('Pork and leek stir-fry',1,'1.Freeze pork for approx. 20 min. Slice against the grain into thin strips (approx. 5 cm/2 in. long). Split leek lengthwise and chop green part into 1.25 cm/0.5 in. slices. Mince garlic and fresh ginger. 2.Combine some of the soy sauce and half of the cornstarch in a large mixing bowl. Add pork and toss to coat. Set aside to marinate for approx. 10 min.
3.Whisk water, remaining soy sauce, Sriracha, remaining cornstarch, sugar, and chicken flavor bouillon in a small bowl. Set aside. 4.Heat some of the oil in a wok or large nonstick skillet over medium-high heat and swirl to coat. When oil is hot and shimmering, add pork. Stir-fry for approx. 1 – 3 min., or just until no longer pink. Remove from wok. 5.Heat remaining oil in same wok and swirl to coat. Add sliced leeks and stir-fry for approx. 3 min., or until almost tender. Add minced garlic and
ginger and fry for approx. 30 sec., or until fragrant. Add pork back to wok. 6.Stir in soy sauce mixture. Reduce heat to medium and cook for approx. 1 – 2 min., or until sauce thickens. Turn off the heat. Stir in toasted sesame oil and serve with hot rice. ','/cheza/code/Img/recipes/pork-and-leek-stir-fry.jpg','340','pork,main','lunch,dinner',$user_id);";
if ($connection->multi_query($sql) === true) {
    echo "Data added to recipes 17 into Table successfully!" . "</br>";
} else {
    echo "Error inserting data to recipes 17 into TABLE!" . $connection->error . "</br>";
}

$sql = "INSERT INTO ingredients(name,unit,amount,recipe_id)
        VALUES ('pork tenderloin(boneless)',15,450,17),
                ('leeks',17,2,17),
                ('garlic',17,2,17),
                ('ginger',15,3.5,17),
                ('soy sauce (reduced-sodium, divided)',2,3,17),
                ('cornstarch(divided)',1,2,17),
                ('water',9,120,17),
                ('Sriracha',2,1,17),
                ('sugar',1,1,17),
                ('chicken flavor bouillon (powdered)',1,0.5,17),
                ('peanut oil(divided)',1,3.5,17),
                ('toasted sesame oil for serving',1,0.5,17),
                ('rice for serving',15,240,17)";

if($connection->query($sql) === TRUE){
  echo "Ingredient 17 DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe 18
$sql ="INSERT INTO `recipes` (`name`,`serving`,`description`,`image_url`,`calories`,`tag`,`meal`,`user_id`) VALUES ('Mapo tofu',1,'1.Cut the silken tofu into equal pieces (approx. 2.5cm / 1 in.). Soak in salted water for approx. 5 min., then remove and set aside. Slice green onion, mince garlic, and dice onion and ginger. 2.Heat oil in the pan over medium-high heat, add ground pork and fry until browned. Add the bean paste, fermented soy beans, green onion, ginger, garlic and dark soy sauce, then deglaze with the rice wine. 3.Add water and silken tofu to wok, then add salt and sugar, and cook everything for approx. 3 min. Mix together cornstarch with a little water to form a slurry, then add to pan and incorporate. 4.Transfer to a plate, season to taste with Sichuan pepper powder, and green onion. ','/cheza/code/Img/recipes/Mapo-tofu.jpg','402','spicy,chinese','dinner',$user_id);";
if ($connection->multi_query($sql) === true) {
    echo "Data added to recipes 18 into Table successfully!" . "</br>";
} else {
    echo "Error inserting data to recipes 18 into TABLE!" . $connection->error . "</br>";
}

$sql = "INSERT INTO ingredients(name,unit,amount,recipe_id)
        VALUES ('slik tofu',15,350,18),
                ('ground pork',15,50,18),
                ('bean paste',1,4,18),
                ('fermented soy beans',1,4.5,18),
                ('green onion',17,1,18),
                ('ginger',15,100,18),
                ('garlic',17,2,18),
                ('rice wine',9,60,18),
                ('dark soy sauce',1,1,18),
                ('sugar',1,1,18),
                ('Sichuan pepper powder',1,0.5,18),
                ('cornstarch',1,2,18),
                ('water',9,100,18),
                ('oil',9,100,18)";


if($connection->query($sql) === TRUE){
  echo "Ingredient 18 DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe 19
$sql ="INSERT INTO `recipes` (`name`,`serving`,`description`,`image_url`,`calories`,`tag`,`meal`,`user_id`) VALUES ('German donauwelle cake',3,'1.Preheat oven to 180°C/350°F. Strain Morello cherries and set aside. Beat together butter, sugar, and vanilla extract until light und fluffy. Add eggs one by one and mix in. 2.Combine flour, baking powder, and salt in a large mixing bowl. Add to butter mixture in 3 portions. Gently mix in until well-combined. Add ¾ of dough to a parchment-lined baking pan. Add cocoa powder and milk to remaining dough and mix until well combined. Add to baking pan and spread on top of light-colored batter. 3.Add cherries evenly across batter and gently press. Bake at 180°C/350°F for approx. 35 – 40 min. Cake is ready when a toothpick inserted into the center comes out clean. 4.While the cake is in the oven, prepare the vanilla cream. Preheat ¾ of milk in a saucepan with ¾ of sugar and vanilla paste over medium heat. In a mixing bowl, combine remaining milk,
  starch, and sugar with a fork until smooth. Add starch mixture to milk once it starts to simmer and whisk until custard starts to thicken. Take off heat and whisk in egg yolk. Add to a bowl and leave to cool a little before placing plastic wrap on top. Allow to fully cool. 5.Beat butter until light and fluffy. Make sure that your butter and custard have the same temperature. In portions, add cooled down custard to butter and mix. Add custard cream to cake and leave to set for approx. 1 hr. in
  the fridge. 6.Cut chocolate into small pieces. Heat some water in a saucepan over medium-high heat. Add chocolate and coconut oil to a small bowl and place on top of saucepan to make a double boiler. Stir gently until fully melted. Top cake with chocolate glaze. Once the chocolate has set, approx. 1 – 2 min., use a fork or decorating scraper to create a wave pattern. Let chocolate set completely,
  slice.','/cheza/code/Img/recipes/German-donauwellecake.jpg','1731','dessert,baking,snack',0,$user_id);";
if ($connection->multi_query($sql) === true) {
    echo "Data added to recipes 19 into Table successfully!" . "</br>";
} else {
    echo "Error inserting data to recipes 19 into TABLE!" . $connection->error . "</br>";
}

$sql = "INSERT INTO ingredients(name,unit,amount,recipe_id)
        VALUES ('Morello cherries',15,500,19),
                ('butter',15,500,19),
                ('sugar',15,300,19),
                ('vanilla paste',1,2,19),
                ('eggs',17,5,19),
                ('flour',15,380,19),
                ('baking powder',1,3,19),
                ('salt',1,0.25,19),
                ('cocoa powder',2,2,19),
                ('milk',9,510,19),
                ('starch',1,45,19),
                ('egg yolk',17,1,19),
                ('dark chocolate',15,200,19),
                ('coconut oil',15,20,19)";


if($connection->query($sql) === TRUE){
  echo "Ingredient 19 DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe 20
$sql ="INSERT INTO `recipes` (`name`,`serving`,`description`,`image_url`,`calories`,`tag`,`meal`,`user_id`) VALUES ('Steamed pork buns',6,'1.Dissolve yeast in warm water, add flour and work into a dough. Set aside to rest for approx. 2 hrs. 2.Finely slice the scallions and chop the ginger. In another mixing bowl, add scallions and ginger to the pork along with salt, white pepper, five-spice powder, light soy sauce, dark soy sauce, oyster sauce and sesame oil. If needed, add a little water to
  loosen the mixture. Stir well to combine and set aside. 3.Bring a large pot of water to a boil. Roll the dough into a log shape and cut into 15 equal portions. Dust the work surface with flour, then use a rolling pin to roll out the pieces of dough into equal-sized circles, rotating the dough as you roll it to ensure an even thickness. Place a spoonful of the pork filling in the center of each dough circle and gently pull and pinch the edges together to make a tight seal.Set aside to rise for
  20 min. 4.Transfer buns to a steam basket set over boiling water and steam for approx. 15 min. ','/cheza/code/Img/recipes/SteamedChinesePorkBuns.jpg','1890','Steamed,Street food ','breakfast',$user_id);";

if ($connection->multi_query($sql) === true) {
    echo "Data added to recipes 20 into Table successfully!" . "</br>";
} else {
    echo "Error inserting data to recipes 20 into TABLE!" . $connection->error . "</br>";
}

$sql = "INSERT INTO ingredients(name,unit,amount,recipe_id)
        VALUES ('ground pork',15,500,20),
                ('flour',15,250,20),
                ('water',9,130,20),
                ('scallions',15,50,20),
                ('salt',1,2,20),
                ('fresh yeast',15,20,20),
                ('ginger',15,15,20),
                ('oyster sauce',9,20,20),
                ('white pepper',15,10,20),
                ('five-spice powder',15,10,20),
                ('light soy sauce',9,10,20),
                ('dark soy sauce',9,10,20),
                ('sesame oil',9,10,20),
                ('flour',9,10,20)";


if($connection->query($sql) === TRUE){
  echo "Ingredient 20 DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//recipe21
$sql = "INSERT INTO recipes SET
name = 'Turkey Congee',
serving = 1,
description = '
    - In a large saucepan, cover the turkey carcass with 14 cups of water. Add in one-third of the celery, half the carrots, and the onion quarters and bring to a boil.
	- Reduce the heat to maintain a simmer and cook until the stock has reduced to 8 cups, about 1 1⁄2 hours.
	- Remove the pan from the heat and discard the carcass. Pour the stock through a fine sieve set over a bowl and discard the solids.
	-In a 4-qt. saucepan, combine the stock with the remaining celery and carrots and the rice. Bring to a boil, then reduce the heat to maintain a simmer and cook, stirring occasionally, until the rice falls apart and the congee thickens, about 90 minutes.
	- Stir in the shredded turkey and cook for 5 minutes longer. Season the congee with salt and pepper and divide among serving bowls. Top with cilantro and scallions before serving.

',
image_url = '/cheza/code/Img/recipes/r_1.jpg',
calories = 215,
tag = 'Chinese, turkey, soups, rice',
meal  = 'breakfast',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('turkey carcass',1,3,21),
	  ('cooked turkey meat',13,3,21),
	  ('carrot',15,100,21),
	  ('yellow onion',15,10,21),
	  ('rice',5,1,21),
	  ('salt',1,1,21),
	  ('black pepper',1,1,21),
	  ('cilantro',15,20,21),
	  ('scallion',15,20,21)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe22
$sql = "INSERT INTO recipes SET
name = 'Brussels Sprout and Quinoa Hash With Fried Eggs',
serving = 1,
description = '
    - Heat 1 tablespoon ghee in a medium skillet over medium heat. Add brussels sprouts and sauté until tender but still slightly crisp, 4 to 5 minutes.
	- Add quinoa and season with salt and pepper ; cook until quinoa is warmed through. Stir in sriracha. Taste and season with additional salt and pepper, if desired.
	- Heat remaining 1 tablespoon ghee in a medium skillet. Crack 1 egg into a small bowl, then slide into skillet. Repeat with remaining egg. Cook until whites are set and yolks reach desired doneness, about 4 minutes for soft, runny yolks.
	- Divide brussels sprout mixture between 2 bowls and top each with a fried egg.
',
image_url = '/cheza/code/Img/recipes/r_2.png',
calories = 313,
tag = 'meatless, eggs',
meal  = 'lunch',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('avocado oil',1,2,22),
	  ('brussels sprout',5,2,22),
	  ('millet',5,1,22),
	  ('salt ',1,1,22),
	  ('black pepper',1,1,22),
	  ('salt',1,1,22),
	  ('black pepper',1,1,22),
	  ('sriracha',1,1,22),
	  ('egg',15,100,22)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe23
$sql = "INSERT INTO recipes SET
name = 'Polenta Fritters with Asparagus & Eggs',
serving = 2,
description = '
    - Heat 1 tablespoon ghee in a medium skillet over medium heat. Add brussels sprouts and sauté until tender but still slightly crisp, 4 to 5 minutes.
	- Push polenta and asparagus to side of pan. Coat pan with cooking spray. Add eggs, and fry until yolk is set, about 3 minutes.
	- Serve eggs on warm polenta with asparagus on the side. Top with cheese and black pepper.

',
image_url = '/cheza/code/Img/recipes/r_3.png',
calories = 380,
tag = 'asparagus spears, eggs',
meal  = 'breakfast',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('polenta',17,4,23),
	  ('asparagus spears',15,50,23),
	  ('egg',15,100,23),
	  ('grated Parmesan cheese',1,2,23),
	  ('black pepper',1,1,23)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe24
$sql = "INSERT INTO recipes SET
name = 'Hot Soba Noodles with Chicken and Egg',
serving = 1,
description = '
    - Heat the broiler. On a foil-lined baking sheet, arrange the chicken skin-side-down and, using a paring knife, lightly score the meat every 1⁄4 inch and season lightly with salt.Broil the chicken thighs, basting every 4 minutes with 1⁄4 cup of the sauce base and flipping halfway, until cooked through and golden brown, about 16 minutes.
	- Transfer the chicken to a cutting board, let rest for 10 minutes, then slice each thigh into 3 thick slices.In a large saucepan, combine the remaining 3⁄4 cup sauce base with the dashi. Using a Microplane set over a fine sieve set in a bowl, grate the 6-inch piece of ginger into the sieve, pressing on the solids to drain as much juice as possible into the bowl.
	- Pour 2 tablespoons of the ginger juice into the saucepan and discard the rest or save for another use. Bring the soup to a boil over medium heat and keep warm.In a large pot of boiling water, cook the soba noodles until al dente, about 3 minutes. Using tongs, lift the noodles from the water and transfer to a colander and rinse under cold running water until the water runs clear. Drain the noodles again and divide among 4 large serving bowls.
	- Add the spinach to the boiling water and cook until just wilted, about 1 minute. Drain the spinach, pressing to remove as much water as possible, and divide among each serving of noodles.Ladle the warm soup over the spinach and noodles in each bowl and top each with 3 chicken slices.
	- Place 1 egg half and one-quarter of the chives in each bowl and then garnish with one-quarter each of the julienned ginger and both sesame seeds. Garnish each bowl with schichimi togarashi and serve immediately.

',
image_url = '/cheza/code/Img/recipes/r_4.jpg',
calories = 120,
tag = 'Japanese, noodles, chicken, spinach',
meal  = 'lunch',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('chicken thigh',17,4,24),
	  ('Kaeshi',5,1,24),
	  ('ginger',17,2,24),
	  ('dried noodles',13,8,24),
	  ('baby spinach',5,2,24),
	  ('salt',1,1,24),
	  (' soft-boiled eggs',15,100,24),
	  ('minced chives',15,10,24),
	  ('toasted black sesame seeds',5,1,24)
";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe25
$sql = "INSERT INTO recipes SET
name = 'Rice Porridge',
serving = 1,
description = '
    - In a sieve, rinse rice under cold water and let drain.
	- Put rice, 8 cups water, and salt into a 4-qt saucepan.
	- Bring to a boil over high heat, reduce heat to medium-low, and cook, partially covered and stirring occasionally, until the rice takes on the consistency of porridge, about 1 1⁄2 hours.
	-Divide porridge between 4 bowls and garnish each with a drizzle of chile oil, scallions, chiles, and crispy shallots.
',
image_url = '/cheza/code/Img/recipes/r_5.png',
calories = 120,
tag = 'Chinese, soups, rice',
meal  = 'breakfast',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('white rice',5,1/2,25),
	  ('chile oil',1,2,25),
	  ('scallions',15,10,25),
	  ('red chiles',15,10,25),
	  ('shallots',15,10,25),
	  ('salt',1,1,25)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe26
$sql = "INSERT INTO recipes SET
name = 'Hazelnut cream hot chocolate',
serving = 1,
description = '
    - Whip the cream until thick and billowy then fold in the chocolate hazelnut spread. Only stir once or twice, you want a marbled effect.
	- In a small saucepan over a medium heat warm the milk until simmering. Take off the heat then add the chocolate. Let it melt, stirring frequently until smooth. Return to the heat until warm then add the hazelnut liqueur.
	- Pour the hot chocolate into a mug and add the whipped cream mixture. Sprinkle the top with the toasted hazelnuts and enjoy.
',
image_url = '/cheza/code/Img/recipes/hazelnut-cream-hot-chocolate.jpg',
calories = 603,
tag = 'chocolate, cream',
meal  = 'drink',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('double cream',9,50,26),
	  ('chocolate hazelnut spread (we used Nutella)',1,1,26),
	  ('whole milk',9,150,26),
	  (' milk chocolate, chopped',15,25,26),
	  ('hazelnut liqueur (we used Frangelico)',9,25,26),
	  ('chopped hazelnuts, toasted',1,1,26)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe27
$sql = "INSERT INTO recipes SET
name = 'Layered lime cheesecake',
serving = 16,
description = '
    - Whizz the biscuits to crumbs in a food processor, or tip into a food bag and crush with a rolling pin. Mix with the melted butter, then press into the base of a 20cm springform cake tin. Chill in the fridge until needed.
	- Put the cream cheese, icing sugar and lime zest in a bowl, then beat with an electric mixer until smooth. Tip in the double cream and continue beating until completely combined. Spoon the cream mixture onto the biscuit base, working from the edges inwards and making sure that there are no air bubbles. Smooth the top of the cheesecake down with the back of a dessert spoon. Leave to set in the fridge while you make the glaze.
	- Soak the gelatine leaves in cold water. Tip the other ingredients for the glaze into a saucepan with 200ml water. Cook gently until the sugar has dissolved and the syrup is simmering. Drain and squeeze the gelatine of any excess water, then stir into the hot syrup to dissolve. Leave everything to infuse until just warm, then sieve the syrup into a jug. When cooled, pour over the cheesecake and put in the fridge overnight to set. Carefully remove the cake from tin before serving.
',
image_url = '/cheza/code/Img/recipes/keylime-cheesecake.jpg',
calories = 428,
tag = 'cheese,cake',
meal  = 'dessert',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('gingernut biscuits',15,250,27),
	  ('unsalted butter, melted',15,125,27),
	  ('cream cheese',15,600,27),
	  ('icing sugar',15,100,27),
	  ('limes, zested (save the juice to use in the glaze)',18,3,27),
	  ('double cream',9,300,27),
	  ('gelatine leaves',17,4,27),
	  ('caster sugar',15,100,27),
	  ('limes, juiced and pared, and zest of 3',18,6,27)

";

if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe28
$sql = "INSERT INTO recipes SET
name = 'Ricotta strawberry French toast',
serving = 2,
description = '
    - In a wide dish, whisk the egg, milk and vanilla together. Coat one side of the bread slices in the liquid, then carefully flip them over and leave them to soak for 1-2 mins.
	- Melt 1 tbsp of the butter in a large non-stick pan over a medium heat and add two slices of bread. Cook for 5 mins or until golden, then turn to cook the other side for another 5 mins. Transfer to a plate and cook the other two slices in the rest of the butter.
	- Halve the toast on the diagonal and spread each slice with the ricotta. Drizzle over the honey and a pinch of flaky sea salt, and arrange some sliced strawberries in a fan across the toast. Decorate the plate with the halved strawberries and mint.
',
image_url = '/cheza/code/Img/recipes/ricotta-strawberry-french-toast.jpg',
calories = 540,
tag = 'strawberry,toast',
meal  = 'breakfast',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('large egg, beaten',18,1,28),
	  ('milk',9,300,28),
	  ('vanilla extract',1,1,28),
	  ('thick-cut white bread',18,4,28),
	  ('butter',2,2,28),
	  ('ricotta',15,50,28),
	  ('honey',2,2,28),
	  ('strawberries, some sliced, some halved',15,100,28),
	  ('mint sprigs, leaves picked',17,2,28)

";
if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe29
$sql = "INSERT INTO recipes SET
name = 'Michelada',
serving = 1,
description = '
    - Mix 1 tsp each salt, chilli powder and black pepper on a plate, wipe the rim of the glass with a slice of lime and roll in the spice mix. Add ice, juice ½ lime and 3 shakes hot sauce, then top up with Mexican lager.
',
image_url = '/cheza/code/Img/recipes/michelada.jpg',
calories = 115,
tag = 'juice,lime',
meal  = 'drink',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('salt',1,1,29),
	  ('chili powder',1,1,29),
	  ('black pepper',1,1,29),
	  ('ice',18,0,29),
	  ('juice 1/2 lime',18,0.5,29),
	  ('shakes hot sauce',18,3,29),
	  ('Mexican lager',18,0,29)

";

if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe30
$sql = "INSERT INTO recipes SET
name = 'Fruit & nut yogurt',
serving = 1,
description = '
    - Mix the nuts, sunflower seeds and pumpkin seeds. Mix the sliced banana and berries. Layer up in a bowl with yoghurt and enjoy.
',
image_url = '/cheza/code/Img/recipes/Fruit & nut yogurt.jpg',
calories = 694,
tag = 'fruit,nut,yogurt',
meal  = 'snack',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('chopped mixed nut',2,3,30),
	  ('sunflower seed',2,1,30),
	  ('pumpkin seed',2,1,30),
	  ('banana',18,1,30),
	  ('handfuls berries (frozen and defrosted is fine)',18,1,30),
	  ('vanilla yogurt',15,200,30)

";

if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe31
$sql = "INSERT INTO recipes SET
name = 'Turkey & pepper pittas',
serving = 2,
description = '
    - Heat the oil in a wok or large frying pan and fry the turkey and chilli flakes for 5-6 mins. Add the peppers and spring onions and stir-fry until the turkey is cooked but the peppers still have crunch. Season.
	- Divide the avocado and coriander between the pitta halves, then spoon in the turkey and pepper mix. Add a dollop of soured cream to each and serve straight away.

',
image_url = '/cheza/code/Img/recipes/Turkey & pepper pittas.jpg',
calories = 526,
tag = 'turkey,pittas',
meal  = 'snack',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('olive oil',2,1,31),
	  ('turkey breast steak, cut into strips',15,200,31),
	  ('chilli flakes',18,1,31),
	  ('red pepper, deseeded and cut into strips',18,1,31),
	  ('yellow pepper, deseeded and cut into strips',18,1,31),
	  ('spring onions, trimmed and sliced',18,3,31),
	  ('avocado, stoned, peeled and sliced',18,1,31),
	  ('handful coriander leaves',18,1,31),
	  ('wholemeal pitta breads, toasted and halved to form pockets',18,2,31),
	  ('soured cream',2,2,31)

";

if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe32
$sql = "INSERT INTO recipes SET
name = 'Mango & banana smoothie',
serving = 1,
description = '
    - Cut the mango down either side of the flat stone, then peel and cut the flesh into chunks.
	- Peel and chop the banana.
	- Put all the ingredients into a food processor or blender, then process until smooth and thick. Keep in the fridge and use the day you make it.

',
image_url = '/cheza/code/Img/recipes/Mango & banana smoothie.jpg',
calories = 107,
tag = 'mango,banana',
meal  = 'smoothie',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('medium mango',18,1,32),
	  ('banana',18,1,32),
	  ('orange juice',9,500,32),
	  ('ice cubes',18,4,32)

";

if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe33
$sql = "INSERT INTO recipes SET
name = 'Peach Melba smoothie',
serving = 2,
description = '
    - Drain and rinse peaches and place in a blender with raspberries. Add orange juice and fresh custard and whizz together.
	- Pour over ice, garnish with another spoonful of custard and a few raspberries. Best served chilled.
',
image_url = '/cheza/code/Img/recipes/Peach Melba smoothie.jpg',
calories = 159,
tag = 'peach,melba',
meal  = 'smoothie',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('can peach halves',15,410,33),
	  ('frozen raspberry, plus a few for garnish',15,100,33),
	  ('orange juice',9,100,33),
	  ('fresh custard, plus a spoonful for garnish',9,150,33)

";
if($connection->query($sql) === TRUE){
  echo "Ingredients DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe34
$sql = "INSERT INTO recipes SET
name = 'Sweet & spicy popcorn',
serving = 2,
description = '
    - Cook the microwave popcorn according to the packet instructions. Tip into a large bowl. Sprinkle over the spices, then pour over the agave syrup. Stir and serve warm or pour into a bag and take to work as an afternoon snack.
	',
image_url = '/cheza/code/Img/recipes/Sweet & spicy popcorn.jpg',
calories = 275,
tag = 'popcorn',
meal  = 'dessert',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('bag salted microwave popcorn',15,100,34),
	  ('chilli powder',1,0.25,34),
	  ('cinnamon',1,0.5,34),
	  ('agave syrup',2,1,34)

";

if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe35
$sql = "INSERT INTO recipes SET
name = 'Dried fruit energy nuggets',
serving = 2,
description = '
    - Whizz apricots with dates and cherries in a food processor until very finely chopped. Tip into a bowl and use your hands to work in coconut oil. Shape the mix into walnut-sized balls, then roll in sesame seeds. Store in an airtight container until you need a quick energy fix.Whizz apricots with dates and cherries in a food processor until very finely chopped. Tip into a bowl and use your hands to work in coconut oil. Shape the mix into walnut-sized balls, then roll in sesame seeds. Store in an airtight container until you need a quick energy fix.
',
image_url = '/cheza/code/Img/recipes/Dried fruit energy nuggets.jpg',
calories = 113,
tag = 'fruit,nuggets',
meal  = 'snack',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('soft dried apricot',15,50,35),
	  ('soft dried date',15,100,35),
	  ('dried cherry',15,50,35),
	  ('coconut oil',1,2,35),
	  ('toasted sesame seed',2,1,35)

";


if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe36
$sql = "INSERT INTO recipes SET
name = 'Sweet mustard salmon with garlicky veg',
serving = 2,
description = '
    - Preheat the oven to fan 180C/conventional 200C/gas 6. Boil the potatoes for 10 minutes until tender. Meanwhile, whisk the mustard, orange juice and honey together in a bowl to make a marinade. Turn the salmon fillets in the marinade until evenly coated, then set aside. Deseed the peppers and cut into thick strips.
	- Drain the potatoes and tip into a shallow ovenproof dish or roasting tray with the peppers and sugar snap peas. Drizzle over the oil, salt and pepper, then toss everything together. Put the salmon fillets on top of the vegetables and pour over the marinade. Bake for 20-25 minutes until the salmon is cooked and just starting to brown.
',
image_url = '/cheza/code/Img/recipes/Sweet mustard salmon with garlicky veg.jpg',
calories = 990,
tag = 'salmon',
meal  = 'dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('baby new potato, halved',15,750,36),
	  ('wholegrain mustard',2,1,36),
	  ('orange',18,1,36),
	  ('clear honey',1,2,36),
	  ('skinless, boneless, salmon fillets, each weighing about 140g/5oz',18,4,36),
	  ('orange or red pepper',18,2,36),
	  ('sugar snap peas',15,250,36),
	  ('olive oil',2,2,36)

";


if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe37
$sql = "INSERT INTO recipes SET
name = 'Sams toad-in-the-hole',
serving = 2,
description = '
    - Heat oven to 220C/200C fan/gas 7. Put the sausages in a 20 x 30cm roasting tin with the oil, then bake for 15 mins until browned.
	- Meanwhile, make up the batter mix. Tip the flour into a bowl with the salt, make a well in the middle and crack both eggs into it. Use an electric whisk to mix it together, then slowly add the milk, whisking all the time. Leave to stand until the sausages are nice and brown.
	- Carefully remove the sausages from the oven – watch because the fat will be sizzling hot – but if it isn’t, put the tin on the hob for a few mins until it is. Pour in the batter mix, transfer to the top shelf of the oven, then cook for 25-30 mins, until risen and golden. Serve with gravy and Sam’s favourite veg – broccoli.
',
image_url = '/cheza/code/Img/recipes/Sams toad-in-the-hole.jpg',
calories = 944,
tag = 'sausage',
meal  = 'lunch,dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('chipolatas',18,12,37),
	  ('sunflower oil',2,1,37),
	  ('plain flour',15,140,37),
	  ('salt',1,0.5,37),
	  ('eggs',18,2,37),
	  ('semi-skimmed milk',9,175,37)


";


if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}

//recipe38
$sql = "INSERT INTO recipes SET
name = 'Methi Sprouts Soup',
serving = 1,
description = '
   -In a sauce pan add half a cup of water and add the methi sprouts. Cook for 5 minutes. In another sauce pan boil water, drop the tomato, cover and keep for 5 minutes. After 5 minutes, take out the tomato and run through cold water.
   -Remove the skin, chop them and blend to a smooth puree.  Also puree the boiled sprouts. Mix  the tomato and sprouts puree. Boil it for 5 minutes.
   -Add the seasoning except lime juice and boil for one minute.Switch off and stir in the lime juice.
   -Transfer to a soup bowl, garnish with pudina/coriander/sprouts and fried bread cubes. Serve hot
',
image_url = '/cheza/code/Img/recipes/Sproutsoup38.jpg',
calories = 172,
tag = 'soup',
meal  = 'lunch,dinner',
user_id = $user_id
";
if($connection->query($sql) === TRUE){
  echo "Recipe DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING recipe DATA!" . $connection->error . "</br>" ;
}


// Insert Data-Begin


//INGREDIENTS's TEST DATA
$sql = "INSERT INTO ingredients (name,unit,amount,recipe_id)
     VALUES
      ('Sprouts',5,1,38),
	  ('Tomato',17,1,38),
	  ('Butter',1,1,38),
	  ('salt',1,0.5,38),
	  ('sugar',1,0.5,38),
	  ('fried breads',1,2,38)


";


if($connection->query($sql) === TRUE){
  echo "Ingredient DATA INSERTED successfully!" . "</br>";
}else {
  echo "Error INSERTING ingredients DATA!" . $connection->error . "</br>" ;
}


//Import
$sqlSource = file_get_contents('nutrientsList/nutrients.sql');

if($connection->multi_query($sqlSource) === TRUE){
  echo "Successfully IMPORTED nutrients list";
}else{
  echo "Error IMPORTING nutrients list table!" . $connection->error . "</br>";
}
