	<?php
session_start();

if ($_SESSION['image_is_logged_in'] == 'true' ) 
{

	$user = $_SESSION['user'];

	$gref = $_GET['id'];
	$que = $_GET['id3'];

	$pref=$_POST['ref'];
	$pnom=$_POST['nom'];
	$punitat=$_POST['unitat'];
	$pprov=$_POST['prov'];
	$pcat=$_POST['tipus'];
	$subm_cat=$_POST['subm_cat'];
	$psubcat=$_POST['subtipus'];
	$pactiu=$_POST['actiu'];
	$ppreusi=$_POST['preusi'];
	$piva=$_POST['iva'];
	$pmarge=$_POST['marge'];
	$pdescompte=$_POST['descompte'];
	$pestoc=$_POST['estoc'];
	$pnotes=$_POST['notes'];

	$pmarge=$pmarge/100;	
	$pdescompte=$pdescompte/100;	
	
	$ppreu=$ppreusi*(1+$piva);
	$ppreu=sprintf("%01.2f", $ppreu);
	$ppvpsi=$ppreusi*(1+$pmarge);
	$ppvpsi=sprintf("%01.2f", $ppvpsi);
	$ppvp=$ppvpsi*(1+$piva);
	$ppvp=sprintf("%01.2f", $ppvp);
	$ppvpdesc=$ppvpsi*(1-$pdescompte)*(1+$piva);
	$ppvpdesc=sprintf("%01.2f", $ppvpdesc);
	
	include 'config/configuracio.php';
	
	/// Busquem nom i prov del producte a partir de la referència ////
	$query0= "SELECT nom, proveidora FROM productes WHERE ref='$gref'";
	$result0=mysql_query($query0);
	if (!$result0) { die("Query0 to show fields from table failed");}
	list($gnom,$gprov)=mysql_fetch_row($result0);
	///////////

?>


<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
		<link rel="stylesheet" type="text/css" href="coope.css" />
		<title>crear-editar nou producte ::: la coope</title>

<script language="javascript" type="text/javascript">

function Validate(){

var nom = document.getElementById("nom").value;
var unitat = document.getElementById("unitat").value;
var prov = document.getElementById("prov").value;
var cat = document.getElementById("tipus").value;
var preusi = document.getElementById("preusi").value;
var marge = document.getElementById("marge").value;
var descompte = document.getElementById("descompte").value;

if (nom=="") {
alert ("T'has deixat el nom en blanc"); 
document.getElementById("nom").focus();
return false;
}

if (unitat==""){
var answer = confirm("T'has deixat unitat buida? \nD'acord: Continuar \nCancelar: Tornar a omplir el camp unitat");
	if (answer){
	return true;
	}
	else
	{
	document.getElementById("unitat").focus();
	return false;
	}
} 

if (prov=="") {
alert ("No has elegit cap proveïdora"); 
document.getElementById("prov").focus();
return false;
}

if (cat=="") {
alert ("No has elegit cap categoria"); 
document.getElementById("tipus").focus();
return false;
}

if (preusi==""){
var answer = confirm("T'has deixat preu sense iva buit? \nD'acord: Continuar \nCancelar: Tornar a omplir el camp preu sense iva");
	if (answer){
	return true;
	}
	else
	{
	document.getElementById("preusi").focus();
	return false;
	}
}
	
var illegalChars= /[\<\>\'\;\:\\\/\"\+\!\¡\º\ª\$\|\@\#\%\¬\=\?\¿\{\}\_\[\]]/;

if (nom.match(illegalChars)) {
   alert ('A nom: només s/accepten lletres, numeros, espai en blanc, punts, comes, guió alt i parentesis'); 
		document.getElementById("nom").focus();		
		return false;
}

if (isNaN(preusi)) {
alert ('A preu sense iva: només s/accepten numeros i el punt decimal'); 
document.getElementById("preusi").focus();
return false;
}

if (preusi<0) {
alert ('A preu sense iva: el numero ha de ser superior que 0'); 
document.getElementById("preusi").focus();
return false;
}

if (preusi.indexOf('.') == -1) preusi += ".";
dectext = preusi.substring(preusi.indexOf('.')+1, preusi.length);

if (dectext.length > 2)
{
alert('A preu sense iva: el numero de decimals no pot ser superior a 2');
document.getElementById("preusi").focus();
return false;
}

if (isNaN(marge)) {
alert ('A marge: només s/accepten numeros i el punt decimal'); 
document.getElementById("marge").focus();
return false;
}

if (marge.indexOf('.') == -1) marge += ".";
dectext2 = marge.substring(marge.indexOf('.')+1, marge.length);

if (dectext2.length > 2)
{
alert('A marge: el numero de decimals no pot ser superior a 2');
document.getElementById("marge").focus();
return false;
}


if (marge<0) {
alert ('A marge: el numero ha de ser superior que 0'); 
document.getElementById("marge").focus();
return false;
}

if (marge>=100000) {
alert ('A marge: el numero ha de ser inferior que 1.000 -o bé 100.000%-'); 
document.getElementById("marge").focus();
return false;
}

if (marge>100){
var answer = confirm("Has ficat un marge superior al 100% \nD'acord: Continuar \nCancelar: Tornar a omplir el camp marge");
	if (answer){
	return true;
	}
	else
	{
	document.getElementById("marge").focus();
	return false;
	}
}

if (isNaN(descompte)) {
alert ('A descompte: només s/accepten numeros i el punt decimal'); 
document.getElementById("descompte").focus();
return false;
}

if (descompte.indexOf('.') == -1) descompte += ".";
dectext2 = descompte.substring(descompte.indexOf('.')+1, descompte.length);

if (dectext2.length > 2)
{
alert('A descompte: el numero de decimals no pot ser superior a 2');
document.getElementById("descompte").focus();
return false;
}


if (descompte<0) {
alert ('A descompte: el numero ha de ser superior que 0'); 
document.getElementById("descompte").focus();
return false;
}

if (descompte>= 100) {
alert ('A descompte: el numero ha de ser inferior a 100'); 
document.getElementById("descompte").focus();
return false;
}

return true;
}


function dropdownlist(listindex)
{

	document.nouprod.subtipus.options.length = 0;
	switch (listindex)
	{
<?php

	$query9= "SELECT tipus FROM categoria ORDER BY tipus";
	$result9=mysql_query($query9);
	if (!$result9) { die("Query9 to show fields from table categoria failed");}
	while (list($jtipus)=mysql_fetch_row($result9))
	{
?>
		case "<?php echo $jtipus; ?>":
		document.nouprod.subtipus.options[0]=new Option("elegeix subcategoria","");
<?php>

		$query8= "SELECT subcategoria FROM subcategoria 
		WHERE categoria='".$jtipus."' ORDER BY subcategoria";
		$result8=mysql_query($query8);
		if (!$result8) {die("Query8 to show fields from table subcategoria failed");}
		$i=1;
		while (list($jsubcat)=mysql_fetch_row($result8))
		{
?>		
		document.nouprod.subtipus.options[<?php echo $i; ?>]=new Option("<?php echo $jsubcat; ?>","<?php echo $jsubcat; ?>");
<?php
			$i++;
		}
?>
		break;
<?php 
	} 
?>
	}
return true;
}

</script>




</head>

<?php
/// Si existeix un GET ($gref)) de producte anem a EDITAR ///
/// Si no existeix anem a CREAR///

	$supernom=strtoupper($gnom);
	$head3=" >>><a href='editprod.php?id=".$gref."'>editar producte ".$gnom."</a>";
	$tit='<p class="h1" style="background: #990000; text-align: left; padding-left: 20px;">Editar producte '.$supernom.'</p>';
	$subtit='Per editar un producte realitza els canvis en la fitxa i clica el botó GUARDAR 
	al final per fer-los efectius. Per eliminar un producte clica el boto ELIMINAR.';
	$formact="editprod.php?id=".$gref."&id2=".$gprov."&id3=edit";
	$width="50%";
	$buteli='<p class="linia_button2" style="background: #990000; text-align: center; vertical-align: middle;">
				<input class="button2" type="submit" value="GUARDAR">
				<input class="button2" name="eliminar" type="button" id="eliminar" value="ELIMINAR" 
     			onClick="var answer = confirm (\'Estas segur de borrar aquest producte!!\')
				if (answer)
					{window.location=\'editprod.php?id='.$gref.'&id3=elim\'}"></p>';
	if (!$gref)
	{
		$supernom=strtoupper($pnom);		
		$head3=">>><a href='editprod.php'>crear nou producte</a>";
		$tit='<p class="h1" style="background: #990000; text-align: left; padding-left: 20px;">Crear nou producte</p>';
		$subtit='Per crear un nou producte omple el formulari i clica el botó GUARDAR al final.';
		$formact="editprod.php?id3=create";
		$width="100%";
		$buteli='<p class="linia_button2" style="background: #990000; padding:4px 0px;
					height: 20px; text-align: center; vertical-align: middle;">
					<input class="button2" type="submit" value="GUARDAR"></p>';		
	}
?>

<body>
<div class="pagina" style="margin-top: 10px;">
<div class="contenidor_1" style="border: 1px solid #990000;">
<p class='path'> 
><a href='admint.php'>administració</a> 
 >><a class='Estilo2' href='productes.php'>editar, crear, eliminar productes</a> 
<?php echo $head3; ?>
</p>

<?php echo $tit; ?>

<?php


/// Retorns amb POST /////////////////////////////////////////////////////////////
///
/// La  variable GET id3 te tres possiblitats:
/// 1. create: Fa Insert amb els POST
/// 2. edit: fa UPDATE amb els POST
/// 3. elim: DELETE producte
///

if ($que=='create')
{
	$select= "SELECT nom,proveidora FROM productes 
	WHERE nom='".$pnom."' AND proveidora='".$pprov."'";
	$query=mysql_query($select);
	if (!$query) {die('Invalid query: ' . mysql_error());}
	if (mysql_num_rows($query) == 1) 
   {
   	die
   	("<p class='comment'>El producte ".$pnom." de la proveïdora ".$pprov." ja existeix.</p>");
	}
 	else
 	{
 		///Creem la referència///
 		$tpll=substr($pprov, 0, 3);
 		$select7= "SELECT ref FROM productes WHERE ref LIKE '".$tpll."%' ORDER BY ref DESC LIMIT 1";
		$query7=mysql_query($select7);
		if (!$query7) {die('Invalid query7: ' . mysql_error());}
		list($sref)=mysql_fetch_row($query7);
		if ($sref!="")
   	{
			/// Hem d agafar el $sref separar els tres primers caracters i sumar-li un als quatre darrers que son un nombre
			$lletres=substr($pprov, 0, 3);
			$numeros=substr($sref, -4);
			$numerosmesu=sprintf('%04d', $numeros + 1);
			$newref=$lletres.$numerosmesu;
		}
 		else
 		{
			/// Hem de crear-lo: 3 primers caracters del proveidor i quatre darrers 0001
			$lletres=substr($pprov, 0, 3);
			$newref=$lletres."0001";
		}
	
 		$query2= "INSERT INTO productes
 				VALUES ('".$newref."','".$pnom."','".$punitat."','".$pprov."','".$pcat."','".$psubcat."',
 				'".$pactiu."','".$ppreusi."','".$piva."','".$pmarge."','".$pdescompte."','0',
 				'".$pnotes."')";
		mysql_query($query2) or die('Error, insert query2 failed:' . mysql_error());
		die ("<p class='comment'>El producte ".$newref."-".$supernom." s'ha introduït correctament a la base de dades:</p>
			<p class='cos2'>unitat: ".$punitat."</p>
			<p class='cos2'>proveïdora: ".$pprov."</p>
			<p class='cos2'>categoria: ".$pcat."</p>
			<p class='cos2'>subcategoria: ".$psubcat."</p>
			<p class='cos2'>actiu: ".$pactiu."</p>
			<p class='cos2'>preu sense iva: ".$ppreusi."</p>
			<p class='cos2'>iva: ".$piva."</p>
			<p class='cos2'>preu amb iva: ".$ppreu."</p>
			<p class='cos2'>marge: ".$pmarge."</p>
			<p class='cos2'>descompte: ".$pdescompte."</p>
			<p class='cos2'>pvp sense iva: ".$ppvpsi."</p>
			<p class='cos2'>pvp: ".$ppvp."</p>
			<p class='cos2'>pvp amb descompte: ".$ppvpdesc."</p>
			<p class='cos2'>comentaris: ".$pnotes."</p>");
	}
}

if ($que=='edit')
{
	$query3= "UPDATE productes SET unitat='".$punitat."',categoria='".$pcat."', 
	subcategoria='".$psubcat."',actiu='".$pactiu."',preusi='".$ppreusi."',iva='".$piva."',marge='".$pmarge."',
	descompte='".$pdescompte."',notes='".$pnotes."'
	WHERE ref='".$pref."'";
	mysql_query($query3) or die('Error, insert query3 failed');
	die ("<p class='comment'>El producte ".$pref."-".$supernom." ha canviat a les següents dades:</p>
			<p class='cos2'>unitat: ".$punitat."</p>
			<p class='cos2'>proveïdora: ".$gprov."</p>
			<p class='cos2'>categoria: ".$pcat."</p>
			<p class='cos2'>subcategoria: ".$psubcat."</p>
			<p class='cos2'>actiu: ".$pactiu."</p>
			<p class='cos2'>preu sense iva: ".$ppreusi."</p>
			<p class='cos2'>iva: ".$piva."</p>
			<p class='cos2'>preu amb iva: ".$ppreu."</p>
			<p class='cos2'>marge: ".$pmarge."</p>
			<p class='cos2'>descompte: ".$pdescompte."</p>
			<p class='cos2'>pvp sense iva: ".$ppvpsi."</p>
			<p class='cos2'>pvp: ".$ppvp."</p>
			<p class='cos2'>pvp amb descompte: ".$ppvpdesc."</p>
			<p class='cos2'>comentaris: ".$pnotes."</p>");
}

if ($que=='elim')
{
	$query4= "SELECT al.producte, a.proveidora FROM albara_linia AS al, albara AS a
	WHERE a.numero=al.numero AND al.producte='".$gnom."' AND a.proveidora='".$gprov."'";
	$result4 = mysql_query($query4);
	if (!$result4) {die('Invalid query4: ' . mysql_error());}
	if (mysql_num_rows($result4) != 0)
	{
		die
   	("<p class='comment'>El producte ".$gnom." de la proveïdora ".$gprov." ja ha estat utilitzat.</p>
   	<p class='commnet'>Pots desactivar-lo, però no borrar-lo</p>");
	}
	$query5= "SELECT cl.ref, pr.nom, pr.proveidora FROM comanda_linia AS cl, productes AS pr
	WHERE cl.ref=pr.ref AND pr.nom='$gnom' AND pr.proveidora='".$gprov."'";
	$result5 = mysql_query($query5);
	if (!$result5) {die('Invalid query5: ' . mysql_error());}
	if (mysql_num_rows($result5) != 0)
	{
		die
   	("<p class='coment'>El producte ".$gnom." de la proveïdora ".$gprov." ja ha estat utilitzat.</p>
   	<p class='comment'>Pots desactivar-lo, però no borrar-lo</p>");
	}
	
	$query6= "DELETE FROM productes WHERE ref='".$gref."'";
	mysql_query($query6) or die('Error, insert query6 failed');
	die
   	("<p class='comment'>El producte ".$gnom." de la proveïdora ".$gprov." s'ha eliminat de la base de dades.</p>");
}
?>


<?php
///////////////////////////////////////////////////////////////////////////////////////

/// Entrades sense POST ///////////////////////////////////////////////////////////////
///
/// Creem el formulari sense o amb valors
/// Si existeix un GET ($gref)) amb valors ---> Estem editant ///
/// Si no existeix sense valors ---> Estem creant ///

	$readonly="";
	if ($gref!="")
	{
		$select= "SELECT * FROM productes 
		WHERE ref='".$gref."'";
		$query=mysql_query($select);
		if (!$query) {die('Invalid query: ' . mysql_error());}
    
		list($ref,$nom,$unitat,$proveidora,$tipus,$subtipus,$actiu,$preusi,$iva,$marge,$descompte,$estoc,$notes)=mysql_fetch_row($query);
		$readonly="readonly";
		$marge=$marge*100;
		$descompte=$descompte*100;		
	}

?>

<div class="contenidor_fac" style=" border: 1px solid #990000; margin-bottom:20px;">
<table width="80%" align="center">
<form action="<?php echo $formact; ?>" method="post" name="nouprod" id="nouprod" onSubmit="return Validate();">

<tr style="padding-top: 10px;">
<td class="cos_majus">Referencia:</td><td class="cos_majus"><input align="right" name="ref" id="ref" type="TEXT" maxlength="7" size="5"
value="<?php echo $ref; ?>" readonly>
</td></tr>

<tr>
<td class="cos_majus">Nom:</td><td class="cos_majus"><input align="right" name="nom" id="nom" type="TEXT" maxlength="50" size="50"
value="<?php echo $nom; ?>" <?php echo $readonly; ?>>
</td></tr>

<tr><td class="cos_majus">Unitat:</td><td><input align="right" name="unitat" id="unitat" type="TEXT" maxlength="20" size="5"
value="<?php echo $unitat; ?>"></td></tr>
<tr><td class="cos_majus">Proveidora:</td>

<?php
	if ($gprov!="")
	{
		echo '<td>
		<input align="right" name="prov" id="prov" type="TEXT" maxlength="30" size="20" value="'.$gprov.'" readonly>
		</td>';
	}
	else
	{
	echo '<td><SELECT name="prov" id="prov" size="1" maxlenght="30"><option value="">elegeix proveïdora</option>';
	$query= "SELECT nom FROM proveidores ORDER BY nom";
	$result=mysql_query($query);
	if (!$result) { die("Query to show fields from table proveidores failed");}
	while (list($sprov)=mysql_fetch_row($result))
	{
		echo "<option value='".$sprov."'>".$sprov."</option>";
	}
	echo "</SELECT></td>";
	}
?>
</tr>

<tr><td class="cos_majus">Categoria:</td>
<td><SELECT id="tipus" name="tipus" size="1" maxlenght="30" 
onChange="javascript: dropdownlist(this.options[this.selectedIndex].value);">
<option value="">elegeix categoria</option>

<?php
	
	$query= "SELECT tipus FROM categoria ORDER BY tipus";
	$result=mysql_query($query);
	if (!$result) { die("Query to show fields from table tipus_prod failed");}
	while (list($stipus)=mysql_fetch_row($result))
	{
		if ($stipus==$tipus) 
		{
			echo "<option value='".$stipus."' selected>".$stipus."</option>";
		}
		else
		{
			echo "<option value='".$stipus."'>".$stipus."</option>";
		}
	}
?>
</SELECT></td></tr>

<tr><td class="cos_majus">Subcategoria: </td>
<td>
<script type="text/javascript" language="JavaScript">
document.write('<select name="subtipus" id="subtipus"><option value="">elegeix subcategoria</option></select>')
</script>
<noscript>
<select name="subtipus" id="subtipus" >
<option value="">elegeix subcategoria</option>
</select>
</noscript>
</td></tr>

<tr><td class="cos_majus">Actiu:</td>

<?php
$checked1='checked'; $checked2="";
if ($actiu=='baixa') {$checked1=""; $checked2="checked";}
?>
<td>
<INPUT type='radio' name='actiu' value='actiu' <?php echo $checked1; ?>>si</INPUT>
<INPUT type='radio' name='actiu' value='baixa' <?php echo $checked2; ?>>no</INPUT>
</td></tr>

<tr><td class="cos_majus">Preu sense iva:</td>
<td><input align="right" name="preusi" id="preusi" type="TEXT" maxlength="7" size="5" value="<?php echo $preusi; ?>">
</td></tr>

<tr><td class="cos_majus">Iva:</td>
<td><SELECT name="iva" id="iva" size="1" maxlenght="3">

<?php
$sele0=""; $sele1=""; $sele2=""; $sele3="";
if ($iva==0) $sele0="selected";
if ($iva==0.21) $sele1="selected";
if ($iva==0.10) $sele2="selected";
if ($iva==0.04) $sele3="selected";
?>

<option value="0" <?php echo $sele0; ?>>sense iva</option>
<option value="0.21" <?php echo $sele1; ?>>21%</option>
<option value="0.10" <?php echo $sele2; ?>>10%</option>
<option value="0.04" <?php echo $sele3; ?>>4%</option>
</SELECT>
</td></tr>

<?php
if ($marge=="") {$marge=0;}
if ($descompte=="") {$descompte=0;}
?>

<tr><td class="cos_majus">Marge:</td>
<td><input align="right" name="marge" id="marge" type="TEXT" maxlength="7" size="5" value="<?php echo $marge; ?>">%
</td></tr>

<tr><td class="cos_majus">Descompte:</td>
<td><input align="right" name="descompte" id="descompte" type="TEXT" maxlength="7" size="5" value="<?php echo $descompte; ?>">%
</td></tr>

<?php
if ($estoc!="")
{
?>
<tr><td class="cos_majus">Estoc:</td>
<td><input align="right" name="estoc" id="estoc" type="TEXT" maxlength="7" 
size="5" value="<?php echo $estoc; ?>" readonly>
</td></tr>
<?php
}
?>

<tr><td class="cos_majus">Comentaris:</td>
<td><input align="right" name="notes" id="notes" type="TEXT" maxlength="255" size="35" value="<?php echo $notes; ?>">
</td></tr>
</table> 

<?php echo $buteli; ?>
</div>

<p class="cos2" style="clear: both; text-align: center; padding: 0px 100px;">
<?php echo $subtit; ?>
</p>
</div></div>
</body>
</html>


<?php
include 'config/disconect.php';
} 
else {
header("Location: index.php"); 
}
?>