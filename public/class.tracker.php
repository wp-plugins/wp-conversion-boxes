<?php

//start session
class WPCB_Tracker {

        protected $filter = array();

        /*********************************
	 * Instance of this class.
	 *********************************/
	protected static $instance = null;
                
	/*********************************
	 * Initialize the tracker.
	 *********************************/

        private function __construct(){
            
                $wpcb_public = WPCB_Public::get_instance();
                $this->wpcb_boxes_table = $wpcb_public->get_boxes_table_name();
                $this->wpcb_tracking_table = $wpcb_public->get_tracking_table_name();
                
                add_action( 'wp_ajax_flush_stats', array( $this, 'flush_stats') );
                add_action( 'wp_ajax_fetch_graph_data', array( $this, 'fetch_graph_data') );
                
                add_action( 'wp_ajax_update_visit_type', array( $this, 'update_visit_type') );
                add_action( 'wp_ajax_nopriv_update_visit_type', array( $this, 'update_visit_type') );
                
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        }
        
	/***************************************
	 * Return an instance of this class.
	 ***************************************/
	public static function get_instance() {

                if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}        

	/***************************************
	 * Register and enqueues public-facing 
         * JavaScript files.
	 ***************************************/
	public function enqueue_scripts() {
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_script( 'tracker-js', PUBLIC_ASSETS_URL.'js/tracker.js' , '', '', true);
                wp_localize_script( 'tracker-js', 'wpcbLocalizedData', array(
		    'ajaxurl' => admin_url( 'admin-ajax.php' ),
		    'nonce' => wp_create_nonce( 'ajax-example-nonce' ),
                    'gaTracking' => get_option('wpcb_ga_tracking')
		) );
                wp_enqueue_script( 'jQuery-visible', PUBLIC_ASSETS_URL.'js/jquery.visible.min.js');
	}   
        
	/***************************************
	 * Flush stats of a particular box
	 ***************************************/
        
        function flush_stats(){
                global $wpdb;
                $wpcb_id = $_POST['wpcb_id'];
                if($wpdb->delete( $this->wpcb_tracking_table, array( 'box_id' => $wpcb_id ) )){
                    echo $wpcb_id;
                }
                else 
                    echo 0;
                die();        
        }

        /****************************************
         * Update visit type according to 
         * frontend activity.
         ***************************************/
        
        function update_visit_type(){
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;
                $visittype = $_POST['newvisittype'];
                $id = $_POST['id'];
                
                $wpcb_if_done = $wpdb->update($wpcb_tbl_name, array('visittype' => $visittype), array('id' => $id), array('%s'), array('%d'));

                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                die();
        }
        
        /***************************************
	 * Log new visit to DB
	 ***************************************/        
        
        function log_new_visit($box_id){
            
                //get IP
            
                $ip = $_SERVER['REMOTE_ADDR'];
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {               // check ip from share internet
                        $ip=$_SERVER['HTTP_CLIENT_IP'];
                } 
                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   // to check ip is pass from proxy
                        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                $ips = explode(",", $ip);
                $ip = $ips[0];
                
                //get host
                
                $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                
                //get visited page
                
                $visitedpage = 'http';
                if( isset($_SERVER["HTTPS"]) ) {
                        if ($_SERVER["HTTPS"] == "on") {$visitedpage .= "s";}
                }
                $visitedpage .= "://";
                if ($_SERVER["SERVER_PORT"] != "80") {
                        $visitedpage .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
                } else {
                        $visitedpage .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                }
                
                //get referring page

                $referring = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : '';
                
                $visittype = 'visit';
                
                //log visit

                if (!in_array($ip, $this->filter) &&  ! is_admin()) {
                    global $wpdb;
                    $wpcb_tbl_name = $this->wpcb_tracking_table;
                    $visitdate = current_time( 'mysql' ); 
                    $wpdb->insert($wpcb_tbl_name , array('ip' => $ip, 'host' => $host, 'visitdate' => $visitdate, 'visitedpage' => $visitedpage, 'referring' => $referring, 'visittype' => $visittype, 'box_id' => $box_id));
                    echo '<div class="wpcb-tracker" data-id="'.$wpdb->insert_id.'" data-boxid="'.$box_id.'" data-visitedpage="'.$visitedpage.'" data-visittype="'.$visittype.'"></div>';
                }

        }

