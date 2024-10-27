<?php
/*
Plugin Name: Amazon Einzeltitellink
Plugin URI:http://www.diewebmaster.it/amazon-einzeltitellinks-plugin-wordpress/
Description: Dieses Plugin erm&ouml;glicht die einfache Integration von Amazon Einzeltitellinks mit Text und Grafik.
Author: Dietmar Mitterer-Zublasing
Author URI: http://www.compusol.it/
Version: 1.3.3
License: This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

Copyright 2008 Compusol des P.I. Mitterer-Zublasing Dietmar

Achtung ich &uuml;berneheme keine Haftung wenn durch dein Einsatz dieses Plugins Sch&auml;den finanzieller oder ideeller oder jeglicher anderer Art entstehen. Der Einsatz dieses Plugin erfolgt auf eigene Gefahr!

Installationshinweise siehe: http://www.diewebmaster.it/amazon-einzeltitellinks-plugin-wordpress/


*/
// Changes to the new Amazon Code
// Replace amazon Tags in Content with Amazon Links



function amazonContent($inhalt)
{
	$spos=0;
	$epos=0;
	
	$neuer_inhalt=$inhalt;
	
	while($spos=strpos($neuer_inhalt, '[aartikel]'))		// find each amazon tag
	{
		$epos=strpos($neuer_inhalt, '[/aartikel]');
		
		if($spos>0 & $epos>0)
		{
			$sub=substr($neuer_inhalt, $spos, $epos-$spos);	// extract the begin tag and parameters from contents
			$sub=str_replace('[aartikel]', '', $sub);	// remove the begin tag

			#list($myASIN,$myFloat,$Partner_ID_Element)=split(":", $sub);	
			list($myASIN,$myFloat,$Partner_ID_Element)=explode(":", $sub);	

			// set default values for missing parameters
			if(($myFloat!='left') && ($myFloat!='right')) $myFloat='none';

            $Partner_ID="compusol-21"; 
            if(get_option('amazon_PartnerID')!='') $Partner_ID=get_option('amazon_PartnerID');
			if($Partner_ID_Element!='') $Partner_ID=$Partner_ID_Element; 
			$amazon_einzeltitel_links=get_option('amazon_einzeltitel_links');
			if($amazon_einzeltitel_links=='') $amazon_einzeltitel_links="float:left; width:120px; margin-right:10px; height:240px; background-color:#FFFFFF; border:1px solid #E0E0E0";
	 		$amazon_einzeltitel_rechts=get_option('amazon_einzeltitel_rechts');
            if($amazon_einzeltitel_rechts=='') $amazon_einzeltitel_rechts="float:right; width:120px; margin-left:10px; height:240px; background-color:#FFFFFF; border:1px solid #E0E0E0";
			$amazon_einzeltitel_none=get_option('amazon_einzeltitel_none');
            if($amazon_einzeltitel_none=='') $amazon_einzeltitel_none="width:100%; height:240px; background-color:#FFFFFF; border:1px solid #E0E0E0";
            $amazon_einzeltitel_link=get_option('amazon_einzeltitel_link');
			if($amazon_einzeltitel_link=='_top') $amazon_einzeltitel_link="false"; else $amazon_einzeltitel_link="true"; 
             $amazon_einzeltitel_rahmen=get_option('amazon_einzeltitel_rahmen');
			if($amazon_einzeltitel_rahmen=='') $amazon_einzeltitel_rahmen="false"; else $amazon_einzeltitel_rahmen="true";
            $amazon_einzeltitel_titelfarbe=get_option('amazon_einzeltitel_titelfarbe');
			if($amazon_einzeltitel_titelfarbe=='') $amazon_einzeltitel_titelfarbe="0066C0";
            $amazon_einzeltitel_preisfarbe=get_option('amazon_einzeltitel_preisfarbe');
			if($amazon_einzeltitel_preisfarbe=='') $amazon_einzeltitel_preisfarbe="333333";
			$amazon_einzeltitel_hintergrundfarbe=get_option('amazon_einzeltitel_hintergrundfarbe');
			if($amazon_einzeltitel_hintergrundfarbe=='') $amazon_einzeltitel_hintergrundfarbe="FFFFFF";

			// build the link based on the ad type
			
			$src='//ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=DE&source=ac&ref=tf_til&ad_type=product_link&tracking_id='.$Partner_ID.'&marketplace=amazon&region=DE&placement='.$myASIN.'&asins='.$myASIN.'
			&show_border='.$amazon_einzeltitel_rahmen.'&link_opens_in_new_window='.$amazon_einzeltitel_link.'&price_color='.$amazon_einzeltitel_preisfarbe.'&title_color='.$amazon_einzeltitel_titelfarbe.'&bg_color='.$amazon_einzeltitel_hintergrundfarbe;				
			
			switch($myFloat)
			{
				case 'left':
					$link = '<iframe scrolling="no" frameBorder="0" src="'.$src.'" marginheight="0" marginwidth="0" style="'.$amazon_einzeltitel_links.'" class="amazon-einzeltitel"></iframe>';
					break;					
				case 'right':
					$link = '<iframe scrolling="no" frameBorder="0" src="'.$src.'" marginheight="0" marginwidth="0" style="'.$amazon_einzeltitel_rechts.'" class="amazon-einzeltitel"></iframe>';
					break;	
				case 'none':
					$link = '<iframe scrolling="no" frameBorder="0" src="'.$src.'" marginheight="0" marginwidth="0" style="'.$amazon_einzeltitel_none.'" class="amazon-einzeltitel"></iframe>';
					break;						
			}
			$neuer_inhalt=str_replace('[aartikel]'.$sub.'[/aartikel]', $link, $neuer_inhalt);
		}
	}
	return $neuer_inhalt;
}

