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
                $tracker_data = array(
		    'ajaxurl' => admin_url( 'admin-ajax.php' ),
		    'nonce' => wp_create_nonce( 'ajax-example-nonce' ),
                    'processingHead' => __('Processing... Please Wait!' , 'wp-conversion-boxes'),
                    'processingBody' => __('It\'s taking longer than usual. Please hang on for a few moments...' , 'wp-conversion-boxes'),
                    'successHead' => __('Success!' , 'wp-conversion-boxes'),
                    'successBody' => __('Thank you for subscribing.' , 'wp-conversion-boxes'),
                    'errorHead' => __('Error!' , 'wp-conversion-boxes'),
                    'errorBody' => __('There was an error submitting your info.' , 'wp-conversion-boxes')
		);
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_script( 'tracker-js', PUBLIC_ASSETS_URL.'/js/tracker.js');
                wp_localize_script( 'tracker-js', 'trackerDefaultData', $tracker_data);
                wp_enqueue_script( 'jQuery-visible', PUBLIC_ASSETS_URL.'/js/jquery.visible.min.js');
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
                
                //get fisited page
                
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
        
        function visit_details($box_id) {

                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_tracking_table;                        
                $results = $wpdb->get_results("SELECT visitedpage, count(DISTINCT ip) AS uniques, "
                        . "COUNT(*) AS pageviews, SUM(CASE WHEN visittype != 'visit' THEN 1 ELSE 0 END) AS boxviews, "
                        . "SUM(CASE WHEN visittype = 'click' or visittype = 'optin' THEN 1 ELSE 0 END) AS conversions "
                        . "FROM $wpcb_tbl_name WHERE visitdate!='' AND box_id = ' $box_id ' "
                        . "GROUP BY visitedpage ORDER BY pageviews DESC LIMIT 0, 7");

                if ($wpdb->num_rows != 0) {
                    echo '<table class="wp-list-table widefat fixed posts wpcb_stats_table">
                            <thead>
                                <tr>
                                    <th style="text-align:left;" width="35%">'. __('Page Url','wp-conversion-boxes') .'</th>
                                    <th>'. __('Unique Visits','wp-conversion-boxes') .'</th>                                    
                                    <th>'. __('Pageviews','wp-conversion-boxes') .'</th>
                                    <th>'. __('Box Views','wp-conversion-boxes') .'</th>
                                    <th>'. __('Conversions','wp-conversion-boxes') .'</th>
                                    <th>'. __('Conversion Rate','wp-conversion-boxes') .'</th>
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
                                    <th style="text-align:left;" width="35%">'. __('Total','wp-conversion-boxes') .'</th>
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
                    echo "<h3>". __('No data to show!','wp-conversion-boxes') ."</h3>";
                }
        }

}

