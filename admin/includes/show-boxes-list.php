<?php
    global $wpdb;
    $wpcb_tracking = WPCB_Tracker::get_instance();
    $wpcb_tbl_name = $this->wpcb_boxes_table;
    $results = $wpdb->get_results("SELECT id,box_name FROM $wpcb_tbl_name ORDER BY id");
    $result_count = count($results);
    if($result_count != 0){
        $count = 1;
        ?>
        <table class="wp-list-table widefat fixed posts" style="clear: none;">
            <thead>
                <tr>
                    <th width="30%">WP Conversion Boxes Name</th>
                    <th style="text-align: center;">Unique Visitors</th>
                    <th style="text-align: center;">Pageviews</th>
                    <th style="text-align: center;">Box Views</th>
                    <th style="text-align: center;">Conversion</th>
                    <th style="text-align: center;">Conversion Rate</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th width="30%">WP Conversion Boxes Name</th>
                    <th style="text-align: center;">Unique Visitors</th>
                    <th style="text-align: center;">Pageviews</th>
                    <th style="text-align: center;">Box Views</th>
                    <th style="text-align: center;">Conversion</th>
                    <th style="text-align: center;">Conversion Rate</th>
                </tr>
            </tfoot>

            <tbody id="the-list">
            <?php
            foreach ($results as $result){
                $id = $result->id;
                $name = stripcslashes($result->box_name);
                $pageviews = $wpcb_tracking->page_views($id);
                $uniquevisitors = $wpcb_tracking->unique_visitors($id);
                $uniqueboxviews = $wpcb_tracking->box_views($id);
                $conversions = $wpcb_tracking->get_conversions($id);
                $conversion_rate = ($uniqueboxviews != 0 && $conversions !=0) ? round(($conversions/$uniqueboxviews)*100, 2) : 0;
                $wpcb_list = ++$count % 2 == 0 ? "<tr class='alternate wpcb-list-item-".$id."'>": "<tr class='wpcb-list-item-".$id."'>";
                $wpcb_list .= "<td width='30%'>".$name."<div class='wpcb-boxes-menu-toggle'><i class='fa fa-cog'></i> Options <span class='fa fa-caret-down'></span></div><div class='wpcb-boxes-menu'><div class='wpcb-menu-left'><h3>Customizations</h3><ul class='wpcb-menu-ul'>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=1&id=' . $id )."' ><i class='fa fa-list'></i> Change Box Template</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=2&id=' . $id )."' ><i class='fa fa-paint-brush'></i> Customize Box Design</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=3&id=' . $id )."' ><i class='fa fa-wrench'></i> Box Settings</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&duplicate=' . $id )."' ><i class='fa fa-copy'></i> Duplicate This Box</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a class='wpcb_delete' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-trash'></i> Delete Box</a></li></ul></div>";
                $wpcb_list .= "<div class='wpcb-menu-right'>";
                $wpcb_list .= "<h3><strike>A/B Test</strike></h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item'><a class='wpcb_no_ab_test'><i class='fa fa-random'></i> <strike>A/B Test This Box (Pro)</strike></a></li></ul>";
                $wpcb_list .= "<h3>Statistics</h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&action=allstats&id=' . $id )."' ><i class='fa fa-line-chart'></i> Detailed Stats</a></li>";
                $wpcb_list .= ($pageviews != 0) ? "<li class='wpcb-boxes-menu-item'><a class='wpcb_flush' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-undo'></i> Reset Box Stats</a></li>" : "";
                $wpcb_list .= "</ul><h3>Shortcode</h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item' style='padding: 5px 10px;'><input type='text' id='wpcb-shortcode-select' value='[wpcb id=\"".$id."\"]' ></li>";
                $wpcb_list .= "</ul></div></div></td>";
                $wpcb_list .= "<td id='wpcb_unique_visitors'>".$uniquevisitors."</td>";
                $wpcb_list .= "<td id='wpcb_pageviews'>".$pageviews."</td>";
                $wpcb_list .= "<td id='wpcb_box_views'>".$uniqueboxviews."</td>";
                $wpcb_list .= "<td id='wpcb_ctr_optins'>".$conversions."</td>";
                $wpcb_list .= "<td id='wpcb_ctr_optins_percent'>".$conversion_rate."%</td>";
                $wpcb_list .= "</tr>";
                echo $wpcb_list;
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    else{
        echo "No boxes found. Please <a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug)."'>click here</a> to create a new WP Conversion Box.";
    }
    