// Admin Options Page
function amazonOptionsPage()
{
	if(isset($_POST['amazonUpdate']))
	{
		$Partner_ID=$_POST["BenutzerID"];
		$amazon_einzeltitel_links=$_POST["amazon_einzeltitel_links_name"];
		$amazon_einzeltitel_rechts=$_POST["amazon_einzeltitel_rechts_name"];
		$amazon_einzeltitel_none=$_POST["amazon_einzeltitel_none_name"];
		$amazon_einzeltitel_link=$_POST["amazon_einzeltitel_link_name"];
		$amazon_einzeltitel_rahmen=$_POST["amazon_einzeltitel_rahmen_name"];
		$amazon_einzeltitel_titelfarbe=$_POST["amazon_einzeltitel_titelfarbe_name"];
		$amazon_einzeltitel_preisfarbe=$_POST["amazon_einzeltitel_preisfarbe_name"];
		$amazon_einzeltitel_hintergrundfarbe=$_POST["amazon_einzeltitel_hintergrundfarbe_name"];
		
		update_option('amazon_PartnerID', $Partner_ID);
		update_option('amazon_einzeltitel_links', $amazon_einzeltitel_links);
		update_option('amazon_einzeltitel_rechts', $amazon_einzeltitel_rechts);		
		update_option('amazon_einzeltitel_none', $amazon_einzeltitel_none);
		update_option('amazon_einzeltitel_link', $amazon_einzeltitel_link);	
		update_option('amazon_einzeltitel_rahmen', $amazon_einzeltitel_rahmen);
		update_option('amazon_einzeltitel_titelfarbe', $amazon_einzeltitel_titelfarbe);
		update_option('amazon_einzeltitel_preisfarbe', $amazon_einzeltitel_preisfarbe);	
		update_option('amazon_einzeltitel_hintergrundfarbe', $amazon_einzeltitel_hintergrundfarbe);			
?>

<div class="updated fade" id="message" style="background-color: rgb(207, 235, 247);">
  <p><strong>Options saved.</strong></p>
</div>
<?php
	}
	else
	{
		$Partner_ID=get_option('amazon_PartnerID');
		$amazon_einzeltitel_links=get_option('amazon_einzeltitel_links');
		$amazon_einzeltitel_rechts=get_option('amazon_einzeltitel_rechts');
		$amazon_einzeltitel_none=get_option('amazon_einzeltitel_none');
		$amazon_einzeltitel_link=get_option('amazon_einzeltitel_link');		
		$amazon_einzeltitel_rahmen=get_option('amazon_einzeltitel_rahmen');
		$amazon_einzeltitel_titelfarbe=get_option('amazon_einzeltitel_titelfarbe');
		$amazon_einzeltitel_preisfarbe=get_option('amazon_einzeltitel_preisfarbe');
		$amazon_einzeltitel_hintergrundfarbe=get_option('amazon_einzeltitel_hintergrundfarbe');	
	}

?>
<div class="wrap">
  <h2>amazon</h2>
  <form method="POST">
    <table class="optiontable">
      <tr valign="top">
        <th>PartnerID</th>
        <td><input id="BenutzerID" name="BenutzerID" type="text" value="<?php echo $Partner_ID; ?>">
          <br>
          Die Partner ID bekommst du indem du dich unter https://partnernet.amazon.de anmeldest! Achtung, wenn du dieses Feld leer l&auml;sst, dann wird die Partner ID <strong>compusol-21</strong> eingesetzt und die Verkaufsprovisionen werden mir gutgeschrieben!</td>
      </tr>
      <tr valign="top">
        <th>Style f&uuml;r Float Links</th>
        <td><input name="amazon_einzeltitel_links_name" type="text" id="amazon_einzeltitel_links_name" value="<?php echo $amazon_einzeltitel_links; ?>" size="80">
          <br>
          CSS Anweisungen f&uuml;r Einzeltitellink der links gefloatet werden soll.  Du kennst dich nicht mit CSS aus? Kein Problem! Lasse das Feld leer. Es wird dann diese Anweisung: <strong>float:left; width:120px; margin-right:10px; height:240px; background-color:#FFFFFF; border:1px solid #E0E0E0</strong> verwendet.</td>
      </tr>
      <tr valign="top">
        <th>Style f&uuml;r Float Rechts</th>
        <td><input name="amazon_einzeltitel_rechts_name" type="text" id="amazon_einzeltitel_rechts_name" value="<?php echo $amazon_einzeltitel_rechts; ?>" size="80">
          <br>
          CSS Anweisungen f&uuml;r Einzeltitellink der rechts gefloatet werden soll.  Du kennst dich nicht mit CSS aus? Kein Problem! Lasse das Feld leer. Es wird dann diese Anweisung: <strong>float:right; width:120px; margin-left:10px; height:240px; background-color:#FFFFFF; border:1px solid #E0E0E0</strong> verwendet.</td>
      </tr>
      <tr valign="top">
        <th>Style f&uuml;r kein Float</th>
        <td><input name="amazon_einzeltitel_none_name" type="text" id="amazon_einzeltitel_none_name" value="<?php echo $amazon_einzeltitel_none; ?>" size="80">
          <br>
          CSS Anweisungen f&uuml;r Einzeltitellink der nicht gefloatet werden soll.  Du kennst dich nicht mit CSS aus? Kein Problem! Lasse das Feld leer. Es wird dann diese Anweisung: <strong>width:100%; height:240px; background-color:#FFFFFF; border:1px solid #E0E0E0</strong> verwendet.</td>
      </tr>
      <tr valign="top">
        <th>Link in neuem Fenster?</th>
        <td><input id="amazon_einzeltitel_link_name" name="amazon_einzeltitel_link_name" type="text" value="<?php echo $amazon_einzeltitel_link; ?>">
          <br>
          Schreibe hier <strong>_blank</strong> (Link im neuen Fenster) oder <strong>_top</strong> (Link im gleichen Fenster) hinein. Wenn du das Feld leer l&auml;sst, wird <strong>_blank</strong> (Link im neuen Fenster) verwendet.</td>
      </tr>
      <tr valign="top">
        <th>Rahmen verwenden?</th>
        <td><input id="amazon_einzeltitel_rahmen_name" name="amazon_einzeltitel_rahmen_name" type="text" value="<?php echo $amazon_einzeltitel_rahmen; ?>">
          <br>
          Schreibe hier <strong>true</strong> oder <strong>false</strong> hinein. Wenn du das Feld leer l&auml;sst, wird <strong>false</strong> verwendet. Dieser hellgraue dünne Rahmen wird von Amazon gesetzt, hat also nichts mit obiger Style Anweisung zu tun. Hast du mittels Style bereits einen Rahmen definiert, so wird mit dieser Option ein zusätzlicher Rahmen innerhalb definiert, dessen Eigenschaften aber nicht beeinflusst werden können.</td>
      </tr>
      <tr valign="top">
        <th>Textfarbe</th>
        <td><input id="amazon_einzeltitel_titelfarbe_name" name="amazon_einzeltitel_titelfarbe_name" type="text" value="<?php echo $amazon_einzeltitel_titelfarbe; ?>">
          <br>
          Schreibe hier den Hexadezimalcode ohne # f&uuml;r die Textfarbe hinein, also z.B. <strong>0066C0</strong> f&uuml;r Blau. Wenn du das Feld leer l&auml;sst, wird 0066C0 verwendet.</td>
      </tr>
      <tr valign="top">
        <th>Linkfarbe</th>
        <td><input id="amazon_einzeltitel_preisfarbe_name" name="amazon_einzeltitel_preisfarbe_name" type="text" value="<?php echo $amazon_einzeltitel_preisfarbe; ?>">
          <br>
          Schreibe hier den hecadezimalcode ohne # f&uuml;r die Textfarbe hinein, also z.B. <strong>333333</strong> f&uuml;r Grau.Wenn du das Feld leer l&auml;sst, wird 333333 verwendet.</td>
      </tr>
      <tr valign="top">
        <th>Art</th>
        <td><input id="amazon_einzeltitel_hintergrundfarbe_name" name="amazon_einzeltitel_hintergrundfarbe_name" type="text" value="<?php echo $amazon_einzeltitel_hintergrundfarbe; ?>">
          <br>
          Schreibe hier den hecadezimalcode ohne # f&uuml;r die Hintergrundfarbe hinein, also z.B. <strong>FFFFFF</strong> f&uuml;r Weiss. Wenn du das Feld leer l&auml;sst, wird FFFFFF verwendet.</td>
      </tr>
      <tr valign="top">
        <td>&nbsp;</td>
        <td><input name="amazonUpdate" type="submit" value="Speichern"></td>
      </tr>
    </table>
  </form>
  <h3>Einbindung des Amazon Einzeltitel Links</h3>
  <p>Um den Amazon Einzeltitel-Link in deinen Weblog einzubinden, musst du folgenden Tag in deinem Wordpressartikel eingeben:</p>
  <code>[aartikel]myASIN:myFloat:Partner_ID[/aartikel]</code>
  <p>wobei die beiden Parameter <strong>myFloat</strong> und <strong>Partner_ID</strong> optinal sind...</p>
  <ul>
    <li><b>myASIN:</b> Hier musst du die ISBN Nummer oder ASIN Nummer des Amazon Artikel eingeben.</li>
    <li><b>myFloat:</b> Hier kannst du <b>left</b> oder <b>right</b> angeben oder den Parameter weglassen.  Dadurch bestimmst du ob der Amazon Artikel nach links, rechts oder nicht gefloatet werden soll.</li>
    <li><b>Partner_ID:</b> Wenn sich die gew&uuml;nschte Partner_ID von der in den Einstellungen eingegebenen Partner_ID unterscheiden soll, kannst du sie mit diesem Parameter angeben.</li>
  </ul>
  <p></p>
  <h4>Beispiele</h4>
  <p>1. Einzelartikel Link ohne Float eingeben</p>
  <code>[aartikel]3835402617[/aartikel]</code>
  <p>Dadurch wird der Einzeltitellink mit der ASIN 3835402617 eingef&uuml;gt. Es findet keine Textumfluss statt.</p>
  <hr>
  <p>2. Einzelartikellink mit Float eingeben</p>
  <code>[aartikel]3835402617:left[/aartikel]</code>
  <p>Dadurch wird der Einzeltitellink mit der ASIN 3835402617 eingef&uuml;gt. Es findet ein Textumfluss float:left statt.</p>
  <hr>
  <p>3. Einzelartikellink mit Float und anderer Partner_IDeingeben</p>
  <code>[aartikel]3835402617:left:compusol-21[/aartikel]</code>
  <p>Dadurch wird der Einzeltitellink mit der ASIN 3835402617 eingef&uuml;gt. Es findet ein Textumfluss float:left statt. Es wird die Partner-ID compusol-21 verwendet, auch wenn in den Einstellungen eine andere Partner ID eingegeben wurde.</p>
  <hr>
</div>
<div class="wrap">
  <h2>Weitere Informationen</h2>
  <p>Eventuelle Updates findest du hier: <a href="http://www.diewebmaster.it/amazon-einzeltitellinks-plugin-wordpress/" target="_blank">http://www.diewebmaster.it/amazon-einzeltitellinks-plugin-wordpress/</a>.
  <p>
  <p>Die Homepage des Autors findest du unter: <a href="http://www.compusol.it" target="_blank">www.compusol.it</a></p>
</div>
<?php
}

// Add Options Page
function amazonAdminSetup()
{
	add_options_page('Amazon Einzeltitel', 'Amazon Einzeltitel', 8, basename(__FILE__), 'amazonOptionsPage');	
}

// Load amazon Actions
if (function_exists('add_action'))
{
	add_action('the_content', 'amazonContent');
	add_action('admin_menu', 'amazonAdminSetup');
}

?>
