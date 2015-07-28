<?php
    global $wpdb;
    $wpcb_tracking = WPCB_Tracker::get_instance();
    $wpcb_tbl_name = $this->wpcb_boxes_table;
    $results = $wpdb->get_results("SELECT id,box_name,box_status,box_type FROM $wpcb_tbl_name ORDER BY id");
    $result_count = count($results);
    if($result_count != 0){
        $count = 1;
        ?>
        <table class="wp-list-table widefat fixed posts" style="clear: none;">
            <thead>
                <tr>
                    <th width="30%"><?php _e('WP Conversion Boxes Name','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Unique Visitors','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Pageviews','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Box Views','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversions','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversion Rate','wp-conversion-boxes'); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th width="30%"><?php _e('WP Conversion Boxes Name','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Unique Visitors','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Pageviews','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Box Views','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversions','wp-conversion-boxes'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversion Rate','wp-conversion-boxes'); ?></th>
                </tr>
            </tfoot>

            <tbody id="the-list">
            <?php
            foreach ($results as $result){
                $id = $result->id;
                $name = stripcslashes($result->box_name);
                $box_status = $result->box_status;
                $box_type = $result->box_type;
                $conversion_type = ($box_type == 1 || $box_type == 2 || $box_type == 5 || $box_type == 6) ? "<span class='wpcb_conversion_type'>". __('Optins','wp-conversion-boxes') . "</span>" : "<span class='wpcb_conversion_type'>". __('Clicks','wp-conversion-boxes') . "</span>";
                $pageviews = $wpcb_tracking->page_views($id);
                $uniquevisitors = $wpcb_tracking->unique_visitors($id);
                $uniqueboxviews = $wpcb_tracking->box_views($id);
                $conversions = $wpcb_tracking->get_conversions($id);
                $conversion_rate = ($uniqueboxviews != 0 && $conversions !=0) ? round(($conversions/$uniqueboxviews)*100, 2) : 0;
                
                if($box_status == 1){
                    $switch_class = 'switch_on';
                    $change_status_to = 0;
                }
                else{
                    $switch_class = 'switch_off';
                    $change_status_to = 1;
                }
                
                $wpcb_list = ++$count % 2 == 0 ? "<tr class='alternate wpcb-list-item-".$id."'>": "<tr class='wpcb-list-item-".$id."'>";
                $wpcb_list .= "<td width='30%'>".$name."<div class='wpcb-boxes-menu-toggle'><i class='fa fa-cog'></i> ". __('Options','wp-conversion-boxes') ." <span class='fa fa-caret-down'></span></div><div class='wpcb-boxes-menu'><div class='wpcb-menu-left'><h3>". __('Customizations','wp-conversion-boxes') ."</h3><ul class='wpcb-menu-ul'>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=1&id=' . $id )."' ><i class='fa fa-list'></i> ". __('Change Box Template','wp-conversion-boxes') ."</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=2&id=' . $id )."' ><i class='fa fa-paint-brush'></i> ". __('Customize Box Design','wp-conversion-boxes') ."</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=3&id=' . $id )."' ><i class='fa fa-wrench'></i> ". __('Box Settings','wp-conversion-boxes') ."</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&duplicate=' . $id )."' ><i class='fa fa-copy'></i> ". __('Duplicate This Box','wp-conversion-boxes') ."</a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a><i class='fa fa-info-circle'></i> ". __('Box Status','wp-conversion-boxes') ."<div wpcb_id='".$id."' change_status_to='".$change_status_to."' class='wpcb_disable_switch ".$switch_class."'> <div class='switch_toggle'></div> <span class='switch_on'>". __('Enabled','wp-conversion-boxes') ."</span> <span class='switch_off'>". __('Disabled','wp-conversion-boxes') ."</span> </div><div class='wpcb-disable-loading'><i class='fa fa-spinner fa-spin'></i></div></a></li>";
                $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a class='wpcb_delete' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-trash'></i> ". __('Delete Box','wp-conversion-boxes') ."</a></li></ul></div>";
                $wpcb_list .= "<div class='wpcb-menu-right'>";
                $wpcb_list .= "<h3><strike>A/B Test</strike></h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item'><a class='wpcb_no_ab_test'><i class='fa fa-random'></i> <strike>". __('A/B Test This Box (Pro)','wp-conversion-boxes') ."</strike></a></li></ul>";
                $wpcb_list .= "<h3>". __('Statistics','wp-conversion-boxes') ."</h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&action=allstats&id=' . $id )."' ><i class='fa fa-line-chart'></i> ". __('Detailed Stats','wp-conversion-boxes') ."</a></li>";
                $wpcb_list .= ($pageviews != 0) ? "<li class='wpcb-boxes-menu-item'><a class='wpcb_flush' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-undo'></i> ". __('Reset Box Stats','wp-conversion-boxes') ."</a></li>" : "";
                $wpcb_list .= "</ul><h3>". __('Shortcode','wp-conversion-boxes') ."</h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item' style='padding: 5px 10px;'><input type='text' id='wpcb-shortcode-select' value='[wpcb id=\"".$id."\"]' ></li>";
                $wpcb_list .= "</ul></div></div></td>";
                $wpcb_list .= "<td id='wpcb_unique_visitors'>".number_format($uniquevisitors)."</td>";
                $wpcb_list .= "<td id='wpcb_pageviews'>".number_format($pageviews)."</td>";
                $wpcb_list .= "<td id='wpcb_box_views'>".number_format($uniqueboxviews)."</td>";
                $wpcb_list .= "<td id='wpcb_ctr_optins'>".number_format($conversions).$conversion_type."</td>";
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
        echo sprintf( __( 'No boxes found. Please <a href="%s">click here</a> to create a new WP Conversion Box.','wp-conversion-boxes'), admin_url( 'admin.php?page=' . $this->wpcb_edit_slug));
    }
    