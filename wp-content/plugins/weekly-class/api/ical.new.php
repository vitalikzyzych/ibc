<?php

class WCS_iCal{

  private $_ical_name = 'wcs_ical';

  public function __construct(){
    add_action('init', array($this, 'init') );
  }

  function init(){
    add_feed( 'wcs_ical', array( $this, 'index' ));
  }

  function index(){

    $start 		  = $this->dateToCal( filter_var( $_GET['start'], FILTER_SANITIZE_NUMBER_INT ) );
    $end		    = $this->dateToCal( filter_var( $_GET['end'], FILTER_SANITIZE_NUMBER_INT ) );
    $subject    = $this->escapeString(filter_var( urldecode( $_GET['subject'] ), FILTER_SANITIZE_SPECIAL_CHARS ) );
    $url	   	  = filter_var( urldecode( $_GET['url'] ), FILTER_SANITIZE_URL );
    $desc	   	  = $this->escapeString( filter_var( urldecode( $_GET['desc'] ), FILTER_SANITIZE_SPECIAL_CHARS ) );
    $name	   	  = filter_var( urldecode( $_GET['name'] ), FILTER_SANITIZE_SPECIAL_CHARS );
    $host	   	  = $this->escapeString( parse_url( $url, PHP_URL_HOST ) );
    $location	  = $this->escapeString( esc_html( $_GET['location'] ) );
    $id         = uniqid();
    $timestamp  = $this->dateToCal( time() );

    header("Content-type: text/calendar; charset=utf-8");
		header("Content-Disposition: attachment; filename={$name}.ics");

echo
"BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
DTEND:{$end}
UID:{$id}
DTSTAMP:{$timestamp}
LOCATION:{$location}
DESCRIPTION:{$desc}
URL;VALUE=URI:{$host}
SUMMARY:{$subject}
DTSTART:{$start}
END:VEVENT
END:VCALENDAR";

    exit;

  }

  function dateToCal($timestamp) {
    return date('Ymd\THis\Z', $timestamp);
  }

  function escapeString($string) {
    return preg_replace('/([\,;])/','\\\$1', $string);
  }


}
new WCS_iCal();
?>