        // Return lifetime page views of requested box
        
        function page_views($box_id){
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;
                $wpdb->get_results("SELECT ip FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' ");
                $pageviews = $wpdb->num_rows;
                return $pageviews;
        }
        
        // Return lifetime unique visitors
        
        function unique_visitors($box_id){
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;
                $wpdb->get_results("SELECT DISTINCT ip FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' ");
                $uniquevisitors = $wpdb->num_rows;
                return $uniquevisitors;
        }
        
        // Unique Box Views
        
        function box_views($box_id){
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;
                $wpdb->get_results("SELECT ip FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' AND visittype != 'visit'");
                $boxviews = $wpdb->num_rows;
                return $boxviews;
        }
        
        // Conversion 
        
        function get_conversions($box_id){
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;
                $wpdb->get_results("SELECT ip FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' AND (visittype = 'click' OR visittype = 'optin')");
                $uniqueboxviews = $wpdb->num_rows;
                return $uniqueboxviews;
        }
        
        // Get Stats for Graph
        
        function get_stats_for_graph($box_id, $startdate, $enddate){
            
            $startingdate = DateTime::createFromFormat('m/d/Y H:i:s', $startdate.' 00:00:00');
            $startdate = date_format($startingdate, 'Y-m-d H:i:s');
            
            $endingdate = DateTime::createFromFormat('m/d/Y H:i:s', $enddate.' 23:59:59');
            $enddate = date_format($endingdate, 'Y-m-d H:i:s');
            
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_tracking_table;
            
            $uniquevisitors = $wpdb->get_results("SELECT visitdate, COUNT(DISTINCT ip) as uniquevisitors FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' AND visitdate BETWEEN ' $startdate ' AND ' $enddate ' GROUP BY DATE(visitdate)","ARRAY_A");
            $pageviews = $wpdb->get_results("SELECT visitdate, COUNT(*) as pageviews FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' AND visitdate BETWEEN ' $startdate ' AND ' $enddate ' GROUP BY DATE(visitdate)","ARRAY_A");
            $boxviews = $wpdb->get_results("SELECT visitdate, COUNT(*) as boxviews FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' AND visittype != 'visit' AND visitdate BETWEEN ' $startdate ' AND ' $enddate ' GROUP BY DATE(visitdate)","ARRAY_A");
            $conversions = $wpdb->get_results("SELECT visitdate, COUNT(*) as conversions FROM $wpcb_tbl_name WHERE box_id = ' $box_id ' AND (visittype = 'click' OR visittype = 'optin') AND visitdate BETWEEN ' $startdate ' AND ' $enddate ' GROUP BY DATE(visitdate)","ARRAY_A");

            $finaluniquevisitors = "";
            $finalpageviews = "";
            $finalboxviews = "";
            $finalconversions = "";

            while($startdate <= $enddate){
                
                $uniquevisitors = ($uniquevisitors) ? $uniquevisitors : array(array( 'visitdate' => '', 'uniquevisitors' => 0 ));
                $pageviews = ($pageviews) ? $pageviews : array(array( 'visitdate' => '', 'pageviews' => 0 ));
                $boxviews = ($boxviews) ? $boxviews : array(array( 'visitdate' => '', 'boxviews' => 0 ));
                $conversions = ($conversions) ? $conversions : array(array( 'visitdate' => '', 'conversions' => 0 ));
                
                foreach($uniquevisitors as $uniquevisitor){
                    $uniquevisitor['visitdate'] = date("Y-m-d 00:00:00",strtotime($uniquevisitor['visitdate']));

                    if(in_array($startdate,$uniquevisitor)){
                        $visitdate = strtotime($uniquevisitor['visitdate'])*1000;
                        $newuniquevisitor = "[".$visitdate.",".$uniquevisitor['uniquevisitors']."],";
                        break;
                    }
                    else{
                        $newtime = strtotime($startdate)*1000;
                        $newuniquevisitor = "[".$newtime.",0],";
                    }
                }
                foreach($pageviews as $pageview){
                    $pageview['visitdate'] = date("Y-m-d 00:00:00",strtotime($pageview['visitdate']));
                    if(in_array($startdate,$pageview)){
                        $visitdate = strtotime($pageview['visitdate'])*1000;
                        $newpageview = "[".$visitdate.",". $pageview['pageviews']."],";
                        break;
                    }
                    else{
                        $newtime = strtotime($startdate)*1000;
                        $newpageview = "[".$newtime.",0],";
                    }
                }
                foreach($boxviews as $boxview){
                    $boxview['visitdate'] = date("Y-m-d 00:00:00",strtotime($boxview['visitdate']));

                    if(in_array($startdate,$boxview)){
                        $visitdate = strtotime($boxview['visitdate'])*1000;
                        $newboxview = "[".$visitdate .",". $boxview['boxviews']."],";
                        break;
                    }
                    else{
                        $newtime = strtotime($startdate)*1000;
                        $newboxview = "[".$newtime.",0],";
                    }

                }
                foreach($conversions as $conversion){
                    $conversion['visitdate'] = date("Y-m-d 00:00:00",strtotime($conversion['visitdate']));

                    if(in_array($startdate,$conversion)){
                        $visitdate = strtotime($conversion['visitdate'])*1000;
                        $newconversion = "[".$visitdate.",". $conversion['conversions']."],";
                        break;
                    }
                    else{
                        $newtime = strtotime($startdate)*1000;
                        $newconversion = "[".$newtime.",0],";
                    }
                }

                $finalpageviews = $finalpageviews.$newpageview;
                $finaluniquevisitors = $finaluniquevisitors.$newuniquevisitor;
                $finalboxviews = $finalboxviews.$newboxview;
                $finalconversions = $finalconversions.$newconversion;

                $startdate = date("Y-m-d H:i:s", strtotime("{$startdate} + 1 day"));
            }

            $graphdata['pageviews'] = $finalpageviews;
            $graphdata['uniquevisitors'] = $finaluniquevisitors;
            $graphdata['boxviews'] = $finalboxviews;
            $graphdata['conversions'] = $finalconversions;
            
            
            return $graphdata;
        }
        
