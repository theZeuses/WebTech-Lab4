<!DOCTYPE html>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
	$name="";$gender="Male";$email="";
  $nameErr=$emailErr="";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
  		if (empty($_POST["name"])) {
        $nameErr = "Name is required";
      } else {
          $name = test_input($_POST["name"]);
      }

      if (empty($_POST["email"])) {
        $emailErr = "Email is required";
      } else {
          $email = test_input($_POST["email"]);
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
          }
      }
      
      $gender = $_POST["gender"];
  }

  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Enter your Information.</h2>
<p><span class="error">* required field</span></p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Name: <br>
    <input type="text" name="name" value="<?php echo $name; ?>" > <span class="error">* <?php echo $nameErr;?></span>
    <br>
    Email: <br>
    <input type="EMAIL" name="email" value="<?php echo $email; ?>"> <span class="error">* <?php echo $emailErr;?></span>
    <br>
    Gender: <br>
    <input type="radio" name=gender value="Male" <?php if($gender=="Male") echo 'checked'; ?>>Male
    <input type="radio" name=gender value="Female" <?php if($gender=="Female") echo 'checked'; ?>>Female 
    <input type="radio" name=gender value="Other" <?php if($gender=="Other") echo 'checked'; ?>>Other
    <br>
  <input type="submit" value="Submit">
</form>
</form>

<?php
class Entry {
  private $name;
  private $email;
  private $gender;

  function __construct($name, $email, $gender) {
    $this->name = $name; 
    $this->email = $email; 
    $this->gender = $gender;
  }
  function set_name($name) {
    $this->name = $name;
  }
  function get_name() {
    return $this->name;
  }
  function set_email($email) {
    $this->email = $email;
  }
  function get_email() {
    return $this->email;
  }
  function set_gender($gender) {
    $this->gender = $gender;
  }
  function get_gender() {
    return $this->gender;
  }
}

$entry = new Entry($name, $email, $gender);

$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
$txt = "Name: ".$entry->get_name()." "."E-mail: ".$entry->get_email()."Gender: ".$entry->get_gender()."\n";
fwrite($myfile, $txt);
fclose($myfile);

$xmldoc = new DomDocument( '1.0' );
$xmldoc->preserveWhiteSpace = false;
$xmldoc->formatOutput = true;

$name = $entry->get_name();
$email = $entry->get_email();
$gender = $entry->get_gender();

if( $xml = file_get_contents( 'main.xml') ) {
    $xmldoc->loadXML( $xml, LIBXML_NOBLANKS );

    $root = $xmldoc->getElementsByTagName('entries')->item(0);

    $entry = $xmldoc->createElement('entry');
    $entryAttribute = $xmldoc->createAttribute("type");
    $entryAttribute->value = "text";
    $entry->appendChild($entryAttribute);


    $root->insertBefore( $entry, $root->firstChild );

    $nameElement = $xmldoc->createElement('name');
    $entry->appendChild($nameElement);
    $nameText = $xmldoc->createTextNode($name);
    $nameElement->appendChild($nameText);

    $emailElement = $xmldoc->createElement('email');
    $entry->appendChild($emailElement);
    $emailText = $xmldoc->createTextNode($email);
    $emailElement->appendChild($emailText);

    $genderElement = $xmldoc->createElement('gender');
    $entry->appendChild($genderElement);
    $genderText = $xmldoc->createTextNode($gender);
    $genderElement->appendChild($genderText);

    $xmldoc->save('main.xml');
  }

?>

</body>
</html>
