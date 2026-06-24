<?php

// Librairies Luan -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// = mkTable, j'avais envie d'essayer moi même xD
function mkTableBody($panier) {
	foreach ($panier as $item) {
		echo "<tr>\n";
		foreach ($item as $champ => $val) {
			if ($champ != "id") echo "\t<td>$val</td>\n";
		}
		echo "</tr>\n";
	}
}

// Librairies TWE -------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/*
Ce fichier définit diverses fonctions permettant de faciliter la production de mises en formes complexes : 
tableaux, formulaires, ...
*/
// Exemple d'appel :  mkLigneEntete($data,array('pseudo', 'couleur', 'connecte'));
function mkLigneEntete($tabAsso,$listeChamps=false)
{

	if (!$listeChamps)
	{
		echo "\t<tr>\n";
		foreach ($tabAsso as $cle => $val)	
		{
			echo "\t\t<th>$cle</th>\n";
		}
		echo "\t</tr>\n";
	}
	else
	{
		echo "\t<tr>\n";
		foreach ($listeChamps as $nomChamp)	
		{
			echo "\t\t<th>$nomChamp</th>\n";
		}
		echo "\t</tr>\n";
	}
}

function mkLigne($tabAsso,$listeChamps=false)
{
	if (!$listeChamps)
	{
		echo "\t<tr>\n";
		foreach ($tabAsso as $cle => $val)	
		{
			echo "\t\t<td>$val</td>\n";
		}
		echo "\t</tr>\n";
	}
	else
	{
		echo "\t<tr>\n";
		foreach ($listeChamps as $nomChamp)	
		{
			echo "\t\t<td>$tabAsso[$nomChamp]</td>\n";
		}
		echo "\t</tr>\n";
	}
}
function mkTable($tabData,$listeChamps=false)
{

	if (count($tabData) == 0) return;

	echo "<table border=\"1\">\n";
	mkLigneEntete($tabData[0],$listeChamps);
	foreach ($tabData as $data)	
	{
		mkLigne($data,$listeChamps);
	}
	echo "</table>\n";	
}

// Produit un menu déroulant portant l'attribut name = $nomChampSelect

// Produit les options d'un menu déroulant à partir des données passées en premier paramètre
// $champValue est le nom des cases contenant la valeur à envoyer au serveur
// $champLabel est le nom des cases contenant les labels à afficher dans les options
// $selected contient l'identifiant de l'option à sélectionner par défaut
// si $champLabel2 est défini, il indique le nom d'une autre case du tableau 
// servant à produire les labels des options

// exemple d'appel : 
// $users = listerUtilisateurs("both");
// mkSelect("idUser",$users,"id","pseudo");
// TESTER AVEC mkSelect("idUser",$users,"id","pseudo",2,"couleur");

function mkSelect($nomChampSelect, $tabData,$champValue, $champLabel,$selected=false,$champLabel2=false)
{

	$multiple=""; 
	if (preg_match('/.*\[\]$/',$nomChampSelect)) $multiple =" multiple =\"multiple\" ";

	echo "<select $multiple name=\"$nomChampSelect\">\n";
	foreach ($tabData as $data)
	{
		$sel = "";	
		if ( ($selected) && ($selected == $data[$champValue]) )
			$sel = "selected=\"selected\"";

		echo "<option $sel value=\"$data[$champValue]\">\n";
		echo  $data[$champLabel] . "\n";
		if ($champLabel2) 	
			echo  " ($data[$champLabel2])\n";
		echo "</option>\n";
	}
	echo "</select>\n";
}

function mkForm($action="",$method="get")
{
	echo "<form action=\"$action\" method=\"$method\" >\n";
}
function endForm()
{
	echo "</form>\n";
}

function mkInput($type,$name,$value="")
{
	echo "<input type=\"$type\" name=\"$name\" value=\"$value\"/>\n";
}

function mkRadioCb($type,$name,$value,$checked=false)
{
	$selectionne = "";	
	if ($checked) 
		$selectionne = "checked=\"checked\"";
	echo "<input type=\"$type\" name=\"$name\" value=\"$value\"  $selectionne />\n";
}

function mkLien($url,$label, $qs="")
{
	echo "<a href=\"$url?$qs\">$label</a>\n";
}

function mkLiens($tabData,$champLabel, $champCible, $urlBase=false, $nomCible="")
{
	foreach($tabData as $nextData) {
	
		if ($urlBase)
			echo "<a href=\"$urlBase&$nomCible=$nextData[$champCible]\">";
		else 
			echo "<a href=\"$nextData[$champCible]\">"; 
		echo $nextData[$champLabel];
		echo "</a> <br/>";
	
	}
}
?>

