        //show total uniqe visitors to date(parameter: (int) number of months). For example: 3 will start counting from 3 monhts before today.
        function total_unique($month) {
            
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;
                $lastmonth = mktime(01, 01, 01, date("m") - $month, date("d"), date("Y"));
                $from = date("Y-m-d H:i:s", $lastmonth);
                $to = date("Y-m-d H:i:s");
                $wpdb->get_results("SELECT DISTINCT ip, visitdate, host FROM $wpcb_tbl_name WHERE visitdate BETWEEN ' $from ' AND ' $to ' GROUP BY ip");
                $totalunique = $wpdb->num_rows;
                return $totalunique;
            
        }

        //show total visits to date(parameter: (int) number of months). For example: 3 will start counting from 3 monhts before today.
        function total_hits($month) {
            
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;            
                $lastmonth = mktime(01, 01, 01, date("m") - $month, date("d"), date("Y"));
                $from = date("Y-m-d H:i:s", $lastmonth);
                $to = date("Y-m-d H:i:s");
                $wpdb->get_results("SELECT ip, visitdate, host FROM $wpcb_tbl_name WHERE visitdate BETWEEN ' $from ' AND ' $to '");
                $totalhitsmonth = $wpdb->num_rows;
                return $totalhitsmonth;
                
        }

        //show daily visits for a specific month / year and div in which to laod the graph
        function daily_hits($month, $year, $divid, $tableid) {
        
                //current month and year
            
                if ($month == "") {
                    $month = date("m");
                }
                if ($year == "") {
                    $year = date("Y");
                }

                //get number of days
                
                $numdaysinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                echo '<table cellpadding="0" cellspacing="0" border="0" class="' . $tableid . '" >
                    <tfoot>
                    <tr>';

                for ($x = 1; $x <= $numdaysinmonth; $x++) {
                    echo '<th>' . $x . '</th>';
                }

                echo '</tr>
                    </tfoot>
                    <tbody>
                    <tr>';
                
                $totalhits = 0;
                
                global $wpdb;
                
                for ($y = 1; $y <= $numdaysinmonth; $y++) {
                    if ($y < 10) {
                        $b = '0' . $y;
                    } else {
                        $b = $y;
                    }
                    $current_month = date('Y-m') . '-' . $b;
                
                    $wpcb_tbl_name = $this->wpcb_tracking_table;                        
                    $wpdb->get_results("SELECT ip FROM $wpcb_tbl_name WHERE visitdate LIKE '$current_month%' GROUP BY visitdate");
                    $numrows = $wpdb->num_rows;

                    echo '<td>';
                    echo $numrows;
                    $totalhits = $totalhits + $numrows;
                    echo '</td>';
                }

                echo '</tr>
                    </tbody>
                    </table>
                    <div id="' . $divid . '"></div>';
        }

        //display visit details (parameter (int) number 
        //of last visits to display of a particular box_id
        
        function visit_details($box_id, $startdate, $enddate) {
            
                $startingdate = DateTime::createFromFormat('m/d/Y H:i:s', $startdate.' 00:00:00');
                $startdate = date_format($startingdate, 'Y-m-d H:i:s');

                $endingdate = DateTime::createFromFormat('m/d/Y H:i:s', $enddate.' 23:59:59');
                $enddate = date_format($endingdate, 'Y-m-d H:i:s');

                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;                        
                $results = $wpdb->get_results("SELECT visitedpage, count(DISTINCT ip) AS uniques, "
                        . "COUNT(*) AS pageviews, SUM(CASE WHEN visittype != 'visit' THEN 1 ELSE 0 END) AS boxviews, "
                        . "SUM(CASE WHEN visittype = 'click' or visittype = 'optin' THEN 1 ELSE 0 END) AS conversions "
                        . "FROM $wpcb_tbl_name WHERE visitdate BETWEEN ' $startdate ' AND ' $enddate ' AND box_id = ' $box_id '"
                        . "GROUP BY visitedpage ORDER BY pageviews DESC LIMIT 0,100 ");

                if ($wpdb->num_rows != 0) {
                    echo '<table id="wpcb_stats_list_table" class="wp-list-table widefat fixed posts wpcb_stats_table">
                            <thead>
                                <tr>
                                    <th style="text-align:left;" width="35%">Page Url</th>
                                    <th>Unique Visits</th>                                    
                                    <th>Pageviews</th>
                                    <th>Box Views</th>
                                    <th>Conversions</th>
                                    <th>Conversion Rate</th>
                                </tr>
                            </thead>';
                    echo '<tbody>';
                    foreach ($results as $result) {
                        
                        if($result->conversions != 0 || $result->boxviews != 0){
                            $conversionrate = round(($result->conversions/$result->boxviews)*100, 2) ;
                        }
                        else{
                            $conversionrate = 0;
                        }
                        
                        echo    '<tr>
                                    <td class="alignleft"><a href="' . $result->visitedpage . '" target="_blank">' . $result->visitedpage . '</a></td>
                                    <td>' . $result->uniques . '</td>
                                    <td>' . $result->pageviews . '</td>
                                    <td>' . $result->boxviews . '</td>
                                    <td>' . $result->conversions . '</td>
                                    <td>' . $conversionrate . '%</td>
                                </tr>';
                    }
                    echo    '</tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align:left;" width="35%">Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                         </table>';
                }
                else{
                    echo "<h3>No data to show!</h3>";
                }
                
        }
        
        
        // Fetch data for AJAX call
        
        public function fetch_graph_data(){
            $box_id = $_POST['box_id'];
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $fetcheddata = $this->get_stats_for_graph($box_id, $startdate, $enddate);
            ob_start();
                $this->visit_details($box_id, $startdate, $enddate);
                $fetcheddata['listdata'] = ob_get_contents();  // get buffer content
            ob_end_clean();
            print json_encode($fetcheddata);
            die();
        }

}

